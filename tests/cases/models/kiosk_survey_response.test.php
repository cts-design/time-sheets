<?php
/* KioskSurveyResponse Test cases generated on: 2011-09-08 08:40:08 : 1315485608*/
App::import('Model', 'KioskSurveyResponse');
App::import('Lib', 'AtlasTestCase');
class KioskSurveyResponseTestCase extends AtlasTestCase {
	var $fixtures = array('app.kiosk_survey_response', 'app.kiosk_survey', 'app.kiosk_survey_question', 'app.kiosk', 'app.location', 'app.ftp_document_scanner', 'app.user', 'app.role', 'app.queued_document', 'app.document_queue_category', 'app.self_scan_category', 'app.document_filing_category', 'app.watched_filing_cat', 'app.program', 'app.program_field', 'app.program_response', 'app.program_response_doc', 'app.program_email', 'app.program_paper_form', 'app.program_instruction', 'app.self_sign_log', 'app.self_sign_log_archive', 'app.user_transaction', 'app.filed_document', 'app.kiosk_button', 'app.master_kiosk_button', 'app.kiosks_kiosk_survey', 'app.kiosk_survey_response_answer');

	function startTest() {
		$this->KioskSurveyResponse =& ClassRegistry::init('KioskSurveyResponse');
	}

	function endTest() {
		unset($this->KioskSurveyResponse);
		ClassRegistry::flush();
	}

}
?>