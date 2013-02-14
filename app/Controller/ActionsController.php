<?php
class ActionsController extends AppController {
    public $name = 'Actions';

    /**
     * List all actions
     */
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
            $this->Action->User->set_new_group($this->Auth->user('id'), $course_id);
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
        
        $student_group_filter = null;
        if ( $group_id ) {
            $student_group_filter = array('Group' => 
                array('conditions' =>
                     array('Group.id' => $group_id)
                )
            );
        }
        
        $actions = $this->Action->find('all', array(
                'contain' => array(
                    'Student' => $student_group_filter,
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
            } else {
                if ( $group_id > 0 &&  empty($action['Student']['Group']) ) {
                    unset($actions[$index]);
                }
            }
            
        }

        $this->set('actions', $actions);
        //debug($actions);

        // get mapping student.id => course_membership.id, to use in link on view side
        // '<td>' . $this->Html->link($action['Student']['last_name'] etc... 
        $course_memberships = $this->Action->Student->CourseMembership->find('list', 
            array('fields' => array('CourseMembership.student_id','CourseMembership.id'),
                    'conditions' => array('CourseMembership.course_id' => $course_id)
            )
        );
        $this->set('course_memberships', $course_memberships);


        // Call Group-model to return groups with assistant names
        // in given course ($course_id from Session)
        $results = $this->Action->Student->Group->groups($course_id);

        // Create array with 'Group.id' as key and 'User.name' as value
        // NOTE: 'User.name' is virtual field defined in User-model
        $user_groups = array();
        foreach($results as $result) {
            $user_groups[$result['Group']['id']] = $result['User']['name'];
        }

        $this->set('user_groups', $user_groups);


        // Get all courses user has attended
        // TODO: what if user isadmin?
        $courses = $this->Course->User->user_courses($this->Auth->user('id'));

        $users_courses = array();
        // Iterate over courses and populate array ready to be used in
        // selection list in actions/index/-view
        // format is Course.id as key and Course.name as value (like find('list'))
        foreach($courses as $course) {
            $users_courses[$course['id']] = $course['name'];
        }

        $this->set('users_courses', $users_courses);

        $this->set('group_id', $group_id);
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
                'controller' => 'actions',
                'action' => 'index',
                $course_id
            )
        );
    }

    /*
     * Method saves action to database.
     * Used in both cases: create new action, and edit existing.
     */
    public function save() {
        if($this->request->is('post') || $this->request->is('put') ) {
            //debug($this->request->data);

            // Convert date and time from Datetimepicker to match database timestamp format
            // ie. '06.02.2013 00:15' converts to '2013-02-06 00:15:00+0200'
            if ( $this->request->data['Action']['deadline'] ) {
                $deadline = $this->request->data['Action']['deadline'];
                $deadline_format = date_create_from_format('d.m.Y H:i', $deadline);
                $deadline_dbstring = date_format($deadline_format, 'Y-m-d H:i:sO');
                $this->request->data['Action']['deadline'] = $deadline_dbstring;
            }

            // If marked as handled, set handled_time to current time
            if ( $this->request->data['Action']['handled_id'] ) {
                $this->request->data['Action']['handled_time'] = date('Y-m-d H:i:sO');
            } else { // if handled mark was removed, remove information
                $this->request->data['Action']['handled_id'] = null;
                $this->request->data['Action']['handled_time'] = null;
            }

            if($this->Action->save($this->request->data)) {
                // Set ID of new saved Action or edited Action
                empty($this->request->data['Action']['id'])
                    ? $id = $this->Action->id : $id = $this->request->data['Action']['id'];
                $this->Session->setFlash(__("Toimenpide (id: $id) tallennettu!"));

                /* Prepare for redirect.
                 * Get CourseMembership.id of the
                 * just saved action, so redirect is possible
                 * to course_memberships/view/$id
                 */
                $action = $this->Action->find('first', array(
                        'conditions' => array('Action.id' => $id),
                        'contain' => array(
                            'Student' => array(
                                'CourseMembership' => array(
                                    'conditions' => array(
                                        'CourseMembership.course_id' => $this->Session->read('Course.course_id')
                                    )
                                )
                            )
                        )
                    )
                );

                $this->redirect(array(
                        'controller' => 'course_memberships',
                        'action' => 'view',
                        $action['Student']['CourseMembership'][0]['id'],
                        '?' => array('scroll_to' => 'action'.$action['Action']['id'])
                     )
                );            
            }
        }
    }

    public function add_action_comment() {
        if($this->request->is('post')) {
            //debug($this->request);
            if($this->Action->ActionComment->save($this->request->data)) {
                $this->redirect(array(
                    'controller' => 'course_memberships',
                    'action' => 'view',
                    // parameter value comes from POST data
                    $this->request->data['ActionComment']['redirect']
                    )
                );
            }
        }
    }

    public function delete($id) {
        if ( $this->Action->delete($id, true) ) {
            $this->Session->setFlash(__("Toimenpide ($id) poistettu"));
            $this->redirect($this->referer());
        } else {
            $this->Session->setFlash(__("Toimenpiteen ($id) poisto epäonnistui"));
        }
    }

    public function get_email_template($id) {
        $action = $this->Action->findById($id);
        $this->set('action', $action);
    }

    public function edit($id, $action_type_id = 0) {
        $this->Action->contain(array('Exercise', 'Student')); // include info about Exercise
        $action_data = $this->Action->findById($id);
        $this->set('action_data', $action_data);
        $this->set('action_types', $this->Action->ActionType->find('list'));
        $this->set('users', $this->Action->User->find('list', array(
                'fields' => array('User.name')
            )
        )
        );
        $this->set('exercises', $this->Action->Exercise->find('list', array(
                    'conditions' => array(
                        'Exercise.course_id' => $action_data['Exercise'][0]['course_id']
                    ),
                    'fields' => array('Exercise.id', 'Exercise.exercise_string')
                )
            )
        );

        $action_exercises = $action_data['Exercise'];
        $list_action_exercises = null;

        // Check if action belongs to multiple action_exercises
        // and list the ID's. ID's are used in checboxes below.
        if ( count($action_exercises) > 1 ) {
            foreach($action_exercises as $exercise) {
                $list_action_exercises[] = $exercise['id'];
            }
        } else { // only one exercise
            $list_action_exercises = $action_exercises[0]['id'];
        }
        $this->set('list_action_exercises', $list_action_exercises);
        if( $this->RequestHandler->isAjax() ) {
            if ( $action_type_id > 0 ) {
                $this->set('action_type_id', $action_type_id);
                $this->set('print_handled', true);
                $this->render('/Elements/generic-action-form');
            }
        }
    }

    public function create($cm_id = 0, $action_type_id = 0) {
        $this->Action->Student->CourseMembership->id = $cm_id;
        $cm = $this->Action->Student->CourseMembership;
        $student_id = $cm->field('student_id');
        if ( !empty($student_id) ) {
            $this->Action->Student->contain(); // only data about Student
            $student = $this->Action->Student->find('first',
                    array('conditions' => array('Student.id' => $student_id))
            );
            $action_data['Student'] = $student['Student'];

            $this->set('cm_id', $cm_id);
            $this->set('action_data', $action_data);
            $this->set('action_types', $this->Action->ActionType->find('list'));
            $this->set('exercises', $this->Action->Exercise->find('list', array(
                        'conditions' => array(
                            'Exercise.course_id' => $cm->field('course_id')
                        ),
                        'fields' => array('Exercise.id', 'Exercise.exercise_string')
                    )
                )
            );
            if ( $action_type_id > 0 ) {
                $this->set('action_type_id', $action_type_id);
                $this->set('print_handled', false);
                $this->render('/Elements/generic-action-form');
            }
        }
    }
}
