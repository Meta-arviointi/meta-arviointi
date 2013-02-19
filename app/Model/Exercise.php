<?php

class Exercise extends AppModel {
	public $name = 'Exercise';
	
	public $belongsTo = array('Course');
	public $hasAndBelongsToMany = array('Action');

    public $validate = array(
        'exercise_name' => array(
            'required' => array(
                'rule' => array(
                	'notEmpty'
                ), 
                'message' => 'Exercise name is required'
            )
        ),
        'starttime' => array(
            'required' => array(
                'rule' => array(
                	'notEmpty'
                ), 
                'message' => 'Exercise start time is required'
            )
        ),
        'endtime' => array(
            'required' => array(
                'rule' => array(
                	'notEmpty'
                ), 
                'message' => 'Exercise end time is required'
            )
        ),
        'review_starttime' => array(
            'required' => array(
                'rule' => array(
                	'notEmpty'
                ), 
                'message' => 'Review start time is required'
            )
        ),
        'review_endtime' => array(
            'required' => array(
                'rule' => array(
                	'notEmpty'
                ), 
                'message' => 'Review end time is required'
            )
        )
    );

    public function compareDate($start, $end) {
    	return strtotime($start) < strtotime($end);
    }

	// Virtual field used in find('list') operations to
	// get proper options for html form <select>-tag
	public $virtualFields = array(
		'exercise_string' => '\'H\' || Exercise.exercise_number || \': \'  || Exercise.exercise_name'
	);
}
?>