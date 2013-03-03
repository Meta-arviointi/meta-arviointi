<?php

class Group extends AppModel {
	public $name = 'Group';
	
	public $belongsTo = array('Course', 'User');
	public $hasAndBelongsToMany = array('Student');

	/**
	 * Fetches groups and assistant names.
	 * If $course_id is set, filter  by selected course.
	 * If $course_id is not set, return all groups and assistants
	 * @return groups and their supervising assistant's name in array
	 */
	public function groups($course_id = 0) {
		if ( $course_id > 0 ) {
			return $this->find('all', array(
				'contain' => array('User'),
				'conditions' => array('Group.course_id' => $course_id)
				)
			);
		} else {
			// Return all groups and assistants
			return $this->find('all', array(
				'contain' => array('User')
				)
			);
		}

	}

	/**
	 * @return Count of students in group
	 */
	public function students_count($group_id) {
		if ( !empty($group_id) ) {
			$result = $this->find('first', array(
					'conditions' => array(
						'Group.id' => $group_id
					),
					'contain' => array(
						'Student'
					)
				)
			);
			return count($result['Student']);
		} else {
			return 0;
		}
	}

	public function link_student($gid, $sid) {
		if ( !empty($sid) && !empty($gid) ) {
			$this->contain('Student');
            $group = $this->findById($gid);
            $group_students = isset($group['Student']) ? $group['Student'] : array();
            array_push($group_students, $sid);
			$options = array(
				'Group' => array(
					'id' => $gid
				),
				'Student' => array(
					'Student' => $group_students
				)
			);
			return $this->save($options);
		} else {
			return false;
		}
	}

	public function unlink_student($gid, $sid) {
		if ( !empty($sid) && !empty($gid) ) {
			$this->contain('Student');
            $group = $this->findById($gid);
            $group_students = isset($group['Student']) ? $group['Student'] : array();
            foreach($group_students as $idx => $student) {
                if ( intval($student['id']) == intval($sid) ) {
                    unset($group_students[$idx]);
                }
            }
            $options = array(
                'Group' => array(
                    'id' => $gid
                ),
                'Student' => array(
                    'Student' => $group_students
                )
            );
            $this->save($options);
		} else {
			return false;
		}
	}
}
?>