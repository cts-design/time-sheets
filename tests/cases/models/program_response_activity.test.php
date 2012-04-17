<?php
/* ProgramResponseActivity Test cases generated on: 2012-04-13 11:17:10 : 1334330230*/
App::import('Model', 'ProgramResponseActivity');
App::import('Lib', 'AtlasTestCase');
class ProgramResponseActivityTestCase extends AtlasTestCase {
	var $fixtures = array('app.program_response_activity', 'app.program_response', 'app.program', 'app.program_step', 'app.program_form', 'app.program_email', 'app.program_paper_form', 'app.watched_filing_cat', 'app.document_filing_category', 'app.bar_code_definition', 'app.document_queue_category', 'app.queued_document', 'app.location', 'app.ftp_document_scanner', 'app.user', 'app.role', 'app.self_sign_log', 'app.kiosk', 'app.kiosk_button', 'app.master_kiosk_button', 'app.self_sign_log_archive', 'app.kiosk_survey', 'app.kiosk_survey_question', 'app.kiosk_survey_response', 'app.kiosk_survey_response_answer', 'app.kiosks_kiosk_survey', 'app.user_transaction', 'app.filed_document', 'app.self_scan_category', 'app.program_instruction', 'app.program_response_doc');

	function startTest() {
		$this->ProgramResponseActivity =& ClassRegistry::init('ProgramResponseActivity');
	}

	function endTest() {
		unset($this->ProgramResponseActivity);
		ClassRegistry::flush();
	}

}
?>