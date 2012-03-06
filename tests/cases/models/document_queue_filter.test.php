<?php
/* DocumentQueueFilter Test cases generated on: 2012-01-18 15:22:26 : 1326918146*/
App::import('Model', 'DocumentQueueFilter');
App::import('Lib', 'AtlasTestCase');
class DocumentQueueFilterTestCase extends AtlasTestCase {
	var $fixtures = array('app.document_queue_filter', 'app.user', 'app.role', 'app.location', 'app.ftp_document_scanner', 'app.kiosk', 'app.kiosk_button', 'app.master_kiosk_button', 'app.self_sign_log', 'app.self_sign_log_archive', 'app.kiosk_survey', 'app.kiosk_survey_question', 'app.kiosk_survey_response', 'app.kiosk_survey_response_answer', 'app.kiosks_kiosk_survey', 'app.queued_document', 'app.document_queue_category', 'app.bar_code_definition', 'app.document_filing_category', 'app.watched_filing_cat', 'app.program', 'app.program_field', 'app.program_response', 'app.program_response_doc', 'app.program_email', 'app.program_paper_form', 'app.program_instruction', 'app.self_scan_category', 'app.user_transaction', 'app.filed_document');

	function startTest() {
		$this->DocumentQueueFilter =& ClassRegistry::init('DocumentQueueFilter');
	}

	function endTest() {
		unset($this->DocumentQueueFilter);
		ClassRegistry::flush();
	}

}
?>