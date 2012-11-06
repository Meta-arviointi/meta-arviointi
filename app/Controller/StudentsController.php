<?php
class StudentsController extends AppController {

	public $name = 'Students';

	public function index() {
		$group_id = 0;
		if(isset($this->request['url']['group_id'])) $group_id = $this->request['url']['group_id'];

		if ( $group_id > 0 ) { // Filter by group
			$this->paginate = array(
				'Student' => array(
					'limit' => 25,
					'conditions' => array('Student.group_id' => $group_id), // Only students in group X
					'order' => array('Student.last_name' => 'asc'),
					'contain' => array(
						'Action',
						'Group' => array(
							'User' => array(
								'fields' => 'name'
							)
						)
					)
				)
			);

		} else { // All students
			$this->paginate = array(
				'Student' => array(
					'limit' => 25,
					'order' => array('Student.last_name' => 'asc'),
					'contain' => array(
						'Action',
						'Group' => array(
							'User' => array(
								'fields' => 'name'
							)
						)
					)
				)
			);
		}
		// 1 = default?
		$this->Student->recursive = 1;
		
		//$students = $this->Student->find('all');
		$students = $this->paginate('Student');
		$this->set('students', $students);


		/* Get data for select-form to filter groups by assistant */

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

		// Group_id visible for view
		$this->set('group_id', $group_id);

	}

	public function view($id) {
		$this->Student->recursive = 2;
		$student = $this->Student->findById($id);
		$this->set('exercises', $this->Student->Action->Exercise->find('list'));
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

	public function add_note() { // change to add_note?
		if($this->request->is('post')) {
			if($this->Student->Note->save($this->request->data)) {
				$this->redirect(array('action' => 'view', $this->request->data['Note']['student_id']));
			}
		}
	}

	public function add_action() { // change to add_note?
		if($this->request->is('post')) {
			if($this->Student->Action->save($this->request->data)) {
				$this->redirect(array('action' => 'view', $this->request->data['Action']['student_id']));
			}
		}
	}
}
