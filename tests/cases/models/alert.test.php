<?php
/* Alert Test cases generated on: 2011-12-01 10:45:06 : 1322754306*/
App::import('Model', 'Alert');
App::import('Lib', 'AtlasTestCase');
class AlertTestCase extends AtlasTestCase {
	var $fixtures = array('app.alert', 'app.user', 'app.role', 'app.location', 'app.ftp_document_scanner', 'app.kiosk', 'app.kiosk_button', 'app.master_kiosk_button', 'app.self_sign_log', 'app.self_sign_log_archive', 'app.kiosk_survey', 'app.kiosk_survey_question', 'app.kiosk_survey_response', 'app.kiosk_survey_response_answer', 'app.kiosks_kiosk_survey', 'app.queued_document', 'app.document_queue_category', 'app.bar_code_definition', 'app.document_filing_category', 'app.watched_filing_cat', 'app.program', 'app.program_field', 'app.program_response', 'app.program_response_doc', 'app.program_email', 'app.program_paper_form', 'app.program_instruction', 'app.self_scan_category', 'app.user_transaction', 'app.filed_document');

	function startTest() {
		$this->Alert =& ClassRegistry::init('Alert');
	}

	function endTest() {
		unset($this->Alert);
		ClassRegistry::flush();
	}

}
?>