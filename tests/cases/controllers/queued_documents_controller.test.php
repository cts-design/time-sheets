<?php
/* QueuedDocuments Test cases generated on: 2010-11-08 13:11:02 : 1289221982*/
App::import('Controller', 'QueuedDocuments');
App::import('Lib', 'AtlasTestCase');
class TestQueuedDocumentsController extends QueuedDocumentsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class QueuedDocumentsControllerTestCase extends AtlasTestCase {
	function startTest() {
		$this->QueuedDocuments =& new TestQueuedDocumentsController();
		$this->QueuedDocuments->constructClasses();
	}

	function endTest() {
		unset($this->QueuedDocuments);
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