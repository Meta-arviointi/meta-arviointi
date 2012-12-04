<?php

App::uses('Student', 'Model');
class StudentTest extends CakeTestCase {
	public $fixtures = array('app.student', 'app.group', 'app.action', 'app.course_membership', 'app.groups_students');
	
	public function setUp() {
		parent::setUp();
		$this->Student = ClassRegistry::init('Student');
	}
	
	public function testStudents() {
		$result = $this->Student->find('all');
		$expected = array(
			array(
				'Student' => array(
					'id' => 1,
					'student_number' => 12345, 
					'last_name' => 'Teppo', 
					'first_name' => 'Testilä',
					'email' => 'teppo.testila@uta.fi', 
				)
			)
		);
		
		//$this->assertEquals($expected, $result);
		
	}
}
?>
