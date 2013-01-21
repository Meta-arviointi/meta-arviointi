<?php

class Action extends AppModel {
	public $name = 'Action';

	public $belongsTo = array('Student', 'User', 'ActionType');
	public $hasMany = array('ActionComment' => array(
		'dependent' => true
		)
	);
	public $hasAndBelongsToMany = array('Exercise');
}
?>