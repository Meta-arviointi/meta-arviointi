<?php
class CoursesController extends AppController {
    public $name = 'Courses';

    /**
     * Index method prints information about course and it's
     * attendees.
     */
    public function admin_index() {
        // don't print course_selection drop-down in admin_index
        $this->set('course_selection', false);
        $courses = $this->Course->get_courses(0);
        $this->set('courses', $courses);
    }

    public function view($cid = 0) {
        $this->set('is_admin', $this->Auth->user('is_admin'));

        if ( !empty($cid) ) {
            if ( $this->Course->exists($cid) ) {
                // Check if course_id is changed from last request
                if ( $cid != $this->Session->read('Course.course_id') ) {
                    // course_id was changed from last request,
                    // set users group in current course to session
                    $this->Course->User->set_new_group($this->Auth->user('id'), $cid);
                }
                $this->Session->write('Course.course_id', $cid);
                $contain = array(
                    'Exercise' => array(
                        'conditions' => array(
                            'Exercise.course_id' => $cid
                        ),
                        'order' => 'Exercise.exercise_number ASC'
                    ),
                    'User' => array(
                        'Group' => array(
                            'conditions' => array(
                                'Group.course_id' => $cid
                            )
                        )
                    ),
                    'CourseMembership' => array(
                        'conditions' => array(
                            'CourseMembership.course_id' => $cid
                        ),
                        'Student' => array(
                            'order' => array(
                                'Student.last_name' => 'ASC'
                            ),
                            'Group' => array(
                                'conditions' => array(
                                    'Group.course_id' => $cid
                                )
                            )
                        ),
                        'Action',
                        'EmailMessage'
                    )
                );
                $course_data = $this->Course->get_course($cid, $contain);
                $course = $course_data['Course'];
                $exercises = $course_data['Exercise'];
                $users = $course_data['User'];
                $course_memberships = $course_data['CourseMembership'];

                $group_count = array();
                foreach($users as $user) {
                    if ( !empty($user['Group']) ) {
                        $group_id = $user['Group'][0]['id'];
                        $group_count[$user['id']] = $this->Course->Group->students_count($group_id);    
                    }
                }

                $users_list = $this->Course->User->find('list', array(
                        'fields' => array(
                            'User.id',
                            'User.name'
                        )
                    )
                );
                $edit = isset($this->request->query['edit']) ? $this->request->query['edit'] : null;
                if ( $edit ) {
                    if ( !strcmp($edit, 'exercises') ) {
                        $this->set('edit_exercises', true);
                        $this->data = $exercises;
                    } else {
                        $this->set('edit_exercises', false);
                    }
                } else {
                    $this->set('edit_exercises', false);
                }
                $this->set('group_count', $group_count);
                $this->set('users_list', $users_list);
                $this->set('course', $course);
                $this->set('exercises', $exercises);
                $this->set('users', $users);
                $this->set('course_memberships', $course_memberships);
                $this->set('course_id', $cid);


                // Create array with 'Group.id' as key and 'User.name' as value
                // NOTE: 'User.name' is virtual field defined in User-model
                $user_groups = array();
                foreach($course_data['User'] as $u) {
                    foreach($u['Group'] as $g) {
                        if(!empty($g)) {
                            $user_groups[$g['id']] = $u['name'];
                        }
                    }
                }
                $this->set('user_groups', $user_groups);


            } else {
                $this->Session->setFlash(__('Tuntematon kurssi'));
                $this->redirect($this->referer());
            }

        } else {
            $this->Session->setFlash(__('Tuntematon kurssi'));
            $this->redirect($this->referer());
        }

    }
    /**
     * Redirects to view-method.
     * Function is called from select-list Forms.
     * It takes $course_id from request and passes it to
     * view-method (above).
     */
    public function view_rdr() {
        // Init. variable to make sure it's not null at the end
        $course_id = $this->Session->read('Course.course_id');
        // Check if request is post
        if ( $this->request->is('post') ) {
            $course_id = $this->request->data['course_id'];
        } else if ( $this->request->is('get') ) { // .. or get
            $course_id = $this->request->query['course_id'];
        }

        if ( $this->Course->exists($course_id) ) {
            // Redirect to index() with $course_id
            $this->redirect(array(
                    'admin' => false,
                    'controller' => 'courses',
                    'action' => 'view',
                    $course_id
                )
            );
        } else { // unknown course_id
            $this->Session->setFlash(__('Tuntematon kurssi'));
            $this->redirect($this->referer());
        }
    }

    public function admin_add() {
        if ($this->request->is('post')) {
            $admins = $this->Course->User->find('list', array(
                    'fields' => array('User.id', 'User.name'),
                    'conditions' => array(
                        'User.id !=' => $this->Session->read('Auth.User.id'),
                        'User.is_admin' => true
                    )
                )
            );
            if ( !empty($admins) ) {
                $uid = $this->request->data['User']['id'];
                unset($this->request->data['User']['id']);
                $this->request->data['User']['User'][] = intval($uid);
                foreach( $admins as $id => $name ) {
                    $this->request->data['User']['User'][] = $id;
                }    
            }
            $this->Course->create();
            if ($this->Course->saveAll($this->request->data)) {
                $name = $this->Course->field('name');
                $this->Session->setFlash(__("Kurssi '$name' lisätty"));
                $this->redirect(array('admin' => false, 'action' => 'view', $this->Course->id));
            } else {
                $this->Session->setFlash(__('Kurssia ei voitu lisätä. Ole hyvä ja yritä myöhemmin uudestaan.'));
            }
        }
    }

    public function edit($cid) {
        if ( $this->request->is('post') || $this->request->is('put') ) {
            if ( $this->Course->save($this->request->data) ) {
                $this->Session->setFlash(__('Kurssin tiedot päivitetty'));
                $this->redirect(array('action' => 'view', $this->Course->id));
            } else {
                $this->Session->setFlash(__('Tietojen tallennus epäonnistui'));
            }

        } else {
            $this->Course->id = $cid;
            if ($this->Course->exists()) {
                $this->Course->contain();
                $course = $this->Course->read(null, $cid);
                // For View, format date to datetimepicker format
                $course['Course']['starttime'] = date('d.m.Y H:i', strtotime($course['Course']['starttime']));
                $course['Course']['endtime'] = date('d.m.Y H:i', strtotime($course['Course']['endtime']));
                $this->data = $course;
            } else {
                $this->Session->setFlash(__('Tuntematon kurssi'));
                $this->redirect($this->referer());
            }
        }
    }

    public function add_users() {
        if ( $this->request->is('post') || $this->request->is('put') ) {
            $this->Course->create();
            if ( $this->Course->save($this->request->data) ) {
                $this->Session->setFlash(__('Assistentit tallennettu kurssille'));
                $this->redirect(array(
                        'action' => 'view',
                        $this->Course->field('id')
                    )
                );
            } else {
                $this->Session->setFlash(__('Tallennus epäonnistui'));
                $this->redirect($this->referer());
            }

        } else {
            $cid = $this->Session->read('Course.course_id');
            $this->data = $this->Course->read(null, $cid);
            $users = $this->Course->User->find('list', array(
                    'fields' => array('User.id', 'User.name')
                )
            );

            // Check if user has group in current course, and if
            // group has students. If so, user can't be deleted from Course.
            // (checkbox disabled)
            $user_groups = array(); // list of user's that can't be deleted
            foreach($users as $uid => $name) {
                // get group
                $group = $this->Course->User->user_group($uid, $cid);
                if ( !empty($group) ) {
                    $gid = $group['Group']['id'];
                    $count = $this->Course->Group->students_count($gid);
                    // If there are students in group, add uid to list
                    if ( $count > 0 ) {
                        $user_groups[] = $uid;
                    }
                }

            }
            $this->set('users', $users);
            $this->set('user_groups', $user_groups);
        }
    }

    /**
     * Function is called from admin/users/index, when multiple
     * users are selected and added to group.
     * Function first fetches current users in course,
     * and appends new users from selection.
     * Then saveAll is performed and all related users are saved to course
     * (old and new).
     */
    public function add_many_users() {
        if ( $this->request->is('post') || $this->request->is('put') ) {
            $users = $this->request->data['User'];
            $course_id = $this->request->data['Course']['id'];
            $course = $this->Course->get_users($course_id);

            $savedata = array();
            // Loop through current users in course and add for saving
            foreach( $course['User'] as $user ) {
                $savedata['User'][] = $user['id'];
            }
            // Flip so we have User.id as key
            $flipped = array_flip($savedata['User']);
            foreach($users as $uid => $id) {
                if ( !isset($flipped[$uid]) ) {
                    // user not in course, add new user
                    // to list for saving
                    $savedata['User'][] = $uid;
                }
            }
            // unset old, and set new data for save
            unset($this->request->data['User']);
            $this->request->data['User'] = $savedata;
            // Save
            $this->Course->create();
            if ( $this->Course->saveAll($this->request->data) ) {
                $this->Session->setFlash(__('Assistentit tallennettu kurssille'));
                $this->redirect(array(
                        'admin' => true,
                        'controller' => 'users',
                        'action' => 'index'
                    )
                );
            } else {
                $this->Session->setFlash(__('Assistenttien tallennus epäonnistui'));
                $this->redirect(array(
                        'admin' => true,
                        'controller' => 'users',
                        'action' => 'index'
                    )
                );
            }

        } else {
            // Courses for selection list
            $this->set('courses', $this->Course->find('list'));
        }
    }

}

