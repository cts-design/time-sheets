<?php
/* FormFields Test cases generated on: 2011-03-17 14:48:40 : 1300373320*/
App::import('Controller', 'FormFields');
App::import('Lib', 'AtlasTestCase');
class TestFormFieldsController extends FormFieldsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class FormFieldsControllerTestCase extends AtlasTestCase {
	var $fixtures = array('app.form_field');

	function startTest() {
		$this->FormFields =& new TestFormFieldsController();
		$this->FormFields->constructClasses();
	}

	function endTest() {
		unset($this->FormFields);
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