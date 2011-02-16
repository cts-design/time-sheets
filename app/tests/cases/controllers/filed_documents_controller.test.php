<?php
/* FiledDocuments Test cases generated on: 2010-11-24 15:11:59 : 1290612419*/
App::import('Controller', 'FiledDocuments');

class TestFiledDocumentsController extends FiledDocumentsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class FiledDocumentsControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.filed_document', 'app.user', 'app.role', 'app.location', 'app.ftp_document_scanner', 'app.kiosk', 'app.kiosk_button', 'app.master_kiosk_button', 'app.self_sign_log', 'app.self_sign_log_archive', 'app.queued_document', 'app.document_queue_category', 'app.user_transaction');

	function startTest() {
		$this->FiledDocuments =& new TestFiledDocumentsController();
		$this->FiledDocuments->constructClasses();
	}

	function endTest() {
		unset($this->FiledDocuments);
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