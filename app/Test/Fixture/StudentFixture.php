<?php

class StudentFixture extends CakeTestFixture {
	/* Import data from production database */
	public $import = array('model' => 'Student');

	public $records = array(
		array(
			'id' => 1,
			'student_number' => 12345, 
			'last_name' => 'Teppo', 
			'first_name' => 'Testilä',
			'email' => 'teppo.testila@uta.fi',
		)
	);
}

?>
