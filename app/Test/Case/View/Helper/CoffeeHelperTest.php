<?php
App::uses('Controller', 'Controller');
App::uses('View', 'View');
App::uses('CoffeeHelper', 'View/Helper');

class CoffeeHelperTest extends CakeTestCase {

    public function setUp() {
		parent::setUp();
		$Controller = new Controller();
		$View = new View($Controller);
		$this->Coffee = new CoffeeHelper($View);
    }

	public function testCompile() {
		$result = $this->Coffee->compile("alert 'foo'");
		$this->assertContains("alert('foo');", $result);
	}

	public function testCompileFunction() {
		$result = $this->Coffee->compile("foo = (a, b) -> a + b");
		return $this->assertContains("foo = function(a, b) {", $result);
	}
}
?>