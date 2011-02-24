<?php
/* SelfSignLogs Test cases generated on: 2010-09-30 15:09:39 : 1285861959*/
App::import('Controller', 'SelfSignLogs');
App::import('Lib', 'AtlasTestCase');
class TestSelfSignLogsController extends SelfSignLogsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class SelfSignLogsControllerTestCase extends AtlasTestCase {
	function startTest() {
		$this->SelfSignLogs =& new TestSelfSignLogsController();
		$this->SelfSignLogs->constructClasses();
	}

	function endTest() {
		unset($this->SelfSignLogs);
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