<?php
/* ProgramRegistration Test cases generated on: 2011-03-28 21:10:45 : 1301346645*/
App::import('Model', 'ProgramRegistration');
App::import('Lib', 'AtlasTestCase');
class ProgramRegistrationTestCase extends AtlasTestCase {
	var $fixtures = array('app.program_registration', 'app.program', 'app.program_field');

	function startTest() {
		$this->ProgramRegistration =& ClassRegistry::init('ProgramRegistration');
	}

	function endTest() {
		unset($this->ProgramRegistration);
		ClassRegistry::flush();
	}

}
?>