<?php
/* Navigations Test cases generated on: 2011-02-04 19:52:09 : 1296849129*/
App::import('Controller', 'Navigations');
App::import('Lib', 'AtlasTestCase');
class TestNavigationsController extends NavigationsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class NavigationsControllerTestCase extends AtlasTestCase {
	function startTest() {
		$this->Navigations =& new TestNavigationsController();
		$this->Navigations->constructClasses();
	}

	function endTest() {
		unset($this->Navigations);
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