<?php
/* ProgramField Test cases generated on: 2011-04-05 09:31:16 : 1302010276*/
App::import('Model', 'ProgramField');
App::import('Lib', 'AtlasTestCase');
class ProgramFieldTestCase extends AtlasTestCase {
	var $fixtures = array('app.program_field');

	function startTest() {
		$this->ProgramField =& ClassRegistry::init('ProgramField');
	}

	function endTest() {
		unset($this->ProgramField);
		ClassRegistry::flush();
	}

}
?>