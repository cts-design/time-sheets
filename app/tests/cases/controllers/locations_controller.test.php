<?php
/* Locations Test cases generated on: 2010-11-05 19:11:25 : 1288983745*/
App::import('Controller', 'Locations');

class TestLocationsController extends LocationsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class LocationsControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.location', 'app.ftp_document_scanner');

	function startTest() {
		$this->Locations =& new TestLocationsController();
		$this->Locations->constructClasses();
	}

	function endTest() {
		unset($this->Locations);
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