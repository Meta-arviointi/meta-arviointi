<?php

class Note extends AppModel {
	public $name = 'Note';

	public $belongsTo = array('Exercise', 'Student', 'User');
}
?>