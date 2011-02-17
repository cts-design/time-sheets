<?php
/* FtpDocumentScanners Test cases generated on: 2010-11-05 19:11:47 : 1288983647*/
App::import('Controller', 'FtpDocumentScanners');

class TestFtpDocumentScannersController extends FtpDocumentScannersController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class FtpDocumentScannersControllerTestCase extends CakeTestCase {
	var $fixtures = array(
            'app.aco',
            'app.aro',
            'app.aro_aco',
            'chairman_report',
            'deleted_document',
            'document_filing_category',
            'document_queue_category',
            'document_transaction',
            'filed_document',
            'ftp_document_scanner',
            'kiosk',
            'kiosk_button',
            'location',
            'master_kiosk_button',
            'navigation',
            'page',
            'press_release',
            'queued_document',
            'role',
            'self_scan_category',
            'self_sign_log',
            'self_sign_log_archive',
            'user',
            'user_transaction'
        );

	function startTest() {
		$this->FtpDocumentScanners =& new TestFtpDocumentScannersController();
		$this->FtpDocumentScanners->constructClasses();
	}

	function endTest() {
		unset($this->FtpDocumentScanners);
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