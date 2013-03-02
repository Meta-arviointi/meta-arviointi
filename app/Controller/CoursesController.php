<?php
class CoursesController extends AppController {
    public $name = 'Courses';

    /**
     * Index method prints information about course and it's
     * attendees.
     */
    public function admin_index($cid = 0) {
        // don't print course_selection drop-down in admin_index
        $this->set('course_selection', false);
        $courses = $this->Course->get_courses($cid);
        $this->set('courses', $courses);
    }

    public function view($cid = 0) {
        if ( !empty($cid) ) {
            if ( $this->Course->exists($cid) ) {
                $this->Session->write('Course.course_id', $cid);
                $contain = array(
                    'Exercise' => array(
                        'conditions' => array(
                            'Exercise.course_id' => $cid
                        )
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

                $this->set('group_count', $group_count);
                $this->set('users_list', $users_list);
                $this->set('course', $course);
                $this->set('exercises', $exercises);
                $this->set('users', $users);
                $this->set('course_memberships', $course_memberships);


            } else {
                $this->Session->setFlash(__('Tuntematon kurssi'));
                $this->redirect($this->referer());
            }

        } else {
            $this->Session->setFlash(__('Tuntematon kurssi'));
            $this->redirect($this->referer());
        }

    }




    public function admin_add() {
        if ($this->request->is('post')) {
            $this->Course->create();
            if ($this->Course->save($this->request->data)) {
                $this->Session->setFlash(__('Kurssi lisätty'));
                $this->redirect(array('action' => 'index', $this->Course->id));
            } else {
                $this->Session->setFlash(__('Kurssia ei voitu lisätä. Ole hyvä ja yritä myöhemmin uudestaan.'));
            }
        }
    }

}

