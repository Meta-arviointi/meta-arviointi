<?php
class CourseMembershipsController extends AppController {
    public $name = 'CourseMemberships';

    /**
     * Add new CourseMembership.
     * Also possible to create new Student and link to Group.
     * @param sid if set, create CourseMembership from old Student
     * where $sid is Student.id
     */
    public function add($sid = 0) {
        $cid = $this->Session->read('Course.course_id');
        if ( isset($this->request->data['Course']['id']) ) {
            $cid = $this->request->data['Course']['id'];
        } else {
            
        }

        if ( $this->request->is('post') || $this->request->is('put') ) {
            $user_id = null;
            if ( isset($this->request->data['CourseMembership']) ) {
                if ( isset($this->request->data['CourseMembership']['User']) ) {
                    $user_id = $this->request->data['CourseMembership']['User'];    
                }
                unset($this->request->data['CourseMembership']);
            }
            if ( $this->CourseMembership->saveAssociated($this->request->data) ) {
                $sid = $this->CourseMembership->Student->id;
                // Check if Student was selected for group
                if ( !empty($user_id) ) {
                    $group = $this->CourseMembership->Course->User->user_group($user_id, $cid);
                    if ( !empty($group) ) {
                        $gid = $group['Group']['id'];
                        $result = $this->CourseMembership->Student->Group->link_student($gid, $sid);
                        if ( !empty($result) ) {
                            $this->Session->setFlash(__('Opiskelija lisätty kurssille'));
                            $this->redirect(array(
                                    'admin' => false,
                                    'controller' => 'courses',
                                    'action' => 'view',
                                    $cid
                                )
                            );
                        } else {
                            $this->Session->setFlash(__('Opiskelijaa ei voitu liittää vastuuryhmään'));
                            $this->redirect(array(
                                    'admin' => false,
                                    'controller' => 'courses',
                                    'action' => 'view',
                                    $cid
                                )
                            );
                        }
                    } else { // Create new group
                        $result = $this->CourseMembership->Student->Group->save(array(
                                'Group' => array(
                                    'course_id' => $cid,
                                    'user_id' => $user_id
                                ),
                                'Student' => array(
                                    'id' => $sid
                                )
                            )
                        );
                        if ( !empty($result) ) {
                            $this->Session->setFlash(__('Opiskelija lisätty kurssille'));
                            $this->redirect(array(
                                    'admin' => false,
                                    'controller' => 'courses',
                                    'action' => 'view',
                                    $cid
                                )
                            );
                        } else {
                            $this->Session->setFlash(__('Opiskelijaa ei voitu liittää vastuuryhmään'));
                            $this->redirect(array(
                                    'admin' => false,
                                    'controller' => 'courses',
                                    'action' => 'view',
                                    $cid
                                )
                            );
                        }
                    }
                } else { // Student was linked to course, but not to a group
                    $this->Session->setFlash(__('Opiskelija lisätty kurssille ilman vastuuryhmää'));
                    $this->redirect(array(
                            'admin' => false,
                            'controller' => 'courses',
                            'action' => 'view',
                            $cid
                        )
                    );
                }
            } else {
                $this->Session->setFlash(__('Opiskelijaa ei voitu liittää kurssille'));
                $this->redirect(array(
                        'admin' => false,
                        'controller' => 'courses',
                        'action' => 'view',
                        $cid
                    )
                );
            }

        } else {
            if ( $sid > 0 ) {
                // Get user data for Form
                $this->data = $this->CourseMembership->Student->findById($sid);
            }
            $this->set('course_id', $cid);

            $users = $this->CourseMembership->Course->get_users($cid);
            $users_list = array();
            foreach( $users['User'] as $user ) {
                $users_list[$user['id']] = $user['name'];
            }

            $this->set('users', $users_list);
        }
    }

    public function admin_create_many($course_id = 0) {
        if ( $this->request->is('post') ) {
            // Check if more than one Student is submitted
            $students = $this->request->data['Student'];
            unset($this->request->data['Student']);
            $returns = array();
            $errors = array();

            // data for saving
            foreach($students as $student => $sid) {
                $data = array();
                $data['course_id'] = $this->request->data['Course']['id'];
                $data['User'] = $this->request->data['User'];
                $data['student_id'] = $sid;
                $return = $this->save_many($data);
                if ( !empty($return) ) {
                    $returns[] = $return;
                } else {
                    $errors[] = $sid;
                }
            }
            $succ_count = count($returns);
            $err_count = count($errors);
            $this->Session->setFlash("$succ_count opiskelijaa lisätty kurssille ($err_count epäonnistui)");
            $this->redirect(array(
                    'admin' => false,
                    'controller' => 'courses',
                    'action' => 'view',
                    $this->request->data['Course']['id']
                )
            );

        } else {
            $courses = $this->CourseMembership->Course->find('list', array(
                    'fields' => array('Course.id', 'Course.name')
                )
            );
            $this->set('courses', $courses);
            if ( $course_id > 0 ) {
                $users = $this->CourseMembership->Course->get_users($course_id);
                $users_list = array();
                foreach( $users['User'] as $user ) {
                    $users_list[$user['id']] = $user['name'];
                }
                $this->set('users', $users_list);
                $this->render('/Elements/user-selection');
            }

        }
    }

    public function save_many($data) {
        $user_id = null;
        if ( isset($data['User']) ) {
            $user_id = $data['User'];
            unset($data['User']);
        }
        $cid = $data['course_id'];
        //krsort($data);
        $this->CourseMembership->create();
        if ( $this->CourseMembership->save($data) ) {
            $sid = $data['student_id'];
            // Check if Student was selected for group
            if ( !empty($user_id) ) {
                $group = $this->CourseMembership->Course->User->user_group($user_id, $cid);
                if ( !empty($group) ) {
                    $gid = $group['Group']['id'];
                    $result = $this->CourseMembership->Student->Group->link_student($gid, $sid);
                    if ( !empty($result) ) {
                        return $sid;
                    } else {
                        return $sid;
                    }
                } else { // Create new group
                    $result = $this->CourseMembership->Student->Group->save(array(
                            'Group' => array(
                                'course_id' => $cid,
                                'user_id' => $user_id
                            ),
                            'Student' => array(
                                'id' => $sid
                            )
                        )
                    );
                    if ( !empty($result) ) {
                        return $sid;
                    } else {
                        $sid;
                    }
                }
            } else { // Student was linked to course, but not to a group
                return $sid;
            }
        } else {
            return false;
        }

    }

    /**
     * View method displays information of students attendance in
     * selected course.
     */
    public function view($id) {
        //debug($this->request);
        // Don't print course_selection drop-down to layout
        //$this->set('course_selection', false);

        /* Load ActionType-model to get action types for selection list */
        $this->loadModel('ActionType');
        // Set action types for view
        $this->set('action_types', $this->ActionType->types());

        // Find selected CourseMembership data
        $options = array(
            'conditions' => array(
                'CourseMembership.id' => $id
            ),
            'contain' => array(
                'Action' => array(
                    'order' => 'Action.created DESC',
                    'ActionComment',
                    'ActionType',
                    'Exercise',
                    'User'
                ),
                'Course',
                'EmailMessage',
                'Student' => array('Group' => array(
                        'conditions' => array(
                            'Group.course_id' => $this->Session->read('Course.course_id')
                        )
                    )
                )
            )
        );
        $course_membership = $this->CourseMembership->find('first', $options);

        $exercises = $this->CourseMembership->Course->Exercise->find('list', array(
                'conditions' => array(
                    'Exercise.course_id' => $course_membership['Course']['id']
                ),
                'fields' => array('Exercise.id', 'Exercise.exercise_string'),
                'order' => 'Exercise.exercise_number ASC'
            )
        );

        // Get list of users (used in course_membership['quit_id'])
        $users = $this->CourseMembership->Course->User->find('list', array(
            'fields' => array('User.name')
        ));

        // Fetch all other CourseMemberships of student $sid.
        // In view.ctp: Display links to student's other 
        // courses attended.
        $this->CourseMembership->contain('Course');
        $sid = $course_membership['Student']['id'];
        $student_courses = $this->CourseMembership->findAllByStudentId($sid);
        
        //debug($student_courses);
        //debug($course_membership);
        //debug($student_actions);
        //debug($exercises);
        $this->set('course_membership', $course_membership);
        $this->set('exercises', $exercises);
        $this->set('users', $users);
        $this->set('student_courses', $student_courses);

    }

    public function view_rdr() {
        // Init. variable to make sure it's not null at the end
        $course_id = $this->Session->read('Course.course_id');
        // get referring CourseMembership.id
        $url = explode('/', $this->referer(null,true));
        $cmid = $url[3];
        // Check if request is post
        if ( $this->request->is('post') ) {
            $course_id = $this->request->data['course_id'];
        } else if ( $this->request->is('get') ) { // .. or get
            $course_id = $this->request->query['course_id'];
        }
        // update user's Group ID to Session
        $this->CourseMembership->Course->User->set_new_group($this->Auth->user('id'), $course_id);
        
        $this->CourseMembership->Course->id = $course_id;
        if ( $this->CourseMembership->Course->exists() ) {

            $this->CourseMembership->id = $cmid;
            $sid = $this->CourseMembership->field('student_id');
            $new_cm = $this->CourseMembership->find('first', array(
                    'conditions' => array(
                        'course_id' => $course_id,
                        'student_id' => $sid
                    )    
                )
            );
            $this->Session->write('Course.course_id', $course_id);
            if ( !empty($new_cm) ) {
                $this->redirect(array(
                      'action' => 'view',
                      $new_cm['CourseMembership']['id']
                    )
                );
            } else {
                // Student is not in selected course, rdr to students/index
                $this->Session->setFlash(__('Opiskelija ei ole valitulla kurssilla'));
                $this->redirect(array(
                        'controller' => 'students',
                        'action' => 'index',
                        $course_id
                    )
                );
            }    
        } else {
            $this->Session->setFlash(__('Tuntematon kurssi'));
            $this->redirect($this->referer());
        }
    }

    public function delete($cm_id) {
        $this->CourseMembership->id = $cm_id;
        if ( $this->CourseMembership->exists() ) {
            // Check if Student has actions
            if ( !$this->CourseMembership->Action->actions_count($cm_id) ) {
                $student_id = $this->CourseMembership->field('student_id');
                $cid = $this->CourseMembership->field('course_id');
                $this->CourseMembership->delete($cm_id, false);
                $this->Session->setFlash(__('Opiskelija poistettu kurssilta'));

                // Delete Student -> Group association
                $group = $this->CourseMembership->Student->student_group($student_id, $cid);
                $gid = $group[0]['id'];

                $this->CourseMembership->Student->Group->unlink_student($gid,$student_id);
                // Call status check. Group is deleted if student_count = 0.
                $this->CourseMembership->Student->Group->update_status($gid);
                $this->redirect($this->referer());
            } else {
                $this->Session->setFlash(__('Opiskelijalle on annettu toimenpiteitä, ei voida poistaa'));
                $this->redirect($this->referer());
            }
        }
    }

    public function delete_many() {
        if ( $this->request->is('post') ) {
            $cid = $this->Session->read('Course.course_id');
            $action_students = array();
            $succ = 0;
            $err = 0;
            foreach($this->request->data['Student'] as $cmid => $sid) {
                if ( $this->CourseMembership->exists($cmid) ) {
                    // Check if Student has actions
                    if ( !$this->CourseMembership->Action->actions_count($cmid) ) {
                        $result = $this->CourseMembership->delete($cmid, false);
                        if ( $result ) {
                            $succ++;
                            // Delete Student -> Group association
                            $group = $this->CourseMembership->Student->student_group($sid, $cid);
                            $gid = $group[0]['id'];

                            $this->CourseMembership->Student->Group->unlink_student($gid,$sid);
                            // Call status check. Group is deleted if student_count = 0.
                            $this->CourseMembership->Student->Group->update_status($gid);    
                        } else {
                            $err++;
                        }
                    } else {
                        $action_students[$sid] = $cmid;
                        $err++;
                    }
                }
            }
            $ascount = count($action_students);
            $this->Session->setFlash(__("$succ opiskelijaa poistettu kurssilta ($err epäonnistui, $ascount:llä opiskelijalla on toimenpiteitä)"));
            $this->redirect($this->referer());
        }
    }

    public function set_quit($id) {
        $this->CourseMembership->read(null, $id);
        $this->CourseMembership->set(array(
            'quit_time' => date('Y-m-d H:i:s'),
            'quit_id'   => $this->Auth->user('id')
        ));
        if($this->CourseMembership->save()) {
            $this->Session->setFlash(__('Opiskelija merkitty keskeyttäneeksi.'));
        }
        $this->redirect(array('action' => 'view', $id));
    }

    public function unset_quit($id) {
        $this->CourseMembership->read(null, $id);
        $this->CourseMembership->set(array(
            'quit_time' => '',
            'quit_id'   => ''
        ));
        if($this->CourseMembership->save()) {
            $this->Session->setFlash(__('Keskeyttämismerkintä poistettu.'));
        }
        $this->redirect(array('action' => 'view', $id));
    }

    /**
     * AJAX function. 
     * @return review endtime of chosen exercise(_id)
     */
    public function review_end($exercise_id) {
        if( $this->RequestHandler->isAjax() ) {
            if ( !empty($exercise_id) ) {

                $this->autoRender=false;
                //Configure::write('debug', 0);
                $exercise = $this->CourseMembership->Course->Exercise->find('first', array(
                        'contain' => array(),
                        'conditions' => array('Exercise.id' => $exercise_id)
                    )
                );
                return date('d.m.Y H:i', strtotime($exercise['Exercise']['review_endtime']));
            }
        }

    }

    public function edit_comment($id) {
        if($this->request->is('put')) {
            if($this->CourseMembership->save($this->request->data)) {
                $this->Session->setFlash(__('Kommentti tallennettu!'));
                $this->redirect(array('action' => 'view', $id));
            }
            else $this->Session->setFlash('Ei onnistu!');
        }
        else {
            $this->data = $this->CourseMembership->findById($id);
        }
    }
}
