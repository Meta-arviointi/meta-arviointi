<?php
App::uses('Controller', 'Controller');
App::uses('View', 'View');
App::uses('LessHelper', 'View/Helper');

class LessHelperTest extends CakeTestCase {

    public function setUp() {
		parent::setUp();
		$Controller = new Controller();
		$View = new View($Controller);
		$this->Less = new LessHelper($View);
    }

	public function testCompile() {
		$result = $this->Less->compile("#foo { .bar { color: red; } }");
		$this->assertContains("#foo .bar {", $result);
	}

	public function testCompileVariable() {
		$result = $this->Less->compile("@color: #f0f0f0; .foo { color: @color; }");
		$this->assertContains("color: #f0f0f0;", $result);
	}
}
?>