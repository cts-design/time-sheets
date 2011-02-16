<?php
/* PressReleases Test cases generated on: 2011-02-09 15:21:32 : 1297264892*/
App::import('Controller', 'PressReleases');

class TestPressReleasesController extends PressReleasesController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class PressReleasesControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.press_release');

	function startTest() {
		$this->PressReleases =& new TestPressReleasesController();
		$this->PressReleases->constructClasses();
	}

	function endTest() {
		unset($this->PressReleases);
		ClassRegistry::flush();
	}

	function testIndex() {

	}

	function testView() {

	}

	function testAdd() {

	}

	function testEdit() {

	}

	function testDelete() {

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