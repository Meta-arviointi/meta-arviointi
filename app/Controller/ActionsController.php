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
        
        $actions = $this->Action->find('all', array(
                'contain' => array(
                    'CourseMembership' => array(
                        'conditions' => array(
                            'CourseMembership.course_id' => $course_id
                        ),
                        'Student' => array(
                            'Group' => array(
                                'conditions' => array(
                                    'Group.course_id' => $course_id
                                )
                            )
                        )
                    ),
                    'User',
                    'ActionType',
                    'Exercise' => array(
                        'conditions' => array(
                            'Exercise.course_id' => $course_id
                        )
                    )
                ),
                'order' => array('Action.created DESC')
             )
        );

        /*
         * Delete actions that don't belong to current course.
         * If group is selected, delete actions which don't belong to
         * selected group's students'
         */
        foreach ($actions as $index => $action) {
            if ( empty($action['Exercise']) || empty($action['CourseMembership']) ) {
                unset($actions[$index]);
            }
        }
        //print_r($actions);
        $this->set('actions', $actions);
        //debug($actions);


        // Call Group-model to return groups with assistant names
        // in given course ($course_id from Session)
        $results = $this->Action->CourseMembership->Student->Group->groups($course_id);

        // Create array with 'Group.id' as key and 'User.name' as value
        // NOTE: 'User.name' is virtual field defined in User-model
        $user_groups = array();
        foreach($results as $result) {
            $user_groups[$result['Group']['id']] = $result['User']['name'];
        }

        $this->set('user_groups', $user_groups);

        $this->set('exercises', $this->Course->Exercise->find('list', array(
            'conditions' => array(
                'course_id' => $course_id
            ),
            'fields' => array('id', 'exercise_name'),
            'order' => 'Exercise.exercise_number'
        )));

        $this->set('action_types', $this->Action->ActionType->find('list', array(
            'conditions' => array(
                'active' => true
            ),
            'fields' => array('id', 'name'),
            'order' => 'ActionType.name'
        )));

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

        $this->set('users', $this->Action->User->find('list', array(
            'fields' => array('User.name')))
        );

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
        if ( $this->request->is('post') || $this->request->is('put') ) {
            //debug($this->request->data);

            // Convert date and time from Datetimepicker to match database timestamp format
            // ie. '06.02.2013 00:15' converts to '2013-02-06 00:15:00+0200'
            if ( isset($this->request->data['Action']['deadline']) ) {
                $deadline = $this->request->data['Action']['deadline'];
                $deadline_format = date_create_from_format('d.m.Y H:i', $deadline);
                $deadline_dbstring = date_format($deadline_format, 'Y-m-d H:i:sO');
                $this->request->data['Action']['deadline'] = $deadline_dbstring;
            } else { // no deadline, make sure it's null when saving to DB
                $this->request->data['Action']['deadline'] = null;
            }

            // If marked as handled, set handled_time to current time
            if ( !empty($this->request->data['Action']['handled_id']) ) {
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

                /* Redirect to course_membership/view page
                 */
                $this->redirect(array(
                        'controller' => 'course_memberships',
                        'action' => 'view',
                        $this->Action->field('course_membership_id'),
                        '?' => array('scroll_to' => 'action'.$id)
                     )
                );            
            } else {
                $this->Session->setFlash(__("Toimenpidettä ei voitu tallentaa (valittiinko harjoituksia?)"));
                $this->redirect($this->referer());
            }
        }
    }

    /**
     * Save many actions at once.
     * Format of $data-array should be like in
     * http://book.cakephp.org/2.0/en/models/saving-your-data.html#saving-related-model-data-habtm
     * Example:
     * array(
     *   (int) 0 => array(
     *     'Action' => array(
     *       'user_id' => '2',
     *       'action_type_id' => '1',
     *       'deadline' => '25.02.2013 15:35',
     *       'description' => 'adas',
     *       'course_membership_id' => '2'
     *     ),
     *     'Exercise' => array(
     *       'Exercise' => array(
     *          (int) 0 => '2',
     *          (int) 1 => '3'
     *       )
     *     )
     *   ),
     *   (int) 1 => array(
     *     'Action' => array(
     *       'user_id' => '2',
     *
     *   .... and so on..
     * @param data associated data to be saved
     *
     */
    public function save_many($data = array()) {
        if ( !empty($data) ) {
            // Convert date and time from Datetimepicker to match database timestamp format
            // ie. '06.02.2013 00:15' converts to '2013-02-06 00:15:00+0200'
            foreach($data as $item) {
                if ( isset($item['Action']['deadline']) ) {
                    $deadline = $item['Action']['deadline'];
                    $deadline_format = date_create_from_format('d.m.Y H:i', $deadline);
                    $deadline_dbstring = date_format($deadline_format, 'Y-m-d H:i:sO');
                    $item['Action']['deadline'] = $deadline_dbstring;
                } else { // no deadline, make sure it's null when saving to DB
                    $item['Action']['deadline'] = null;
                }
            }

            return $this->Action->saveAll($data);
        }
    }

    public function add_action_comment() {
        if($this->request->is('post')) {
            if($this->Action->ActionComment->save($this->request->data)) {
                $this->Action->id = $this->request->data['ActionComment']['action_id'];
                $cm = $this->Action->field('course_membership_id');
                $this->redirect(array(
                        'controller' => 'course_memberships',
                        'action' => 'view',
                        $cm
                    )
                );
            } else {
                $this->Session->setFlash(__('Kommenttia ei voitu luoda'));
                $this->redirect($this->referer());
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
        $this->Action->contain(array(
                'Exercise',
                'CourseMembership' => array(
                    'Student'
                )
            )
        );
        $action_data = $this->Action->findById($id);
        $this->set('action_data', $action_data);
        $this->set('action_types', $this->Action->ActionType->types());
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
        $cm = $this->Action->CourseMembership->find('first', array(
                'conditions' => array(
                    'CourseMembership.id' => $cm_id
                )
            )
        );
        if ( !empty($cm) ) {
            $this->set('action_data', $cm);
            $this->set('action_types', $this->Action->ActionType->types());
            $this->set('exercises', $this->Action->Exercise->find('list', array(
                        'conditions' => array(
                            'Exercise.course_id' => $cm['CourseMembership']['course_id']
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

    public function create_many($action_type_id = 0) {
        // POST = submit from generic-many-actions-form.ctp
        // Includes CourseMembership data. CourseMemberships are
        // selected in courses/index at #CreateManyActions-form
        if ( $this->request->is('post') ) {
            // Check if more than one CourseMembership is submitted
            $cms = $this->request->data['CourseMembership'];
            if ( count($cms) > 1 ) {
                $save_data = null; // data to be saved of one action
                $action = null; // Action-data
                $exercises = null; // Exercise-data
                $data = null; // data for save_many() (saveAll())

                // Loop through CourseMemberships and make individual calls to save()
                foreach($cms as $cm => $id) {
                    $action = $this->request->data['Action'];
                    $exercises = $this->request->data['Exercise'];
                    // Set CourseMembership ID to Action
                    $action['course_membership_id'] = $id;
                    $save_data['Action'] = $action;
                    $save_data['Exercise'] = $exercises;
                    $data[] = $save_data; // add data to be saved
                }

                if ( $this->save_many($data) ) {
                    $this->Session->setFlash(__("Uudet toimenpiteet luotu"));
                    $this->redirect(array(
                            'admin' => false,
                            'controller' => 'students',
                            'action' => 'index',
                            $this->Session->read('Course.course_id')
                        )
                    );
                } else {
                    $this->Session->setFlash(__('Toimenpiteiden luonti ei onnistunut'));
                    $this->redirect($this->referer());
                }
            } else { // only one student selected
                $cm_id = array_values($this->request->data['CourseMembership']);
                $this->request->data['Action']['course_membership_id'] = $cm_id[0];
                unset($this->request->data['CourseMembership']);
                $this->save(); // call save() (default saving for one action)

            }

        } else { // data for forms.
            $this->set('action_types', $this->Action->ActionType->types());
            $this->set('exercises', $this->Action->Exercise->find('list', array(
                        'conditions' => array(
                            'Exercise.course_id' => $this->Session->read('Course.course_id')
                        ),
                        'fields' => array('Exercise.id', 'Exercise.exercise_string')
                    )
                )
            );
            if ( $action_type_id > 0 ) {
                $this->set('action_type_id', $action_type_id);
                $this->set('print_handled', false);
                $this->render('/Elements/generic-many-actions-form');
            }
        }
    }
}
