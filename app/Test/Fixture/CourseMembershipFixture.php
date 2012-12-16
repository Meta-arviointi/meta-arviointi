<?php

class CourseMembershipFixture extends CakeTestFixture {
	// public $import = array('model' => 'Group', 'records' => true);
	public $import = array('model' => 'CourseMembership');
	
	public $records = array(
		array(
			'id' => 1,
			'course_id' => 1, 
			'student_id' => 1, 
			'quit_id' => null,
			'quit_time' => null,
			'comment' => null
		)
	);
}

?>
