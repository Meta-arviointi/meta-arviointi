<?php

class Exercise extends AppModel {
	public $name = 'Exercise';
	
	public $belongsTo = array('Course');
	public $hasAndBelongsToMany = array('Action');
}
?>