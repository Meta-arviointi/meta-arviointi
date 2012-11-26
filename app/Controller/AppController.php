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
            'loginAction' => array('controller' => 'users', 'action' => 'login', 'course_id' => false),
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
        if($this->Auth->user()) {
            if(!is_null($this->request->course_id)) {
                $this->_course = $this->Course->findById($this->request->course_id);
            }
            if(!$this->request->course_id || !$this->_course) {
                $params = array(
                    'order' => array('Course.starttime DESC')
                );
                $this->_course = $this->Course->find('first', $params);
                $this->redirect(array('course_id' => $this->_course['Course']['id']));
            }
        }
//        $this->Auth->allow('*');
//        $this->Auth->allow('add', 'logout');
    }
}
