<?php
class UsersController extends AppController {

    public $name = 'Users';

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(array('login', 'logout'));
    }

    public function login() {
        if ($this->request->is('post')) {
            if ($this->Auth->login()) {
                $this->redirect($this->Auth->redirect());
            } else {
                $this->Session->setFlash(__('Invalid username or password, try again'));
            }
        }
    }

    public function logout() {
        $this->Session->setFlash(__('Kirjauduit ulos'));
        $this->redirect($this->Auth->logout());
    }


    public function start() {
        //var_dump($this->Auth->user());
        $course_id = $this->request->params['course_id'];
        $is_admin = $this->Auth->user('is_admin');
        /* Get user's group in current course */
        $user = $this->User->Group->find('first', array(
            'conditions' => array(
                'Group.user_id' => $this->Auth->user('id'),
                'Group.course_id' => $course_id
                ),
            'contain' => array(
                'User',
                )
            )
        );
        if ( !empty($user['Group']) ) {
            $this->redirect('/'.$course_id.'/students/index?group_id='.$user['Group']['id']);    
        } else { // user has not a group in current course 
            $this->redirect(array(
                'controller' => 'students',
                'action' => 'index'
                )
            );
        }
        
    }
    public function index() {
        $this->User->recursive = 0;
        $this->set('users', $this->paginate());
    }
/*
    public function view($id = null) {
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
        $this->set('user', $this->User->read(null, $id));
    }
*/
    public function add() {
        if ($this->request->is('post')) {
            $this->User->create();
            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash(__('The user has been saved'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The user could not be saved. Please, try again.'));
            }
        }
    }
/*
    public function edit($id = null) {
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash(__('The user has been saved'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The user could not be saved. Please, try again.'));
            }
        } else {
            $this->request->data = $this->User->read(null, $id);
            unset($this->request->data['User']['password']);
        }
    }

    public function delete($id = null) {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
        if ($this->User->delete()) {
            $this->Session->setFlash(__('User deleted'));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('User was not deleted'));
        $this->redirect(array('action' => 'index'));
    }
*/
}
