<?php
/* Alerts Test cases generated on: 2011-12-06 13:29:13 : 1323196153*/
App::import('Controller', 'Alerts');
App::import('Lib', 'AtlasTestCase');
class TestAlertsController extends AlertsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class AlertsControllerTestCase extends AtlasTestCase {
	var $fixtures = array('app.alert', 'app.user', 'app.role', 'app.location', 'app.ftp_document_scanner', 'app.kiosk', 'app.kiosk_button', 'app.master_kiosk_button', 'app.self_sign_log', 'app.self_sign_log_archive', 'app.kiosk_survey', 'app.kiosk_survey_question', 'app.kiosk_survey_response', 'app.kiosk_survey_response_answer', 'app.kiosks_kiosk_survey', 'app.queued_document', 'app.document_queue_category', 'app.bar_code_definition', 'app.document_filing_category', 'app.watched_filing_cat', 'app.program', 'app.program_field', 'app.program_response', 'app.program_response_doc', 'app.program_email', 'app.program_paper_form', 'app.program_instruction', 'app.self_scan_category', 'app.user_transaction', 'app.filed_document');

	function startTest() {
		$this->Alerts =& new TestAlertsController();
		$this->Alerts->constructClasses();
	}

	function endTest() {
		unset($this->Alerts);
		ClassRegistry::flush();
	}

	function testAdminIndex() {

	}

	function testAdminAdd() {

	}

	function testAdminEdit() {

	}

	function testAdminDelete() {

	}

}
?>