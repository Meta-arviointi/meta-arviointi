<?php
class CoursesController extends AppController {
    public $name = 'Courses';

    /**
     * Index method prints information about course and it's
     * attendees.
     */
    public function admin_index() {

/*
array(
    'conditions' => array('Model.field' => $thisValue), //array of conditions
    'recursive' => 1, //int
    'fields' => array('Model.field1', 'DISTINCT Model.field2'), //array of field names
    'order' => array('Model.created', 'Model.field3 DESC'), //string or array defining order
    'group' => array('Model.field'), //fields to GROUP BY
    'limit' => n, //int
    'page' => n, //int
    'offset' => n, //int
    'callbacks' => true //other possible values are false, 'before', 'after'
)*/
        $params = array(
	        'order' => array('Course.endtime DESC'),
		'fields' => array('Course.id', 'Course.name', 'Course.starttime', 'Course.endtime')
        );
                $this->_course = $this->Course->find('first', $params);

	$courses = $this->Course->find('all', $params);
        // Create array with 'Group.id' as key and 'User.name' as value
        // NOTE: 'User.name' is virtual field defined in User-model
        $course_groups = array();
        foreach($courses as $course) {
            $course_groups[$course['Course']['id']] = $course['Course']['name'];
        }
        // Set array to be used in drop-down selection
	$this->set('courses', $courses);
        $this->set('course_groups', $course_groups);
    }

    public function index($course_id = 0) {
        /* Check if course_id is requested in params */
        if ( $course_id > 0 ) {
            // Save new course_id to session for further use
            $this->Session->write('Course.course_id', $course_id);
        } else {
            // No course_id in request, take course_id from session
            $course_id = $this->Session->read('Course.course_id') == null ? 0 : $this->Session->read('Course.course_id');
        }

        $group_id = null;
        // Check if get-request has 'group_id'.
        // If so, set it to session 'User.group_id'
        if (isset($this->request->query['group_id'])) {
            $group_id = $this->request->query['group_id'];
            $this->Session->write('User.group_id', $group_id);
        } else { // No variable in get-request, take group_id from session
            // Read group_id from session, if 'null' group_id = 0.
            $group_id = $this->Session->read('User.group_id') == null ? 0 : $this->Session->read('User.group_id');
        }

        //debug($this->request);
        //debug($this->Session->read());
        //$course = $this->Course->kurssit($this->request->params['course_id']);

        // Hope to find a better solution for this later - Joni
        if ( $group_id > 0 ) {
            /*
             * Joins tables students, groups_students and groups
             * using find and use group_id as condition.
             */
            $options['joins'] = array(
                array('table' => 'groups_students',
                    'alias' => 'GroupStudent',
                    'type' => 'inner',
                    'conditions' => array(
                        'Student.id = GroupStudent.student_id')
                ),
                array('table' => 'groups',
                    'alias' => 'Group',
                    'type' => 'inner',
                    'conditions' => array(
                        'Group.id = GroupStudent.group_id')
                )
            );
            $options['conditions'] = array(
                'Group.id = ' . $group_id,
                'Group.course_id = ' . $course_id,
            );

            $options['contain'] = array(
                'Action',
                'CourseMembership' => array(
                    'conditions' => array(
                        'CourseMembership.course_id =' => $course_id)
                    ),
                'Group' => array(
                    'User' => array(
                            'fields' => 'name'
                    )
                )
            );

            $students = $this->Course->CourseMembership->Student->find('all', $options);
        //  debug($students);

        } else {
            // No group_id, show all Students
            $students = $this->Course->CourseMembership->Student->find('all', array(
                'contain' => array(
                    'Group' => array(
                        'User' => array(
                            'fields' => 'name'
                            )
                        ),
                    'Action',
                    'CourseMembership' => array(
                            'conditions' => array('CourseMembership.course_id' => $course_id)
                        )
                    )
                )
            );
            //debug($students);
        }

        /*
         * Remove elements that contain empty 'CourseMembership'
         * (meaning students who don't belong to current course (course_id)).
         * This way View don't need to handle empty array elements. 
         */
        foreach ($students as $index => $student) {
            if ( empty($student['CourseMembership']) ) {
                unset($students[$index]);
            } 
        }

        // Call Group-model to return groups with assistant names
        // in given course ($course_id from Session)
        $results = $this->Course->Group->groups($course_id);

        // Create array with 'Group.id' as key and 'User.name' as value
        // NOTE: 'User.name' is virtual field defined in User-model
        $user_groups = array();
        foreach($results as $result) {
            $user_groups[$result['Group']['id']] = $result['User']['name'];
        }

        // Set array to be used in drop-down selection
        $this->set('user_groups', $user_groups);

        $this->set('students', $students);
        // Group_id visible for view
        $this->set('group_id', $group_id);
    }

    /**
     * List all actions
     */
    public function index_actions() {
        $course_id = $this->Session->read('Course.course_id') == null ? 0 : $this->Session->read('Course.course_id');
        /*
        $this->Course->Exercise->Action->contain(array(
                'ActionType',
                'Exercise' => array(
                    'Course' => array(
                        'conditions' => array('Course.id' => $course_id)
                    )
                ),
                'Student',
                'User'
            )
        );*/

        // TODO: add functionality where only actions of the selected course are taken,
        // now it takes actions of all courses...
        $actions = $this->Course->Exercise->Action->find('all', array(
                'contain' => array(
                    'Student',
                    'User',
                    'ActionType',
                    'Exercise' => array(
                        'conditions' => array(
                            'Exercise.course_id' => $course_id
                        )
                    )
                )
             )
        );

        /*
         * Delete actions that don't belong to current course.
         */
        foreach ($actions as $index => $action) {
            if ( empty($action['Exercise']) ) {
                unset($actions[$index]);
            }
        }

        $this->set('actions', $actions);
        //debug($actions);

        // get mapping student.id => course_membership.id, to use in link on view side
        // '<td>' . $this->Html->link($action['Student']['last_name'] etc... 
        $course_memberships = $this->Course->CourseMembership->find('list', 
            array('fields' => array('CourseMembership.student_id','CourseMembership.id'),
                    'conditions' => array('CourseMembership.course_id' => $course_id)
            )
        );
        $this->set('course_memberships', $course_memberships);
    }

    public function admin_add() {
        if ($this->request->is('post')) {
            $this->Course->create();
            if ($this->Course->save($this->request->data)) {
                $this->Session->setFlash(__('The course has been added'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The course could not be added. Please, try again.'));
            }
        }
    }
}

