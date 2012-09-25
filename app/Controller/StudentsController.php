<?php
class StudentsController extends AppController {

	public $name = 'Students';

	public function index() {

		// Don't fetch associated data (Notifications etc)
		$this->Student->recursive = 0;
		
		$students = $this->Student->find('all');
		$this->set('students', $students);
	}

	public function view($id) {
		$student = $this->Student->findById($id);
		$this->set('student', $student);
	}

	public function add() {
		if($this->request->is('post')) {
			if($this->Student->save($this->request->data)) {
				$this->redirect(array('action' => 'view', $this->Student->id));
			}
		}
	}

	public function edit($id) {
		if($this->request->is('put')) {
			if($this->Student->save($this->request->data)) {
				$this->Session->setFlash('Tallennettu!');
				$this->redirect(array('action' => 'view', $this->Student->id));
			}
			else $this->Session->setFlash('Ei onnistu!');
		}
		else {
			$this->data = $this->Student->findById($id);
		}
	}

	public function delete($id) {
		if($this->Student->delete($id)) {
			$this->Session->setFlash('Poistettu!');
		}
		$this->redirect(array('action' => 'index'));
	}



	public function add_notification() {
		if($this->request->is('post')) {
			if($this->Student->Notification->save($this->request->data)) {
				$this->redirect(array('action' => 'view', $this->request->data['Notification']['student_id']));
			}
		}
	}
}
