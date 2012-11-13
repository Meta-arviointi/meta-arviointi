<?php

App::uses('Group', 'Model');
class GroupTest extends CakeTestCase {
	public $fixtures = array('app.group', 'app.user');
	
	public function setUp() {
		parent::setUp();
		$this->Group = ClassRegistry::init('Group');
	}
	
	public function testGroups() {
		$result = $this->Group->groups();
		$expected = array(
			array('Group' => array('id' => 1, 'course_id' => 1, 'user_id' => 2, 
				array('User' => array(
					'id' => 2, 
					'basic_user_account' => 23456,
					'last_name' => 'Assistentti',
					'first_name' => 'Asseri',
					'email' => 'asseri.assistentti@uta.fi',
					'is_admin' => 'false',
					'password' => 'testi')
					)
				)
			),
			array('Group' => array('id' => 2, 'course_id' => 1, 'user_id' => 3, 
				array('User' => array(
					'id' => 3, 
					'basic_user_account' => 34567,
					'last_name' => 'Assistentti',
					'first_name' => 'Testi',
					'email' => 'testi.assistentti@uta.fi',
					'is_admin' => 'false',
					'password' => 'testi')
					)
				)
			)	 
		);
		
		$this->assertEquals($expected, $result);
		
	}
}
?>
