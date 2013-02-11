<?php
class ActionsController extends AppController {
    public $name = 'Actions';

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
                        $action['Student']['CourseMembership'][0]['id']
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
}
