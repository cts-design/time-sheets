<?php
App::import('Model', 'ModuleAccessControl');
App::import('Lib', 'AtlasTestCase');
class ModuleAccessControlTestCase extends AtlasTestCase {
	var $fixtures = array('app.module_access_control');

	function startTest() {
		$this->ModuleAccessControl =& ClassRegistry::init('ModuleAccessControl');
        $this->ModuleAccessControl->create();
	}

	function endTest() {
		unset($this->ModuleAccessControl);
		ClassRegistry::flush();
	}
	
	function testCheckPermissionFunction() {
		$this->assertFalse($this->ModuleAccessControl->checkPermissions('ChairmanReports'));
		$this->assertFalse($this->ModuleAccessControl->checkPermissions('Controller that doesnt have a record'));
		$this->assertTrue($this->ModuleAccessControl->checkPermissions('Pages'));
	}
}
?>