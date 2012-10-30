<?php
class StudentsController extends AppController {

	public $name = 'Students';

	public $helpers = array(
		'Html',
		'Form',
		'Paginator');

	public $paginate = array(
		'Student' => array(
			'limit' => 25,
			'order' => array('Student.last_name' => 'asc'),
			'contain' => array(
				'Note',
				'Group' => array(
					'User' => array(
						'fields' => 'name'
						)
					)
				)
			)
		);

	public function index() {

		// 1 = default?
		$this->Student->recursive = 1;
		
		//$students = $this->Student->find('all');
		$students = $this->paginate('Student');
		$this->set('students', $students);

		// Call Group-model to return groups with assistant names
		$results = $this->Student->Group->groups();

		// Create array with 'Group.id' as key and 'User.name' as value
		// NOTE: 'User.name' is virtual field defined in User-model

		$user_groups = array();
		foreach($results as $result) {
			$user_groups[$result['Group']['id']] = $result['User']['name'];
		}

		// Set array to be used in drop-down selection
		$this->set('user_groups', $user_groups);

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



	public function add_notification() { // change to add_note?
		if($this->request->is('post')) {
			if($this->Student->Note->save($this->request->data)) {
				$this->redirect(array('action' => 'view', $this->request->data['Note']['student_id']));
			}
		}
	}
}
