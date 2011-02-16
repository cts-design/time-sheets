<?php
/* DocumentFilingLocations Test cases generated on: 2010-10-19 17:10:22 : 1287509782*/
App::import('Controller', 'DocumentFilingLocations');

class TestDocumentFilingLocationsController extends DocumentFilingLocationsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class DocumentFilingLocationsControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.document_filing_location');

	function startTest() {
		$this->DocumentFilingLocations =& new TestDocumentFilingLocationsController();
		$this->DocumentFilingLocations->constructClasses();
	}

	function endTest() {
		unset($this->DocumentFilingLocations);
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