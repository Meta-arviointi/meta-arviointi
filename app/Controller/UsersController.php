<?php
class UsersController extends AppController {

    public $name = 'Users';

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(array('login', 'logout', 'forgotten_password'));
    }

    public function login() {
        if ($this->request->is('post')) {
            if ($this->Auth->login()) {
                // Select logged in user
                $this->User->id = $this->Auth->user('id');

                // Save last_login to session, before saving new value
                $last_login = $this->User->field('last_login');
                $this->Session->write('User.last_login', $last_login);
                $this->User->saveField('last_login', date('Y-m-d H:i:sO'));

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
                    } // TODO else = ei millään kurssilla (redirect?)
                }

                /* Get user's group in selected course */
                $user = $this->User->user_group($this->Auth->user('id'), $course_id);
                // If present, set group_id to session
                if ( !empty($user['Group']) ) {
                    $this->Session->write('User.group_id', $user['Group']['id']);
                }
                $cid = $this->Session->read('Course.course_id');
                if ( !empty($cid) ) {
                    // if there is Course, redirect normally
                    $this->redirect($this->Auth->redirect());
                } else { // no course, redirect to admin
                    $this->redirect(array(
                            'admin' => true,
                            'controller' => 'courses',
                            'action' => 'index'
                        )
                    );
                }

            } else {
                $this->Session->setFlash(__('Käyttäjätunnus tai salasana väärin, yritä uudelleen.'));
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
     * Create random password for $uid.
     * Saves users new password to database.
     * @return new password
     */
    public function create_new_password($uid) {
        if ( !empty($uid) ) {
            $this->User->id = $uid;
            if ( $this->User->exists() ) {
                $new_pw = $this->User->random_password();
                //debug($new_pw);
                $this->User->saveField('password', $new_pw, false);
                return $new_pw;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function forgotten_password($uid = null) {
        if ( $this->request->is('post') || !empty($uid) ) {
            $this->User->contain();
            $user = null;
            // form
            if ( $this->request->is('post') ) {
                $user = $this->User->findByEmail($this->request->data['User']['email']);    
            } else if ( !empty($uid) ) { // $uid
                // normal GET request, where admin has requested new password for user
                if ( $this->Auth->user('is_admin') ) {
                    $user = $this->User->findById($uid);    
                } else { // user is not admin, bad request
                    $this->Session->setFlash(__('Ei oikeuksia'));
                    $this->redirect($this->referer());
                }
            }
            if ( !empty($user) ) {
                // User is set, proceed with creating new password
                $uid = $user['User']['id'];
                $email = $user['User']['email'];
                $name = $user['User']['name'];
                // Create and save new pw
                $new_pw = $this->create_new_password($uid);
                if ( $new_pw ) {
                    // send new pw as email to user
                    App::import('Controller', 'EmailMessages');
                    $e = new EmailMessagesController();
                    $e->send_pw($email, $new_pw, $name);

                    // redirect
                    $this->Session->setFlash(__('Uusi salasana lähetetty osoitteeseen: '). $email);
                    $this->redirect($this->referer());
                } else {
                    $this->Session->setFlash(__('Salasanan luonti epäonnistui'));
                    $this->redirect($this->referer());
                }
            } else {
                $this->Session->setFlash(__('Tuntematon käyttäjä'));
            }
        } else {
            // Normal request. Renders a form for password reset.
            // Used from login screen
        }
    }


    public function admin_index() {
        // Don't print course_selection drop-down to layout
        $this->set('course_selection', false);

        $admin = $this->Auth->user('is_admin');
        $this->set('admin', $admin);
        
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
        $this->set('user_logins', $this->User->find('list', array('fields' => array('User.id', 'User.basic_user_account'))));
        $this->set('courses', $this->User->Course->find('list', array('fields' => array('id', 'name'))));
    }


    public function view($id = null) {
        if ( $id == $this->Auth->user('id') || $this->Auth->user('is_admin') ) {
            $this->User->id = $id;
            if ($this->User->exists()) {
                $this->set('referer', $this->referer());
                $this->set('user', $this->User->read(null, $id));    
            } else {
                $this->Session->setFlash(__('Tuntematon käyttäjä'));
                $this->redirect($this->referer());
            }
        } else {
            $this->Session->setFlash(__('Ei riittäviä oikeuksia'));
            // HUOM. Jos kirjoittaa urlilla /users/view/x, ainakaan
            // Firefox ei lähetä HTTP_REFERER kenttää headerissa.
            // Silloin ao. redirect menee '/'. Referer toimii Firefoxissa
            // jos on painettu linkkiä. 
            $this->redirect($this->referer());
        }
    }

    public function edit($id = null) {
        // set self variable 
        $this->set('self', false);
        if ( $id == $this->Auth->user('id') ) {
            $this->set('self', true);
        }
        if ( $this->request->is('post') || $this->request->is('put') ) {
            $this->set('user', $this->User->findById($id));
            $this->set('print_back', false);
            if ( $this->User->save($this->request->data) ) {
                $this->Session->setFlash(__('Käyttäjän tiedot päivitetty'));
                $this->redirect(array('action' => 'view', $this->User->id));
            } else {
                $this->Session->setFlash(__('Tietojen tallennus epäonnistui'));
                //debug($this->User->validationErrors);
            }

        } else {
            if ( $id == $this->Auth->user('id') || $this->Auth->user('is_admin') ) {
                $this->User->id = $id;
                if ($this->User->exists()) {
                    $user = $this->User->read(null, $id);
                    // No default password
                    unset($user['User']['password']);
                    $this->set('user', $user);
                    $this->data = $user;
                    $this->set('referer', $this->referer());
                    if ( $this->RequestHandler->isAjax() ) {
                        $this->set('print_back', false);
                    } else {
                        $this->set('print_back', true);
                    }
                } else {
                    $this->Session->setFlash(__('Tuntematon käyttäjä'));
                    $this->redirect($this->referer());
                }
            } else {
                $this->Session->setFlash(__('Ei riittäviä oikeuksia'));
                // HUOM. Jos kirjoittaa urlilla /users/view/x, ainakaan
                // Firefox ei lähetä HTTP_REFERER kenttää headerissa.
                // Silloin ao. redirect menee '/'. Referer toimii Firefoxissa
                // jos on painettu linkkiä.
                $this->redirect($this->referer());
            }
        }
    }

    public function add() {
        if ($this->request->is('post')) {
            $this->User->create();
            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash(__('Käyttäjä lisätty järjestelmään'));
                if ( !strcmp($this->referer(), Router::url(null, true)) ) {
                    // don't redirect to users/add-view
                    // redirect to users/index instead
                    $this->redirect(array(
                            'admin' => true,
                            'controller' => 'users',
                            'action' => 'index'
                        )
                    );
                } else {
                    $this->redirect($this->referer());
                }
            } else {
                $this->Session->setFlash(__('Käyttäjää ei voitu lisätä järjestelmään, tarkista tiedot.'));
            }
        }
    }


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
        // If 'Course' has data, return true
        return !empty($user_course['Course']);
    }


    public function test($cmid) {
        //debug($this->User->get_last_course($course_id));
        //debug($this->User->Action->new_actions());
        //debug($this->User->Action->new_actions_count());

        // course_id from session, include only Action and Exercise
        //debug($this->User->Action->open_actions();
        // course_id as parameter
        //debug($this->User->Action->open_actions();
        // course_id from session, contain also Student
        debug($this->User->Action->actions_count($cmid));
        
    }

    public function student_test($sid) {
        debug($this->User->Group->Student->findById($sid));
    }


}
