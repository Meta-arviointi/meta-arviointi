<?php
class CoursesController extends AppController {
	public $name = 'Courses';

	/**
	 * Index method prints information about course and it's
	 * attendees.
	 */ 
	public function index($course_id = 0) {
		/* Check if course_id is requested in params */
		if ( $course_id > 0 ) {
			// Save new course_id to session for further use
			$this->Session->write('Course.course_id', $course_id);
		} else { 
			// No course_id in request, take id from session
			$course_id = $this->Session->read('Course.course_id') == null ? 0 : $this->Session->read('Course.course_id');
		}
		
		$group_id = null;
		// Check if get-request has 'group_id'.
		// If so, set it to session 'User.group_id'
		if (isset($this->request->query['group_id'])) {
			$group_id = $this->request->query['group_id'];
			$this->Session->write('User.group_id', $group_id);
		} else { // No variable in get-request, take group_id from session
			// Read group_id from session, if 'null' group_id = 0.
			$group_id = $this->Session->read('User.group_id') == null ? 0 : $this->Session->read('User.group_id');
		}

	//	debug($this->request);
	//	debug($this->Session->read());
		//$course = $this->Course->kurssit($this->request->params['course_id']);

		// Hope to find a better solution for this later - Joni
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
				'Group.id = ' . $group_id,
				'Group.course_id = ' . $course_id,
			);

			$options['contain'] = array(
				'Action',
				'CourseMembership' => array(
					'conditions' => array(
						'CourseMembership.course_id =' => $course_id)
					),
				'Group' => array(
					'User' => array(
							'fields' => 'name'
					)
				)
			);

			$students = $this->Course->CourseMembership->Student->find('all', $options);
		//	debug($students);

		} else {
			// No group_id, show all Students
			$students = $this->Course->CourseMembership->Student->find('all', array(
				'contain' => array(
					'Group' => array(
						'User' => array(
							'fields' => 'name'
							)
						),
					'Action',
					'CourseMembership' => array(
							'conditions' => array('CourseMembership.course_id' => $course_id)
						)
					)
				)
			);
			//debug($students);
		}


		// Call Group-model to return groups with assistant names
		$results = $this->Course->Group->groups();

		// Create array with 'Group.id' as key and 'User.name' as value
		// NOTE: 'User.name' is virtual field defined in User-model
		$user_groups = array();
		foreach($results as $result) {
			$user_groups[$result['Group']['id']] = $result['User']['name'];
		}

		// Set array to be used in drop-down selection
		$this->set('user_groups', $user_groups);

		$this->set('students', $students);
		// Group_id visible for view
		$this->set('group_id', $group_id);
	}
	
	/**
	 * List all actions
	 */
	public function index_actions() {
		$course_id = $this->Session->read('Course.course_id') == null ? 0 : $this->Session->read('Course.course_id');
		/*
		$this->Course->Exercise->Action->contain(array(
				'ActionType',
				'Exercise' => array(
					'Course' => array(
						'conditions' => array('Course.id' => $course_id)
					)
				),
				'Student',
				'User'
			)
		);*/
		
		// TODO: add functionality where only actions of the selected course are taken,
		// now it takes actions of all courses...
		$actions = $this->Course->Exercise->Action->find('all');
		$this->set('actions', $actions);
		
		// get mapping student.id => course_membership.id, to use in link on view side
		// '<td>' . $this->Html->link($action['Student']['last_name'] etc... 
		$course_memberships = $this->Course->CourseMembership->find('list', 
			array('fields' => array('CourseMembership.student_id','CourseMembership.id'),
					'conditions' => array('CourseMembership.course_id' => $course_id)
			)
		);
		$this->set('course_memberships', $course_memberships);
		
	}
	

}

