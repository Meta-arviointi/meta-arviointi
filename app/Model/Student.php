<?php

class Student extends AppModel {
	public $name = 'Student';

	public $hasMany = array(
        'Action' => array('order' => 'Action.created DESC'), 
        'CourseMembership'
    );
	public $hasAndBelongsToMany = array('Group');

}
?>
