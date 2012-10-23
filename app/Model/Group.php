<?php

class Group extends AppModel {
	public $name = 'Group';
	
	public $belongsTo = array('Course', 'User');
	public $hasMany = array('Student');

	/**
	 * Fetches all groups and assistant names.
	 * @return all groups and their supervising assistant's name in array
	 */
	public function groups() {
		return $this->find('all', array(
			'contain' => array('User')
			)
		);

	}
}
?>