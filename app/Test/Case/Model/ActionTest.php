<?php

App::uses('Action', 'Model');
class ActionTest extends CakeTestCase {
	public $fixtures = array('app.action', 'app.user');
	
	public function setUp() {
		parent::setUp();
		$this->Action = ClassRegistry::init('Action');
		$this->User = ClassRegistry::init('User');
	}
	
	public function testNewActionsCount() {
		$this->User->id = 4;
		$result = $this->Action->new_actions_count($this->User->field('last_login'));
		$this->assertEquals(15, $result);
		
		$this->User->id = 1;
		$result = $this->Action->new_actions_count($this->User->field('last_login'));
		$this->assertEquals(1, $result);
	}
}
