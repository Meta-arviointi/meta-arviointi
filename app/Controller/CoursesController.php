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
                        )
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

        // Redirect to index() with $course_id
        $this->redirect(array(
                'admin' => false,
                'controller' => 'courses',
                'action' => 'view',
                $course_id
            )
        );
    }

    public function admin_add() {
        if ($this->request->is('post')) {
            $this->Course->create();
            if ($this->Course->save($this->request->data)) {
                $name = $this->Course->field('name');
                $this->Session->setFlash(__("Kurssi '$name' lisätty"));
                $this->redirect(array('admin' => false, 'action' => 'view', $this->Course->id));
            } else {
                $this->Session->setFlash(__('Kurssia ei voitu lisätä. Ole hyvä ja yritä myöhemmin uudestaan.'));
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

}

