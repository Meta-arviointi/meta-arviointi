<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {

    public $helpers = array('Form', 'Html', 'Session', 'Less', 'Coffee', 'Paginator');

    public $uses = array('Course');

    private $_course;

    public $components = array(
        'Session',
        'Auth' => array(
            'loginAction' => array('controller' => 'users', 'action' => 'login', 'course_id' => false, 'admin' => false),
            'loginRedirect' => array('controller' => 'users', 'action' => 'start'),
            'logoutRedirect' => array('controller' => 'users', 'action' => 'login', 'course_id' => false),
            'authenticate' => array(
                'Form' => array(
                    'fields' => array('username' => 'basic_user_account')
                )
            )
        ),
        'RequestHandler'
    );

    public function beforeFilter() {
        if ( $this->Auth->user() ) {
            if ( !$this->Session->read('Course.course_id') ) {
                $params = array(
                    'order' => array('Course.starttime DESC')
                );
                $this->_course = $this->Course->find('first', $params);
                // write course_id to session
                $this->Session->write('Course.course_id', $this->_course['Course']['id']);
                //$this->redirect(array('course_id' => $this->_course['Course']['id']));
            }
        }

        //        $this->Auth->allow('*');
        //        $this->Auth->allow('add', 'logout');
    }

    public function beforeRender() {
        // Get new email messages from IMAP and insert them to the database
        // FIXME when the imap-functions are available!
        if(false && function_exists('curl_init')) {
            $json_url = 'http://kallunki.org/email_json.php';
            $ch = curl_init($json_url);
            $options = array(
                CURLOPT_RETURNTRANSFER => true,
                //CURLOPT_HTTPHEADER => array('Content-type: application/json') ,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => array('secret_token' => 'm374arvioint1')
            );
            curl_setopt_array($ch, $options);
            $results = json_decode(curl_exec($ch));
            //print_r($results);


            if(!empty($results)) {
                $this->loadModel('EmailMessage');
                foreach($results as $r) {
                    $this->EmailMessage->create();
                    $this->EmailMessage->set(array(
                        'sender' => $r->from,
                        'receiver' => $r->to,
                        'subject' => $r->subject,
                        'content' => $r->body,
                        'sent_time' => date('Y-m-d H:i:sO', strtotime($r->date))
                    ));

                    $student = $this->EmailMessage->Student->findByEmail(strtolower($r->from));
                    if($student) {
                        $this->EmailMessage->set('student_id', $student['Student']['id']);
                    }
                    $this->EmailMessage->save();
                }
            }
        }

        if($this->Auth->user()) {
            // Check for new messages in the database and pass notifications to the layout
            $this->loadModel('User');
            $this->User->Behaviors->load('Containable');
            //print_r($this->Auth->user('id'));
            $user = $this->User->find('first', array(
                'conditions' => array(
                    'User.id' => $this->Auth->user('id')
                ),
                'contain' => array(
                    'Group' => array(
                        'Student' => array(
                            'CourseMembership' => array(
                                'Course'
                            ),
                            'EmailMessage' => array(
                                'conditions' => array(
                                    'EmailMessage.read_time' => null
                                )
                            )
                        )
                    )
                )
            ));
            $email_messages = array();
            if(!empty($user) && !empty($user['Group'])) {
                foreach($user['Group'] as $group) {
                    foreach($group['Student'] as $student) {
                        $membership = null;
                        foreach($student['CourseMembership'] as $cm) {
                            if($membership == null || strtotime($cm['starttime']) > strtotime($membership['starttime'])) {
                                $membership = $cm;
                            }
                        }
                        foreach($student['EmailMessage'] as &$em) {
                            $em['course_membership_id'] = $membership['id'];
                        }
                        $email_messages = array_merge($email_messages, $student['EmailMessage']);
                    }
                }
            }
            $this->set('email_notifications', $email_messages);


            //Check for chat messages 
            $this->loadModel('ChatMessage');
            $chat_messages = $this->ChatMessage->find('all', array(
                'order' => 'ChatMessage.id DESC',
                'limit' => 10
            ));
            $chat_messages = array_reverse($chat_messages);
            $this->set('chat_messages', $chat_messages);
        }
    }


}
