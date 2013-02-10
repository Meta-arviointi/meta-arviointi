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
                $this->User->id = $this->Auth->user('id');
                // Save last_login to session, before saving new value
                $last_login = $this->User->field('last_login');
                $this->Session->write('User.last_login', $last_login);
                $this->User->saveField('last_login', date('Y-m-d H:i:sO'));
                $this->redirect($this->Auth->loginRedirect);
            } else {
                $this->Session->setFlash(__('Invalid username or password, try again'));
            }
        }
    }

    public function logout() {
        $this->Session->delete('Course.course_id');
        $this->Session->delete('User.group_id');
        // Update last_login when logging out
        $this->User->id = $this->Auth->user('id');

        $this->User->saveField('last_login', date('Y-m-d H:i:sO'));

        $this->Session->setFlash(__('Kirjauduit ulos'));
        $this->redirect($this->Auth->logout());
    }


    /**
     * Perform operations after successful login.
     * Such as selecting default group for user.
     */
    public function start() {

        // Empty group_id just in case it's old session
        $this->Session->delete('User.group_id');

        // Read course_id from session
        $course_id = $this->Session->read('Course.course_id');
        $is_admin = $this->Auth->user('is_admin');

        /*
         * Check if user is assigned to latest course.
         * If not, get last course user is assigned, and set
         * that course_id to session and proceed.
         */
        if ( !$this->assigned_to_course() ) {
            $last_course = $this->User->get_last_course($this->Auth->user('id'));
            if ( $last_course ) {
                $course_id = $last_course['id'];
                $this->Session->write('Course.course_id', $course_id);
            }
        }

        /* Get user's group in selected course */
        $user = $this->User->user_group($this->Auth->user('id'), $course_id);
        // If present, set group_id to session
        if ( !empty($user['Group']) ) {
            $this->Session->write('User.group_id', $user['Group']['id']);
        }
        // Redirect to students index-view
        $this->redirect(array(
            'controller' => 'courses',
            'action' => 'index'
            )
        );
    }

//    public function index() {
//        $this->User->recursive = 0;
//        $this->set('users', $this->paginate());
//    }

    public function admin_index() {
        $this->User->recursive = 0;
        $this->set('users', $this->paginate());

        $params = array(
                'order' => array('Course.endtime DESC'),
                'fields' => array('Course.id', 'Course.name', 'Course.starttime', 'Course.endtime')
        );
        $courses = $this->Course->find('all', $params);
        // Create array with 'Group.id' as key and 'User.name' as value
        // NOTE: 'User.name' is virtual field defined in User-model
        $course_groups = array();
        foreach($courses as $course) {
            $course_groups[$course['Course']['id']] = $course['Course']['name'];
        }
        // Set array to be used in drop-down selection
        $this->set('course_groups', $course_groups);

	    $params = array(
		    'order' => array('User.last_name ASC'),
		    'fields' => array('User.last_name', 'User.first_name', 'User.email'),
                'contain' => array('Course' => array('fields' => array('Course.id', 'Course.name'), 'order' => array('Course.id DESC'))
            )
	    );
	    $users = $this->User->find('all', $params);
	    $this->set('users', $users);
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
    public function admin_add() {
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
    /**
     * Is user assigned to given course?
     * @return true if user is assigned to course
     * and false if user is not assigned to course.
     *
     * If arguments (course_id and user_id) are empty, take
     * default values from Session and Auth.
     *
     */
    public function assigned_to_course($course_id = 0, $user_id = 0) {
        if ( empty($course_id) )
            $course_id = $this->Session->read('Course.course_id');
        if ( empty($user_id) )
            $user_id = $this->Auth->user('id');

        // HABTM relationship between Course and User
        $user_course = $this->User->find('first', array(
             'conditions' => array(
                 'User.id' => $user_id,
                 ),
             'contain' => array(
                'Course' => array(
                     'conditions' => array(
                        'Course.id' => $course_id
                        )
                    )
                )
            )
        );
        // If 'Course' is empty, return false
        // If 'Course' has data, return false
        return !empty($user_course['Course']);
    }

    public function test() {
        //debug($this->User->get_last_course($course_id));
        debug($this->User->Action->new_actions());
        debug($this->User->Action->new_actions_count());
    }


}
