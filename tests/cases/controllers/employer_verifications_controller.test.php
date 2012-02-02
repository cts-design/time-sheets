<?php
/* EmployerVerifications Test cases generated on: 2012-02-01 09:24:27 : 1328106267*/
App::import('Controller', 'EmployerVerifications');
App::import('Lib', 'AtlasTestCase');
class TestEmployerVerificationsController extends EmployerVerificationsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class EmployerVerificationsControllerTestCase extends AtlasTestCase {
	var $fixtures = array('app.employer_verification');

	function startTest() {
		$this->EmployerVerifications =& new TestEmployerVerificationsController();
		$this->EmployerVerifications->constructClasses();
	}

	function endTest() {
		unset($this->EmployerVerifications);
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