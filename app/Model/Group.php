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
}
?>