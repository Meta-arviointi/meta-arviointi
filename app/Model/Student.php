<?php

class Student extends AppModel {
	public $name = 'Student';

	public $hasMany = array('Action', 'Note');
	public $belongsTo = array('Group');


}
?>
