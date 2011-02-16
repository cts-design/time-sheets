<?php
/* DeletedDocuments Test cases generated on: 2010-12-02 19:12:31 : 1291319071*/
App::import('Controller', 'DeletedDocuments');

class TestDeletedDocumentsController extends DeletedDocumentsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class DeletedDocumentsControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.deleted_document', 'app.user', 'app.role', 'app.location', 'app.ftp_document_scanner', 'app.kiosk', 'app.kiosk_button', 'app.master_kiosk_button', 'app.self_sign_log', 'app.self_sign_log_archive', 'app.queued_document', 'app.document_queue_category', 'app.user_transaction', 'app.filed_document', 'app.document_filing_category');

	function startTest() {
		$this->DeletedDocuments =& new TestDeletedDocumentsController();
		$this->DeletedDocuments->constructClasses();
	}

	function endTest() {
		unset($this->DeletedDocuments);
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