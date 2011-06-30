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
	function startTest() {
		$this->ModuleAccessControls =& new TestModuleAccessControlsController();
		$this->ModuleAccessControls->constructClasses();
        $this->ModuleAccessControls->params['controller'] = 'module_access_controls';
        $this->ModuleAccessControls->params['pass'] = array();
        $this->ModuleAccessControls->params['named'] = array();
        $this->testController = $this->ModuleAccessControls;
	}

	function endTest() {
        $this->ModuleAccessControls->Session->destroy();
		unset($this->ModuleAccessControls);
		ClassRegistry::flush();
	}
}
?>
