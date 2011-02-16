<?php
/* Tests Test cases generated on: 2010-11-10 19:11:11 : 1289417831*/
App::import('Controller', 'Tests');

class TestTestsController extends TestsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class TestsControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.test');

	function startTest() {
		$this->Tests =& new TestTestsController();
		$this->Tests->constructClasses();
	}

	function endTest() {
		unset($this->Tests);
		ClassRegistry::flush();
	}

	function testAdminIndex() {

	}

	function testAdminView() {

	}

	function testAdminAdd() {

	}

	function testAdminEdit() {

	}

	function testAdminDelete() {

	}

}
?>