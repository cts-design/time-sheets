<?php
/* Rfps Test cases generated on: 2011-02-28 17:48:26 : 1298915306*/
App::import('Controller', 'Rfps');
App::import('Lib', 'AtlasTestCase');
class TestRfpsController extends RfpsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class RfpsControllerTestCase extends AtlasTestCase {
	var $fixtures = array('app.rfp');

	function startTest() {
		$this->Rfps =& new TestRfpsController();
		$this->Rfps->constructClasses();
	}

	function endTest() {
		unset($this->Rfps);
		ClassRegistry::flush();
	}

	function testIndex() {

	}

	function testAdd() {

	}

	function testEdit() {

	}

	function testDelete() {

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