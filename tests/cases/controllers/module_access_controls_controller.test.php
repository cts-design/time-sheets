<?php
/* ModuleAccessControls Test cases generated on: 2011-06-22 09:51:01 : 1308750661*/
App::import('Controller', 'ModuleAccessControls');
App::import('Lib', 'AtlasTestCase');
class TestModuleAccessControlsController extends ModuleAccessControlsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class ModuleAccessControlsControllerTestCase extends AtlasTestCase {
	var $fixtures = array('app.module_access_control');

	function startTest() {
		$this->ModuleAccessControls =& new TestModuleAccessControlsController();
		$this->ModuleAccessControls->constructClasses();
	}

	function endTest() {
		unset($this->ModuleAccessControls);
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