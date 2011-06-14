<?php
/* Program Test cases generated on: 2011-03-29 12:33:26 : 1301402006*/
App::import('Model', 'Program');
App::import('Lib', 'AtlasTestCase');
class ProgramTestCase extends AtlasTestCase {
	var $fixtures = array('app.program', 'app.program_field', 'app.program_registration');

	function startTest() {
		$this->Program =& ClassRegistry::init('Program');
	}

	function endTest() {
		unset($this->Program);
		ClassRegistry::flush();
	}

}
?>