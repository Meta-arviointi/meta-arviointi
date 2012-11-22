<?php

class CourseMembership extends AppModel {
	public $name = 'CourseMembership';
	
	public $belongsTo = array('Course', 'Student');

}
?>