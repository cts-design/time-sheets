<?php
/* ProgramRegistrations Test cases generated on: 2011-03-28 21:10:55 : 1301346655*/
App::Import('Controller', 'ProgramRegistrations');

class TestProgramRegistrationsController extends ProgramRegistrationsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class ProgramRegistrationsControllerTestCase extends CakeTestCase {
	var $fixtures = array('navigation', 'program_registration');

	function startTest() {
		$this->ProgramRegistrations =& new TestProgramRegistrationsController();
		$this->ProgramRegistrations->constructClasses();
		debug($this->ProgramRegistrations);	

	}

	function endTest() {
		unset($this->ProgramRegistrations);
		ClassRegistry::flush();
	}
	
	function testIndex() {
			
	}

}
?>