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

    function testAdminUpdate() {
        // test turning on pages
        $formData = array(
            'module' => 'Pages',
            'state' => 0
        );

        $result = $this->testAction('/admin/module_access_controls/update', array('form_data' => $formData));
        $this->assertTrue($result['data']['success']);

        $check = $this->ModuleAccessControls->ModuleAccessControl->findByName('Pages');
        $this->assertEqual($check['ModuleAccessControl']['permission'], 0);

        // reset pages to disabled
        $formData = array(
            'module' => 'Pages',
            'state' => 1
        );

        $result = $this->testAction('/admin/module_access_controls/update', array('form_data' => $formData));
        $this->assertTrue($result['data']['success']);

        $check = $this->ModuleAccessControls->ModuleAccessControl->findByName('Pages');
        $this->assertEqual($check['ModuleAccessControl']['permission'], 1);

        // test that the Programs module doesn't exist
        $module = $this->ModuleAccessControls->ModuleAccessControl->findByName('Programs');
        $this->assertFalse($module);

        // test turning off programs
        $formData = array(
            'module' => 'Programs',
            'state' => 1
        );

        $result = $this->testAction('/admin/module_access_controls/update', array('form_data' => $formData));
        $this->assertTrue($result['data']['success']);
        $check = $this->ModuleAccessControls->ModuleAccessControl->findByName('Programs');
        $this->assertEqual($check['ModuleAccessControl']['permission'], 1);
    }
}
?>
