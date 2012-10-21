<?php

class Action extends AppModel {
	public $name = 'Action';

	public $belongsTo = array('Exercise', 'Student', 'User');
}
?>