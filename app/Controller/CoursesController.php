<?php
class CoursesController extends AppController {
    public $name = 'Courses';

    /**
     * Index method prints information about course and it's
     * attendees.
     */
    public function admin_index($cid = 0) {

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
    if ($cid > 0) {
        $this->Session->write('Course.admin_course', $cid);    

        $order = array('Student.last_name' => 'ASC');
        $students = $this->Course->CourseMembership->Student->find('all', array(
            'contain' => array(
                'Group' => array(
                    'conditions' => array(
                        'Group.course_id' => $cid
                    ),
                    'User' => array(
                        'fields' => 'name'
                    )
                ),
                'CourseMembership' => array(
                    'conditions' => array(
                        'CourseMembership.course_id' => $cid
                    )
                )
            ),
            'order' => $order
        ));
    } else {
        $this->Session->write('Course.admin_course', '0');
    }

	$courses = $this->Course->get_courses($cid);

    $course_groups = array();
    $exercise_list = array();
    $users_list = array();
    $groups = array();
    $quitcount = '0';

    foreach($courses as $course) {
        $course_groups[$course['Course']['id']] = $course['Course']['name'];
        $exercise_list = $course['Exercise'];
        $users_list = $course['User'];
    }

    foreach($users_list as $index => $stuff) {
        if ($stuff['is_admin'] == '1') { 
            unset($users_list[$index]);
        }
    }

	if ($cid > 0) {
        foreach($students as $index => $stuff) {
            if (empty($stuff['CourseMembership'])) {
                unset($students[$index]);
            } else {
                foreach($stuff['CourseMembership'] as $coursem) {
                    if ($coursem['quit_id'] > '0') {
                        $quitcount++;
                    }
                }
                foreach($stuff['Group'] as $grouploop) {
                    $uid = $grouploop['user_id'];
                    if (isset($groups[$uid])) {
                        $groups[$uid]++;
                    } else {
                        $groups[$uid] = '1';
                    }
                }
            }
        }
		$this->set('single_course', 'true');
        $this->set('scount', count($students));
        $this->set('acount', count($users_list));
        $this->set('quitcount', $quitcount);
        $this->set('actioncount', 'hardcode 0');
        $this->set('exercise_list', $exercise_list);
        $this->set('users_list', $users_list);
        $this->set('students_list', $students);
        $this->set('groups', $groups);
    }

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
            $this->Course->User->set_new_group($this->Auth->user('id'), $course_id);
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
         * Delete students that don't belong to current course.
         * Delete also if student don't belong to selected group.
         * If $group_id = 0, show also students who don't have group.
         * ['Group'] or ['CourseMembership'] is empty array.
         */
        foreach ($students as $index => $student) {
            if ( empty($student['CourseMembership']) ) {
                unset($students[$index]);
            } else if ( $group_id > 0 && empty($student['Group']) ) {
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

