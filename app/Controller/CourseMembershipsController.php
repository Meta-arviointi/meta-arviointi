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
            // check if we have User set in request data
            if ( isset($this->request->data['CourseMembership']) ) {
                if ( isset($this->request->data['CourseMembership']['User']) ) {
                    $user_id = $this->request->data['CourseMembership']['User'];    
                }
                unset($this->request->data['CourseMembership']);
            }
            // assume group is set
            $has_group = true;
            // check if assistant group was selected
            if ( !empty($user_id) ) {
                // check if user has group already
                $group = $this->CourseMembership->Course->User->user_group($user_id, $cid);
                if ( !empty($group) ) {
                    // user has group already, set $gid
                    $gid = $group['Group']['id'];
                    $this->request->data['Group'] = array('id' => $gid);
                } else { // Create new group using saveAssosiated
                    $this->request->data['Group'] = array(
                        'course_id' => $cid,
                        'user_id' => $user_id
                    );
                }
            } else { // Student was linked to course, but not to a group
                $has_group = false;
            }
            if ( $this->CourseMembership->saveAssociated($this->request->data) ) {
                $sid = $this->CourseMembership->Student->id;
                if ( $has_group ) {
                    $this->Session->setFlash(__('Opiskelija luotu kurssille ja vastuuryhmä asetettu'));
                } else {
                    $this->Session->setFlash(__('Opiskelija luotu kurssille ilman vastuuryhmää'));
                }
                $this->redirect(array(
                        'admin' => false,
                        'controller' => 'courses',
                        'action' => 'view',
                        $cid
                    )
                );
            } else {
                $this->Session->setFlash(__('Opiskelijan luominen epäonnistui'));
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
        $sid = $data['student_id'];
        $gid = null;

        // Check if Student was selected for group
        if ( !empty($user_id) ) {
            // yes, get/create user group and set $gid for saving
            $group = $this->CourseMembership->Group->User->user_group($user_id, $cid);
            if ( !empty($group) ) {
                $gid = $group['Group']['id'];
            } else { // Create new group
                // Create new group for user $uid
                $this->CourseMembership->Group->create();
                $this->CourseMembership->Group->save(array(
                    'course_id' => $cid, 
                    'user_id' => $uid
                    )
                );
                // set $gid
                $gid = $this->CourseMembership->Group->id;
            }
        }
        // if group id was found, add to $data for saving
        if ( !empty($gid) ) {
            $data['group_id'] = $gid;
        }
        $this->CourseMembership->create();
        if ( $this->CourseMembership->save($data) ) {
            return $sid;
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
                'Student',
                'Group'
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
        $url = parse_url($this->referer(null,true));
        $path = explode('/', $url['path']);
        $cmid = $path[3];
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

    public function delete($cm_id, $redirect = true) {
        $this->CourseMembership->id = $cm_id;
        if ( $this->CourseMembership->exists() ) {
            // Check if Student has actions
            if ( !$this->CourseMembership->Action->actions_count($cm_id) ) {
                $gid = $this->CourseMembership->field('group_id');
                $this->CourseMembership->delete($cm_id, false);
                $this->Session->setFlash(__('Opiskelija poistettu kurssilta'));

                if ( $gid ) {
                    // Student belonged to group.
                    // Call status check. Group is deleted if student_count = 0.
                    $this->CourseMembership->Group->update_status($gid);
                }
                if ( $redirect ) {
                    $this->redirect($this->referer());    
                } else {
                    // redirect disabled, return $cmid
                    return $cm_id;
                }
            } else {
                $this->Session->setFlash(__('Opiskelijalle on annettu toimenpiteitä, ei voida poistaa'));
                $this->redirect($this->referer());
            }
        }
    }

    public function delete_many() {
        if ( $this->request->is('post') ) {
            $action_students = array();
            $succ = 0;
            $err = 0;
            foreach($this->request->data['Student'] as $cmid => $sid) {
                if ( $this->CourseMembership->exists($cmid) ) {
                    // Check if Student has actions
                    if ( !$this->CourseMembership->Action->actions_count($cmid) ) {
                        $this->delete($cmid, false);
                        $succ++;
                    } else {
                        $action_students[$sid] = $cmid;
                        $err++;
                    }
                } else {
                    // Unknown membership
                    $err++;
                }
            }
            $ascount = count($action_students);
            $this->Session->setFlash(__("$succ opiskelijaa poistettu kurssilta ($err epäonnistui ($ascount:llä opiskelijalla on toimenpiteitä))"));
            $this->redirect($this->referer());
        }
    }

    public function set_groups($cid = 0) {
        if ( $cid <= 0 ) {
            $cid = $this->Session->read('Course.course_id');
        }
        if ( $this->request->is('post') ) {
            $students = $this->request->data['Student'];
            $uid = $this->request->data['User']['id'];
            $group = $this->CourseMembership->Group->User->user_group($uid, $cid);
            $succ = 0;
            $err = 0;
            if ( !empty($group) ) {
                $gid = $group['Group']['id'];
            } else { // user has no group
                // Create new group for user $uid
                $this->CourseMembership->Group->create();
                $this->CourseMembership->Group->save(array(
                    'course_id' => $cid, 
                    'user_id' => $uid
                    )
                );
                // set $gid
                $gid = $this->CourseMembership->Group->id;
            }
            if ( !empty($gid) ) {
                foreach($students as $cmid => $sid) {

                    $update_group = false;
                    // get possible old group
                    $sgroup = $this->CourseMembership->student_group($sid, $cid);
                    if ( !empty($sgroup) ) {
                        if ( $sgroup['id'] != $gid ) {
                            if ( $this->CourseMembership->set_group($cmid, $gid) ) {
                                // update old group as new group was saved
                                $update_group = true;
                                $succ++;
                            } else {
                                // error in saving
                                $err++;
                            }
                        } else {
                            // else = old and new group are same, no updates
                            $succ++;   
                        }
                    } else {
                        // student not in group, save new group 
                        if ( $this->CourseMembership->set_group($cmid, $gid) ) {
                            $succ++;
                        } else {
                            $err++;
                        }
                    }
                    if ( $update_group ) {
                        $this->CourseMembership->Group->update_status($sgroup['id']);
                    }
                }
            }
            $this->Session->setFlash("$succ opiskelijan vastuuryhmä päivitetty ($err epäonnistui)");
            $this->redirect(array(
                    'controller' => 'courses',
                    'action' => 'view',
                    $cid
                )
            );
        } else { // normal request, get data for Form
            // get users in course
            $users = $this->CourseMembership->Course->get_users($cid);
            $users_list = array();
            // make drop-down
            foreach( $users['User'] as $user ) {
                $users_list[$user['id']] = $user['name'];
            }

            $this->set('users', $users_list);
        }
    }

    public function set_group($cmid = 0) {
        $cid = $this->Session->read('Course.course_id');
        if ( $this->request->is('post') ) {
            $uid = $this->request->data['User']['id'];
            $cmid = $this->request->data['CourseMembership']['id'];
            $group = $this->CourseMembership->Group->User->user_group($uid, $cid);
            $gid = null;
            if ( !empty($group) ) {
                $gid = $group['Group']['id'];
            } else { // user has no group
                // Create new group for user $uid
                $this->CourseMembership->Group->create();
                $this->CourseMembership->Group->save(array(
                    'course_id' => $cid, 
                    'user_id' => $uid
                    )
                );
                // set $gid
                $gid = $this->CourseMembership->Group->id;
            }
            $this->CourseMembership->id = $cmid;

            // get possible old group
            $group_id = $this->CourseMembership->field('group_id');
            // flag if group should be updated
            $update_group = false;
            if ( !empty($group_id) ) {
                // check if new group is same as old
                if ( $group_id != $gid ) {
                    // different, save new group
                    if ( $this->CourseMembership->set_group($cmid, $gid) ) {
                        // update old group as new group was saved
                        $update_group = true;
                        $return = true;
                    } else {
                        // error in saving
                        $return = false;
                    }
                }  else {
                    // else = old and new group are same, no updates
                    $return = true;   
                }
            } else {
                // student not in group, save new group 
                if ( $this->CourseMembership->set_group($cmid, $gid) ) {
                    $return = true;
                } else {
                    $return = false;
                }
            }
            if ( $update_group ) {
                $this->CourseMembership->Group->update_status($group_id);
            }
            if ( $return ) {
                $this->Session->setFlash(__('Vastuuryhmä vaihdettu'));
                $this->redirect($this->referer());
            } else {
                $this->Session->setFlash(__('Vastuuryhmän tallennuksessa tapahtui virhe'));
                $this->redirect($this->referer());
            }

        } else {
            // get users in course
            $users = $this->CourseMembership->Course->get_users($cid);
            $users_list = array();
            // make drop-down
            foreach( $users['User'] as $user ) {
                $users_list[$user['id']] = $user['name'];
            }

            $this->set('users', $users_list);
            $this->set('course_membership_id', $cmid);
        }

    }


    public function set_quit($id, $redirect = true) {
        $this->CourseMembership->read(null, $id);
        $this->CourseMembership->set(array(
            'quit_time' => date('Y-m-d H:i:sO'),
            'quit_id'   => $this->Auth->user('id')
        ));
        if($this->CourseMembership->save()) {
            $this->Session->setFlash(__('Opiskelija merkitty keskeyttäneeksi.'));
        }
        if ( $redirect) {
            $this->redirect(array('action' => 'view', $id));
        } else {
            return $id;
        }
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
     * Set multiple quits (called from courses/view)
     */
    public function set_quits() {
        if ( $this->request->is('post') ) {
            $succ = 0;
            $err = 0;
            foreach($this->request->data['Student'] as $cmid => $sid) {
                if ( $this->CourseMembership->exists($cmid) ) {
                    $this->set_quit($cmid, false);
                    $succ++;
                } else {
                    // Unknown membership
                    $err++;
                }
            }
            $this->Session->setFlash("$succ opiskelijaa merkitty keskeyttäneeksi");
            $this->redirect($this->referer());
        }
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
