<?php
/* ProgramFields Test cases generated on: 2011-04-05 09:31:45 : 1302010305*/
App::import('Controller', 'ProgramFields');
App::import('Lib', 'AtlasTestCase');
class TestProgramFieldsController extends ProgramFieldsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class ProgramFieldsControllerTestCase extends AtlasTestCase {
	var $fixtures = array('app.program_field');

	function startTest() {
		$this->ProgramFields =& new TestProgramFieldsController();
		$this->ProgramFields->constructClasses();
	}

	function endTest() {
		unset($this->ProgramFields);
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