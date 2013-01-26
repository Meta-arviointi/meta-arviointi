<?php
class CourseMembershipsController extends AppController {
    public $name = 'CourseMemberships';

    /**
     * View method displays information of students attendance in
     * selected course.
     */
    public function view($id) {
        //debug($this->request);

        /* Load ActionType-model to get action types for selection list */
        $this->loadModel('ActionType');
        // Set action types for view
        $this->set('action_types', $this->ActionType->types());

        // Find selected CourseMembership data
        $this->CourseMembership->recursive = 2;
        $course_membership = $this->CourseMembership->findById($id);

        // get student's actions in selected course enrolment
        $student_actions = $this->CourseMembership->Course->Exercise->Action->find('all', array(
                'contain' => array(
                    'Student',
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
                    'Action.student_id' => $course_membership['Student']['id']
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

        //debug($course_membership);
        //debug($student_actions);
        //debug($exercises);
        $this->set('course_membership', $course_membership);
        $this->set('student_actions', $student_actions);
        $this->set('exercises', $exercises);
        $this->set('users', $users);

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
                return date('d.m.Y', strtotime($exercise['Exercise']['review_endtime']));
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
