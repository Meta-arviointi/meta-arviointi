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
			'order' => array(
				'Student.last_name' => 'asc'
			))
		);

	public function index() {

		$this->Student->recursive = 1;
		
		//$students = $this->Student->find('all');
		$students = $this->paginate('Student');
		$this->set('students', $students);


		/* Get users. 'User.name' is a virtual field! */
		$users = $this->Student->Group->User->find('list', array(
			'fields' => array('User.id', 'User.name'))
		);

		/* Get groups */
		$groups = $this->Student->Group->find('list', array(
			'fields' => array('Group.user_id', 'Group.id'))
		);


		$user_groups = array();

		/* Make array with 'Group.id' as key and 'User.name' as value.
		 * This way we get correct group_id => user name pairs for selection.
		 */
		foreach($groups as $user_id => $group_id) {
			$user_groups[$group_id] = $users[$user_id];
		}

		/* Set for selection */
		$this->set('user_groups', $user_groups);

		/* Debug */
		$this->set('users', $users);
		$this->set('groups', $groups);

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
