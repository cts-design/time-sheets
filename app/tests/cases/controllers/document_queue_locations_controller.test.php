<?php
/* DocumentQueueLocations Test cases generated on: 2010-10-19 17:10:54 : 1287509814*/
App::import('Controller', 'DocumentQueueLocations');

class TestDocumentQueueLocationsController extends DocumentQueueLocationsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class DocumentQueueLocationsControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.document_queue_location');

	function startTest() {
		$this->DocumentQueueLocations =& new TestDocumentQueueLocationsController();
		$this->DocumentQueueLocations->constructClasses();
	}

	function endTest() {
		unset($this->DocumentQueueLocations);
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