<?php

class EmailMessage extends AppModel {
	public $name = 'EmailMessage';
	public $belongsTo = array('CourseMembership');
}

?>