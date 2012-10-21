<?php

class Group extends AppModel {
	public $name = 'Group';
	
	public $belongsTo = array('Course', 'User');
	public $hasMany = array('Student');


}
?>