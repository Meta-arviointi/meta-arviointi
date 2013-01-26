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
        // Get new email messages
        // FIXME when the imap-functions are available!
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


}
