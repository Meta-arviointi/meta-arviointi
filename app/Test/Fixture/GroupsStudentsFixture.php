<?php

class GroupsStudentsFixture extends CakeTestFixture {
	// public $import = array('model' => 'Group', 'records' => true);
	public $import = array('table' => 'groups_students');
	
	public $records = array(
		array(
			'id' => 1,
			'group_id' => 1,
			'student_id' => 1
		)
	);

}

?>
