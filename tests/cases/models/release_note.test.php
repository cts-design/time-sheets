<?php
/* ReleaseNote Test cases generated on: 2011-06-21 15:13:59 : 1308683639*/
App::import('Model', 'ReleaseNote');
App::import('Lib', 'AtlasTestCase');
class ReleaseNoteTestCase extends AtlasTestCase {
	var $fixtures = array('app.release_note');

	function startTest() {
		$this->ReleaseNote =& ClassRegistry::init('ReleaseNote');
	}

	function endTest() {
		unset($this->ReleaseNote);
		ClassRegistry::flush();
	}

}
?>