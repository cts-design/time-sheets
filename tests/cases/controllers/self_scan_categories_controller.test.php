<?php
/* SelfScanCategories Test cases generated on: 2010-12-15 21:12:58 : 1292447938*/
App::import('Controller', 'SelfScanCategories');
App::import('Lib', 'AtlasTestCase');
class TestSelfScanCategoriesController extends SelfScanCategoriesController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class SelfScanCategoriesControllerTestCase extends AtlasTestCase {
	function startTest() {
		$this->SelfScanCategories =& new TestSelfScanCategoriesController();
		$this->SelfScanCategories->constructClasses();
	}

	function endTest() {
		unset($this->SelfScanCategories);
		ClassRegistry::flush();
	}

	function testAdminIndex() {

	}

	function testAdminView() {

	}

	function testAdminAdd() {

	}

	function testAdminEdit() {

	}

	function testAdminDelete() {

	}

}
?>