<?php
class CourseMembershipsController extends AppController {
	public $name = 'CourseMemberships';
	
	/**
	 * View method displays information of students attendance in
	 * selected course.
	 */
	public function view($id) {
		//debug($this->request);
		
		/* Load ActionType-model to get action types for selection list */
		$this->loadModel('ActionType');
		// Set action types for view
		$this->set('action_types', $this->ActionType->types());
		
		// Find selected CourseMembership data
		$course_membership = $this->CourseMembership->findById($id);
		
		// get student's actions in selected course enrolment
		// TODO: check complexity
		$student_actions = $this->CourseMembership->Course->Exercise->Action->find('all', array(
				'contain' => array(
					'Student',
					'User',
					'ActionType',
					'ActionComment' => array('User'),
					'Exercise' => array(
						'conditions' => array(
							'Exercise.course_id' => $course_membership['Course']['id']
						)
					)
				),
				'conditions' => array(
					'Action.student_id' => $course_membership['Student']['id']
				)
				
			)
		);
		//debug($course_membership);
		//debug($student_actions);
		$this->set('course_membership', $course_membership);
		$this->set('student_actions', $student_actions);
		$this->set('exercises', $this->CourseMembership->Course->Exercise->find('list'));
		
	}
	
	public function set_quit($id) {
		$this->CourseMembership->read(null, $id);
		$this->CourseMembership->set(array(
			'quit_time' => date('Y-m-d H:i:s'),
			'quit_id' 	=> $this->Auth->user('id')
		));
		if($this->CourseMembership->save()) {
			$this->Session->setFlash(__('Opiskelija merkitty keskeyttäneeksi.'));
		}
		$this->redirect(array('action' => 'view', $id));
	}

	public function unset_quit($id) {
		$this->CourseMembership->read(null, $id);
		$this->CourseMembership->set(array(
			'quit_time' => '',
			'quit_id' 	=> ''
		));
		if($this->CourseMembership->save()) {
			$this->Session->setFlash(__('Keskeyttämismerkintä poistettu.'));
		}
		$this->redirect(array('action' => 'view', $id));
	}
}
