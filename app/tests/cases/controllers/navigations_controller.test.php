<?php
/* Navigations Test cases generated on: 2011-02-04 19:52:09 : 1296849129*/
App::import('Controller', 'Navigations');

class TestNavigationsController extends NavigationsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class NavigationsControllerTestCase extends CakeTestCase {
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
		$this->Navigations =& new TestNavigationsController();
		$this->Navigations->constructClasses();
	}

	function endTest() {
		unset($this->Navigations);
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