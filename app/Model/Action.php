<?php

class Action extends AppModel {
	public $name = 'Action';

	public $belongsTo = array('Student', 'User', 'ActionType');
	public $hasMany = array('ActionComment');
	public $hasAndBelongsToMany = array('Exercise');
}
?>