<?php

class GroupFixture extends CakeTestFixture {
	// public $import = array('model' => 'Group', 'records' => true);
	
	public $fields = array(
		'id' => array('type' => 'integer', 'key' => 'primary'),
		'course_id' => array('type' => 'integer'),
		'user_id' => array('type' => 'integer')
	);
	public $records = array(
		array('id' => 1, 'course_id' => 1, 'user_id' => 2);
		array('id' => 2, 'course_id' => 1, 'user_id' => 3);
		//array('id' => 3, 'course_id' => 1, 'user_id' => 4);
	);

}

?>
