<?php
/* PressRelease Test cases generated on: 2011-02-09 15:20:21 : 1297264821*/
App::import('Model', 'PressRelease');

class PressReleaseTestCase extends CakeTestCase {
	var $fixtures = array('app.press_release');

	function startTest() {
		$this->PressRelease =& ClassRegistry::init('PressRelease');
	}

	function endTest() {
		unset($this->PressRelease);
		ClassRegistry::flush();
	}

}
?>