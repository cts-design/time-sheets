<?php
/* ProgramResponse Test cases generated on: 2011-03-28 21:10:45 : 1301346645*/
App::import('Model', 'ProgramResponse');
App::import('Lib', 'AtlasTestCase');
class ProgramResponseTestCase extends AtlasTestCase {
	var $fixtures = array('app.program_response', 'app.program', 'app.program_field');

	function startTest() {
		$this->ProgramResponse =& ClassRegistry::init('ProgramResponse');
	}

	function endTest() {
		unset($this->ProgramResponse);
		ClassRegistry::flush();
	}

}
?>