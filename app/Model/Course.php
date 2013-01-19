<?php

class Course extends AppModel {
	public $name = 'Course';

	public $hasMany = array('Group', 'Exercise', 'CourseMembership');

	public $hasAndBelongsToMany = array('User');

    public $validate = array(
        'name' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Course name is required'
            )
        ),
        'starttime' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Course start time is required'
            )
        ),
        'endtime' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Course end time is required'
            )
        )
    );
}
?>
