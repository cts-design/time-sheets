<?php
/* KioskButtons Test cases generated on: 2010-09-27 19:09:52 : 1285615792*/
App::import('Controller', 'KioskButtons');
App::import('Lib', 'AtlasTestCase');
class TestKioskButtonsController extends KioskButtonsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class KioskButtonsControllerTestCase extends AtlasTestCase {
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
		$this->KioskButtons =& new TestKioskButtonsController();
		$this->KioskButtons->constructClasses();
	}

	function endTest() {
		unset($this->KioskButtons);
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