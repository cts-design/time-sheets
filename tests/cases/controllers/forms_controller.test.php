<?php
/* Forms Test cases generated on: 2011-03-17 14:46:54 : 1300373214*/
App::import('Controller', 'Forms');
App::import('Lib', 'AtlasTestCase');
class TestFormsController extends FormsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class FormsControllerTestCase extends AtlasTestCase {
	var $fixtures = array('app.form');

	function startTest() {
		$this->Forms =& new TestFormsController();
		$this->Forms->constructClasses();
	}

	function endTest() {
		unset($this->Forms);
		ClassRegistry::flush();
	}

	function testIndex() {

	}

	function testAdd() {

	}

	function testEdit() {

	}

	function testDelete() {

	}

	function testAdminIndex() {

	}

	function testAdminAdd() {

	}

	function testAdminEdit() {

	}

	function testAdminDelete() {

	}

}
?>