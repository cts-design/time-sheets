<?php
/* Admins Test cases generated on: 2010-09-24 12:09:56 : 1285331696*/
App::import('Controller', 'Admins');

class TestAdminsController extends AdminsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class AdminsControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.admin');

	function startTest() {
		$this->Admins =& new TestAdminsController();
		$this->Admins->constructClasses();
	}

	function endTest() {
		unset($this->Admins);
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