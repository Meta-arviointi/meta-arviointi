<?php
class ActionsController extends AppController {
    public $name = 'Actions';


    public function add_action() {
        if($this->request->is('post')) {
            //debug($this->request->data);
            //$deadline_date = $this->request->data['Action']['deadline_date'];

            //$deadline_time = $this->request->data['Action']['deadline_time'];
            //$test = date('Y-m-d H:i:sO',)
            if($this->Action->save($this->request->data)) {
                // Get ID of new saved Action
                $id = $this->Action->id; 
                $this->Session->setFlash(__("Uusi toimenpide (id: $id) tallennettu!"));

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

    public function edit($id) {
        if($this->request->is('put')) {
            // Set modified to current time
            $this->request->data['Action']['modified'] = date('Y-m-d H:i:sO');

            // If marked as handled, set handled_time to current time
            if ( $this->request->data['Action']['handled_id'] ) {
                $this->request->data['Action']['handled_time'] = date('Y-m-d H:i:sO');
            } else { // if handled mark was removed, remove handled time
                $this->request->data['Action']['handled_time'] = null;
            }
            //debug($this->request->data);
            if( $this->Action->save($this->request->data) ) {
                $this->Session->setFlash(__('Toimenpide tallennettu!'));

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

                // Redirect to course_memberships controller
                $this->redirect(array(
                        'controller' => 'course_memberships',
                        'action' => 'view',
                        $action['Student']['CourseMembership'][0]['id']
                     )
                );
            } else {
                $this->Session->setFlash('Ei onnistunut!');
            }
        } else {
            $this->Action->contain('Exercise'); // include info about Exercise
            $this->data = $this->Action->findById($id);
            $this->set('action_types', $this->Action->ActionType->find('list'));
            $this->set('users', $this->Action->User->find('list', array(
                        'fields' => array('User.name')
                    )
                )
            );
            $this->set('exercises', $this->Action->Exercise->find('list', array(
                        'conditions' => array(
                            'Exercise.course_id' => $this->data['Exercise'][0]['course_id']
                        ),
                        'fields' => array('Exercise.id', 'Exercise.exercise_string')
                    )
                )
            );

        }
    }
}
