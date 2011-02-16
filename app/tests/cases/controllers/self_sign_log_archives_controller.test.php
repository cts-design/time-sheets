<?php
/* SelfSignLogArchives Test cases generated on: 2010-10-29 12:10:24 : 1288355004*/
App::import('Controller', 'SelfSignLogArchives');

class TestSelfSignLogArchivesController extends SelfSignLogArchivesController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class SelfSignLogArchivesControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.self_sign_log_archive', 'app.user', 'app.self_sign_log', 'app.kiosk', 'app.kiosk_button', 'app.user_transaction');

	function startTest() {
		$this->SelfSignLogArchives =& new TestSelfSignLogArchivesController();
		$this->SelfSignLogArchives->constructClasses();
	}

	function endTest() {
		unset($this->SelfSignLogArchives);
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