<?php
/* Setting Test cases generated on: 2011-11-01 17:01:04 : 1320181264*/
App::import('Model', 'Setting');
App::import('Lib', 'AtlasTestCase');
class SettingTestCase extends AtlasTestCase {
	var $fixtures = array('app.setting');

	function startTest() {
		$this->Setting =& ClassRegistry::init('Setting');
	}

	function endTest() {
		unset($this->Setting);
		ClassRegistry::flush();
	}

}
?>