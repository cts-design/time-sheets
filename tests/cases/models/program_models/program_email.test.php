<?php
/* ProgramEmail Test cases generated on: 2011-04-04 14:35:21 : 1301927721*/
App::import('Model', 'ProgramEmail');

class ProgramEmailTestCase extends CakeTestCase {
	var $fixtures = array('app.program_email', 'app.program', 'app.program_field', 'app.program_response');

	function startTest() {
		$this->ProgramEmail =& ClassRegistry::init('ProgramEmail');
	}

	function endTest() {
		unset($this->ProgramEmail);
		ClassRegistry::flush();
	}

}
?>