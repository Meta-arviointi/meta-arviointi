<?php

class Exercise extends AppModel {
	public $name = 'Exercise';
	
	public $belongsTo = array('Course');
	public $hasAndBelongsToMany = array('Action');

	// Virtual field used in find('list') operations to
	// get proper options for html form <select>-tag
	public $virtualFields = array(
		'exercise_string' => '\'H\' || Exercise.exercise_number || \': \'  || Exercise.exercise_name'
	);
}
?>