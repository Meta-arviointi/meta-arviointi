<?php

class Student extends AppModel {
	public $name = 'Student';

	public $hasMany = array('Notification', 'Action', 'Note');
	public $belongsTo = array('Group');


}
?>