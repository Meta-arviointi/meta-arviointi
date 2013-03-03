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

    public function create_many($course_id = 0) {
        if ( $this->request->is('post') ) {

        } else {
            $courses = $this->CourseMembership->Course->find('list', array(
                    'fields' => array('Course.id', 'Course.name')
                )
            );
            $this->set('courses', $courses);
            if ( $course_id > 0 ) {
                
                $results = $this->CourseMemberhip->Course->get_users($course_id, array('User'));
                
                $this->set('user_groups', $user_groups);
            }

        }
    }

    /**
     * View method displays information of students attendance in
     * selected course.
     */
    public function view($id) {
        //debug($this->request);
        // Don't print course_selection drop-down to layout
        $this->set('course_selection', false);

        /* Load ActionType-model to get action types for selection list */
        $this->loadModel('ActionType');
        // Set action types for view
        $this->set('action_types', $this->ActionType->types());

        // Find selected CourseMembership data
        $course_membership = $this->CourseMembership->findById($id);

        // get student's actions in selected course enrolment
        $student_actions = $this->CourseMembership->Action->find('all', array(
                'contain' => array(
                    'User',
                    'ActionType',
                    'ActionComment' => array('User'),
                    'Exercise' => array(
                        'conditions' => array(
                            'Exercise.course_id' => $course_membership['Course']['id']
                        )
                    )
                ),
                'conditions' => array(
                    'Action.course_membership_id' => $id
                ),
                'order' => array('Action.created DESC')
            )
        );

        /*
         * Delete actions that don't belong to current course.
         */
        foreach ($student_actions as $index => $action) {
            if ( empty($action['Exercise']) ) {
                unset($student_actions[$index]);
            } 
        }

        $exercises = $this->CourseMembership->Course->Exercise->find('list', array(
                'conditions' => array(
                    'Exercise.course_id' => $course_membership['Course']['id']
                ),
                'fields' => array('Exercise.id', 'Exercise.exercise_string')    
            )
        );

        // Get list of users (used in course_membership['quit_id'])
        $users = $this->CourseMembership->Course->User->find('list', array(
            'fields' => array('User.name')
        ));

        // Fetch all other CourseMemberships of student $sid.
        // In view.ctp: Display links to student's other 
        // courses attended.
        $sid = $course_membership['Student']['id'];
        $student_courses = $this->CourseMembership->findAllByStudentId($sid);
        
        //debug($student_courses);
        //debug($course_membership);
        //debug($student_actions);
        //debug($exercises);
        $this->set('course_membership', $course_membership);
        $this->set('student_actions', $student_actions);
        $this->set('exercises', $exercises);
        $this->set('users', $users);
        $this->set('student_courses', $student_courses);

    }

    public function delete($cm_id) {
        $this->CourseMembership->id = $cm_id;
        if ( $this->CourseMembership->exists() ) {
            $student_id = $this->CourseMembership->field('student_id');
            $cid = $this->CourseMembership->field('course_id');
            $this->CourseMembership->delete($cm_id, false);
            $this->Session->setFlash(__('Opiskelija poistettu kurssilta'));

            // Delete Student -> Group association
            $group = $this->CourseMembership->Student->student_group($student_id, $cid);
            $gid = $group[0]['id'];

            $this->CourseMembership->Student->Group->contain('Student');
            $group = $this->CourseMembership->Student->Group->findById($gid);
            $group_students = isset($group['Student']) ? $group['Student'] : array();
            foreach($group_students as $idx => $student) {
                if ( intval($student['id']) == intval($student_id) ) {
                    unset($group_students[$idx]);
                }
            }
            $options = array(
                'Group' => array(
                    'id' => $gid
                ),
                'Student' => array(
                    'Student' => $group_students
                )
            );
            $this->CourseMembership->Student->Group->save($options);

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
