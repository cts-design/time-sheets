<?php
/* KioskButtons Test cases generated on: 2010-09-27 19:09:52 : 1285615792*/
App::import('Controller', 'KioskButtons');

class TestKioskButtonsController extends KioskButtonsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class KioskButtonsControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.kiosk_button', 'app.kiosk');

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