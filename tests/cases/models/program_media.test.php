<?php
/* ProgramMedia Test cases generated on: 2012-04-10 10:42:35 : 1334068955*/
App::import('Model', 'ProgramMedia');
App::import('Lib', 'AtlasTestCase');
class ProgramMediaTestCase extends AtlasTestCase {
	var $fixtures = array('app.program_media');

	function startTest() {
		$this->ProgramMedia =& ClassRegistry::init('ProgramMedia');
	}

	function endTest() {
		unset($this->ProgramMedia);
		ClassRegistry::flush();
	}

}
?>