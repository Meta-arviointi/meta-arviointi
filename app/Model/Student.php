<?php

class Student extends AppModel {
	public $name = 'Student';

	public $hasMany = array(
        'Action' => array('order' => 'Action.created DESC'), 
        'Note' => array('order' => 'Note.created DESC')
    );
	public $belongsTo = array('Group');


}
?>
