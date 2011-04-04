<?php
/* ProgramEmails Test cases generated on: 2011-04-04 14:36:54 : 1301927814*/
App::import('Controller', 'ProgramEmails');
App::import('Lib', 'AtlasTestCase');
class TestProgramEmailsController extends ProgramEmailsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class ProgramEmailsControllerTestCase extends AtlasTestCase {
	var $fixtures = array('app.program_email', 'app.program', 'app.program_field', 'app.program_response');

	function startTest() {
		$this->ProgramEmails =& new TestProgramEmailsController();
		$this->ProgramEmails->constructClasses();
	}

	function endTest() {
		unset($this->ProgramEmails);
		ClassRegistry::flush();
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