<?php
/* ChairmanReports Test cases generated on: 2011-02-09 18:14:25 : 1297275265*/
App::import('Controller', 'ChairmanReports');

class TestChairmanReportsController extends ChairmanReportsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class ChairmanReportsControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.chairman_report');

	function startTest() {
		$this->ChairmanReports =& new TestChairmanReportsController();
		$this->ChairmanReports->constructClasses();
	}

	function endTest() {
		unset($this->ChairmanReports);
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