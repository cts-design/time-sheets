<?php
/* ProgramResponse Test cases generated on: 2011-03-28 21:10:45 : 1301346645*/
App::import('Model', 'ProgramResponse');
App::import('Lib', 'AtlasTestCase');
class ProgramResponseTestCase extends AtlasTestCase {
	
	function startTest() {
		$this->ProgramResponse =& ClassRegistry::init('ProgramResponse');
	}
	
	function endTest() {
		unset($this->ProgramResponse);
		ClassRegistry::flush();
	}

}
?>