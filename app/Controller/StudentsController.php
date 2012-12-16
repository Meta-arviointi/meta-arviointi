<?php
class StudentsController extends AppController {

	public $name = 'Students';

	public function index() {
		$group_id = 0;
		if(isset($this->request['url']['group_id'])) $group_id = $this->request['url']['group_id'];

		/* Paginointi ei toimi suoraan HABTM modelin kanssa kun ehtoina halutaan käyttää
		syvemmällä assosiaatiossa olevia arvoja. Esim Studenteja ei voi paginoida Group.id:n perusteella

		if ( $group_id > 0 ) { // Filter by group
			$this->paginate = array(
				'Student' => array(
					'limit' => 25,
					//'conditions' => array('Student.group_id' => $group_id), // Only students in group X
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
		*/
		if ( $group_id > 0 ) {
			/*
			 * Joins tables students, groups_students and groups
			 * using find and use group_id as condition.
			 */
			$options['joins'] = array(
				array('table' => 'groups_students',
					'alias' => 'GroupStudent',
					'type' => 'inner',
					'conditions' => array(
						'Student.id = GroupStudent.student_id')
				),
				array('table' => 'groups',
					'alias' => 'Group',
					'type' => 'inner',
					'conditions' => array(
						'Group.id = GroupStudent.group_id')
				)
			);
			$options['conditions'] = array(
				'Group.id = ' . $group_id
			);

			$options['contain'] = array(
				'Action',
				'Group' => array(
					'User' => array(
							'fields' => 'name'
					)
				)
			);

			$students = $this->Student->find('all', $options);

		} else { // Show all students
			$students = $this->Student->find('all', array(
				'contain' => array(
							'Action',
							'Group' => array(
								'User' => array(
									'fields' => 'name'
								)
							)
						)
				));

		}
		//$students = $this->paginate('Student');
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
		$this->Student->recursive = 3;
		$student = $this->Student->findById($id);
		$this->set('exercises', $this->Student->Action->Exercise->find('list'));
		$this->set('student', $student);
		/* Load ActionType-model to get action types for selection list */
		$this->loadModel('ActionType');
		$this->set('action_types', $this->ActionType->types());
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

	public function index_actions() {
		$actions = $this->Student->Action->find('all');
		$this->set('actions', $actions);
	}

	public function add_action() { // change to add_note?
		if($this->request->is('post')) {
			if($this->Student->Action->save($this->request->data)) {
				$this->redirect(array('action' => 'view', $this->request->data['Action']['student_id']));
			}
		}
	}

	public function add_action_comment() { // change to add_note?
		if($this->request->is('post')) {
			if($this->Student->Action->ActionComment->save($this->request->data)) {
				$ac = $this->Student->Action->ActionComment->read();
				$this->redirect(array('action' => 'view', $ac['Action']['student_id']));
			}
		}
	}
}
