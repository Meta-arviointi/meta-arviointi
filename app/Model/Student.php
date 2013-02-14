<?php

class Student extends AppModel {
	public $name = 'Student';

	public $hasMany = array( 
        'CourseMembership'
    );
	public $hasAndBelongsToMany = array('Group');

    public $virtualFields = array(
        'name' => 'Student.first_name || \' \' || Student.last_name'
    );
}
?>
