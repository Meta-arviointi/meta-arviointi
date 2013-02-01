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
        // Flag variable to indicate if course is changed
        $course_changed = false;

        /* Check if course_id is requested in params */
        if ( $course_id > 0 ) {
            // Check if course_id is changed from last request
            if ( $course_id != $this->Session->read('Course.course_id') ) {
                $course_changed = true;
            }
            // Save new course_id to session for further use
            $this->Session->write('Course.course_id', $course_id);
        } else {
            // No course_id in request, take course_id from session
            $course_id = $this->Session->read('Course.course_id') == null ? 0 : $this->Session->read('Course.course_id');
        }

        /* If course changed, update group_id to Session
         * to match user's group in new course.
         */
        if ( $course_changed ) {
            $this->set_new_group($this->Auth->user('id'), $course_id);
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
        $order = array('Student.last_name' => 'ASC');
        $students = $this->Course->CourseMembership->Student->find('all', array(
                'contain' => array(
                    'Group' => array(
                        'conditions' =>
                            ($group_id > 0 ? // if
                                array(
                                    'Group.course_id' => $course_id,
                                    'Group.id' => $group_id
                                    )
                                : array('Group.course_id' => $course_id) // else
                            )
                        ,
                        'User' => array(
                            'fields' => 'name'
                        )
                     ),
                    'CourseMembership' => array(
                            'conditions' => array('CourseMembership.course_id' => $course_id)
                    )
                ),
                'order' => $order
            )
        );

        /*
         * Delete students that don't belong to current course
         * or to selected group.
         * ['Group'] or ['CourseMembership'] is empty array.
         */
        foreach ($students as $index => $student) {
            if ( empty($student['Group']) || empty($student['CourseMembership']) ) {
                unset($students[$index]);
            } 
        }

        // Loop to fetch all actions related to one student
        foreach ($students as &$student) {
            $student_actions = $this->Course->Exercise->Action->find('all', array(
                    'conditions' => array(
                        'Action.student_id' => $student['Student']['id']
                    ),
                    'contain' => array(
                        'Exercise' => array(
                            'conditions' => array('Exercise.course_id' => $course_id)
                        )
                    )
                )
            );
            // Remove unnecessary depth from arrays that resulted
            // from call to find('all'), and add actions to
            // $student['Action'] -array
            foreach ($student_actions as $action) {
                // Check that action belongs to exercise
                // that belongs to current course
                if ( !empty($action['Exercise']) ) {
                    $student['Action'][] = $action['Action'];
                }
            }
        }

        //debug($students);

        // Call Group-model to return groups with assistant names
        // in given course ($course_id from Session)
        $results = $this->Course->Group->groups($course_id);

        // Create array with 'Group.id' as key and 'User.name' as value
        // NOTE: 'User.name' is virtual field defined in User-model
        $user_groups = array();
        foreach($results as $result) {
            $user_groups[$result['Group']['id']] = $result['User']['name'];
        }

        // Get all courses user has attended
        // TODO: what if user isadmin?
        $courses = $this->Course->User->user_courses($this->Auth->user('id'));

        $users_courses = array();
        // Iterate over courses and populate array ready to be used in 
        // selection list in courses/index/-view
        // format is Course.id as key and Course.name as value (like find('list'))
        foreach($courses as $course) {
            $users_courses[$course['id']] = $course['name'];
        }

        // Set array to be used in drop-down selection
        $this->set('user_groups', $user_groups);

        $this->set('students', $students);
        // Group_id visible for view
        $this->set('group_id', $group_id);

        $this->set('users_courses', $users_courses);
    }

    /**
     * Redirects to index-method.
     * Function is called from select-list Forms.
     * It takes $course_id from request and passes it to
     * index-method (above).
     */
    public function index_rdr() {
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
                'controller' => 'courses',
                'action' => 'index',
                $course_id
            )
        );

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
                $this->Session->setFlash(__('Kurssi lisätty'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('Kurssia ei voitu lisätä. Ole hyvä ja yritä myöhemmin uudestaan.'));
            }
        }
    }

    /*
     * After course_id is changed between requests,
     * update user's new group_id (related to new course) to Session.
     */
    private function set_new_group($user_id, $course_id) {
        $user = $this->Course->User->user_group($user_id, $course_id);
        // If present, set group_id to session
        if ( !empty($user['Group']) ) {
            $this->Session->write('User.group_id', $user['Group']['id']);
        } else {
            // No Group assigned to user in current course.
            // Delete group_id from session, so no old values remain.
            $this->Session->delete('User.group_id');
        }
    }
}

