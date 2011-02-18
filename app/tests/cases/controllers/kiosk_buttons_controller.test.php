<?php
/* KioskButtons Test cases generated on: 2010-09-27 19:09:52 : 1285615792*/
App::import('Controller', 'KioskButtons');
App::import('Lib', 'AtlasTestCase');
class TestKioskButtonsController extends KioskButtonsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class KioskButtonsControllerTestCase extends AtlasTestCase {
	function startTest() {
		$this->KioskButtons =& new TestKioskButtonsController();
		$this->KioskButtons->constructClasses();
	}

	function endTest() {
		unset($this->KioskButtons);
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