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
}
