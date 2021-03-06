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

    public $uses = array('Course', 'User');

    private $_course;

    public $components = array(
        'Session',
        'Auth' => array(
            'loginAction' => array('controller' => 'users', 'action' => 'login', 'course_id' => false, 'admin' => false),
            'loginRedirect' => array('controller' => 'students', 'action' => 'index'),
            'logoutRedirect' => array('controller' => 'users', 'action' => 'login', 'course_id' => false),
            'authError' => 'Ole hyvä ja kirjaudu sisään',
            'authenticate' => array(
                'Form' => array(
                    'fields' => array('username' => 'basic_user_account')
                )
            )
        ),
        'RequestHandler'
    );

    public function beforeFilter() {

        // PRINT COURSE SELECTION DROPDOWN LIST TO HEADERBAR
        // Default is to print, override in controller to prevent print
        $this->set('course_selection', true);

        // Take newest course
        // (no need to be logged in because we
        // need course_id in login())
        if ( !$this->Session->read('Course.course_id') ) {
            $params = array(
                'order' => array('Course.starttime DESC')
            );
            $this->_course = $this->Course->find('first', $params);
            // if there were courses, write course_id to session
            if ( !empty($this->_course) ) {
                $this->Session->write('Course.course_id', $this->_course['Course']['id']);
            } else { // no courses, set course_id = 0 and don't print course_selection drop-down
                $this->Session->write('Course.course_id', 0);
                $this->set('course_selection', false);
            }
        }

        $this->set('all_courses', 
            $this->Course->find('list', array('fields' => 'Course.name'))
        );

        
        $uid = $this->Auth->user('id');
        if ( !empty($uid) ) {

            // Get all courses user has attended
            $courses = $this->User->user_courses($uid);

            $users_courses = array();
            // Iterate over courses and populate array ready to be used in 
            // selection list in courses/index/-view
            // format is Course.id as key and Course.name as value (like find('list'))
            foreach($courses as $course) {
                $users_courses[$course['id']] = $course['name'];
            }

            // THIS DROP-DOWN IS PRINTED TO LAYOUT, UNLESS 'course_selection'
            // IS SET TO false in Controller!!!

            // Set array to be used in drop-down selection
            $this->set('users_courses', $users_courses);
        }
        
    }

    public function beforeRender() {
        // Get new email messages from IMAP and insert them to the database
        // FIXME when the imap-functions are available!
        if(function_exists('curl_init')) {
            $json_url = 'https://meta-arviointi.sis.uta.fi/email_json.php';
            $ch = curl_init($json_url);
            $options = array(
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYPEER => false,
                //CURLOPT_HTTPHEADER => array('Content-type: application/json') ,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => array('secret_token' => 'm374arvioint1')
            );
            curl_setopt_array($ch, $options);
            $results = curl_exec($ch);
            $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            //print_r($results);
            //echo $http_status;
            $results = json_decode($results);


            if(!empty($results)) {
                $this->loadModel('EmailMessage');
                foreach($results as $r) {
                    $base64body = base64_decode($r->body, true);
                    if($base64body) {
                        $r->body = $base64body;
                    }

                    $this->EmailMessage->create();
                    $this->EmailMessage->set(array(
                        'sender' => $r->from,
                        'receiver' => $r->to,
                        'subject' => str_replace("_", " ", mb_decode_mimeheader($r->subject)),
                        'content' => quoted_printable_decode($r->body),
                        'sent_time' => date('Y-m-d H:i:sO', strtotime($r->date))
                    ));

                    $student = $this->EmailMessage->CourseMembership->Student->findByEmail(strtolower($r->from));
                    if(!empty($student['CourseMembership'])) {
                        $membership = null;
                        foreach($student['CourseMembership'] as $cm) {
                            if($membership == null) $membership = $cm; //TODO better logic for matching emails to courses
                        }
                        $this->EmailMessage->set('course_membership_id', $membership['id']);

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
                        'CourseMembership' => array(
                            'Course',
                            'EmailMessage' => array(
                                'conditions' => array(
                                    'EmailMessage.read_time' => null
                                )
                            )
                        )
                    )
                )
            ));
            //print_r($user);
            $email_messages = array();
            if(!empty($user) && !empty($user['Group'])) {
                foreach($user['Group'] as $group) {
                    foreach($group['CourseMembership'] as $cm) {
                        $email_messages = array_merge($email_messages, $cm['EmailMessage']);
                    }
                }
            }
            $this->set('email_notifications', $email_messages);


            //Check for chat messages 
            $this->loadModel('ChatMessage');
            $chat_messages = $this->ChatMessage->find('all', array(
                'order' => 'ChatMessage.created DESC',
                'limit' => 50,
                'conditions' => array(
                    'ChatMessage.created >' => date('Y-m-d H:i:s', strtotime('-7 days'))
                )
            ));
            $chat_messages = array_reverse($chat_messages);
            $this->set('chat_messages', $chat_messages);
        }
    }


}
