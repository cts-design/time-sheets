<?php
/* Program Test cases generated on: 2011-03-23 15:16:23 : 1300893383*/
App::import('Model', 'Program');
App::import('Lib', 'AtlasTestCase');
class ProgramTestCase extends AtlasTestCase {
	var $fixtures = array('app.program');

	function startTest() {
		$this->Program =& ClassRegistry::init('Program');
	}

	function endTest() {
		unset($this->Program);
		ClassRegistry::flush();
	}

}
?>