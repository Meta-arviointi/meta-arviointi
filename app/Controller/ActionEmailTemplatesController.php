<?php
class ActionEmailTemplatesController extends AppController {
    public $name = 'ActionEmailTemplates';

    public function admin_index() {
    	if($this->request->is('post')) {
    		if($this->ActionEmailTemplate->ActionType->saveAssociated($this->request->data)) {
    			$this->Session->setFlash(__('Viestipohja tallennettu.'));
    		}
    		else {
    			$this->Session->setFlash(__('Viestipohjan tallentaminen ei onnistunut. Yritä myöhemmin uudelleen.'));
    		}
    		$this->redirect(array('action' => 'index'));
    	}
    	$this->set('action_types', $this->ActionEmailTemplate->ActionType->find('all', array(
    		'conditions' => array('ActionType.active' => true),
    		'order' => array('ActionType.name ASC')
    	)));
    	$this->set('course_selection', false);
   	}
}
?>