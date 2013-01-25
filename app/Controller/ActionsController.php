<?php
class ActionsController extends AppController {
    public $name = 'Actions';


    public function add_action() {
        if($this->request->is('post')) {
            //debug($this->request->data);
            if($this->Action->save($this->request->data)) {
                $this->redirect(array(
                    'controller' => 'course_memberships',
                    'action' => 'view',
                    // parameter value comes from POST data
                    $this->request->data['Action']['redirect']
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
            if($this->Action->save($this->request->data)) {
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
            }
            else $this->Session->setFlash('Ei onnistu!');
        }
        else {
            $this->Action->contain(); // fetch only info about Action
            $this->data = $this->Action->findById($id);

        }
    }
}
