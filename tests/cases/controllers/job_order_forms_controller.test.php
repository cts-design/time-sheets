<?php
/* JobOrderForms Test cases generated on: 2012-01-25 11:20:14 : 1327508414*/
App::import('Controller', 'JobOrderForms');
App::import('Lib', 'AtlasTestCase');
class TestJobOrderFormsController extends JobOrderFormsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class JobOrderFormsControllerTestCase extends AtlasTestCase {
	var $fixtures = array('app.job_order_form');

	function startTest() {
		$this->JobOrderForms =& new TestJobOrderFormsController();
		$this->JobOrderForms->constructClasses();
	}

	function endTest() {
		unset($this->JobOrderForms);
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