<?php
/* BarCodeDefinitions Test cases generated on: 2011-10-04 11:42:46 : 1317742966*/
App::import('Controller', 'BarCodeDefinitions');
App::import('Lib', 'AtlasTestCase');
class TestBarCodeDefinitionsController extends BarCodeDefinitionsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class BarCodeDefinitionsControllerTestCase extends AtlasTestCase {
	var $fixtures = array('app.bar_code_definition');

	function startTest() {
		$this->BarCodeDefinitions =& new TestBarCodeDefinitionsController();
		$this->BarCodeDefinitions->constructClasses();
	}

	function endTest() {
		unset($this->BarCodeDefinitions);
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