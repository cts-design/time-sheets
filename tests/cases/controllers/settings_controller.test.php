<?php
/* Settings Test cases generated on: 2011-11-01 17:02:42 : 1320181362*/
App::import('Controller', 'Settings');
App::import('Lib', 'AtlasTestCase');
class TestSettingsController extends SettingsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class SettingsControllerTestCase extends AtlasTestCase {
	var $fixtures = array('app.setting');

	function startTest() {
		$this->Settings =& new TestSettingsController();
		$this->Settings->constructClasses();
	}

	function endTest() {
		unset($this->Settings);
		ClassRegistry::flush();
	}

	function testAdminIndex() {

	}

	function testAdminAdd() {

	}

	function testAdminEdit() {

	}

	function testAdminDelete() {

	}

}
?>