<?php

class CourseMembership extends AppModel {
	public $name = 'CourseMembership';
	
	public $belongsTo = array('Course', 'Student');
	public $hasMany = array(
		'Action',
        'EmailMessage' => array('order' => 'EmailMessage.sent_time DESC')
    );

}
?>