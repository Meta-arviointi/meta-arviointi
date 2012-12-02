<?php

class Course extends AppModel {
	public $name = 'Course';

	public $hasMany = array('Group', 'Exercise', 'CourseMembership');

	public $hasAndBelongsToMany = array('User');
	

}
?>