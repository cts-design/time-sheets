<?php
/* SelfSignLogArchives Test cases generated on: 2010-10-29 12:10:24 : 1288355004*/
App::import('Controller', 'SelfSignLogArchives');
App::import('Lib', 'AtlasTestCase');
class TestSelfSignLogArchivesController extends SelfSignLogArchivesController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class SelfSignLogArchivesControllerTestCase extends AtlasTestCase {
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
		$this->SelfSignLogArchives =& new TestSelfSignLogArchivesController();
		$this->SelfSignLogArchives->constructClasses();
	}

	function endTest() {
		unset($this->SelfSignLogArchives);
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