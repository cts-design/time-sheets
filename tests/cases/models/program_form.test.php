<?php
/* ProgramForm Test cases generated on: 2012-04-17 13:39:41 : 1334684381*/
App::import('Model', 'ProgramForm');

class ProgramFormTestCase extends CakeTestCase {
	var $fixtures = array('app.program_form', 'app.program_step', 'app.program', 'app.program_response', 'app.user', 'app.role', 'app.location', 'app.ftp_document_scanner', 'app.kiosk', 'app.kiosk_button', 'app.master_kiosk_button', 'app.self_sign_log', 'app.self_sign_log_archive', 'app.kiosk_survey', 'app.kiosk_survey_question', 'app.kiosk_survey_response', 'app.kiosk_survey_response_answer', 'app.kiosks_kiosk_survey', 'app.queued_document', 'app.document_queue_category', 'app.bar_code_definition', 'app.document_filing_category', 'app.watched_filing_cat', 'app.self_scan_category', 'app.user_transaction', 'app.filed_document', 'app.program_response_doc', 'app.program_response_activity', 'app.program_email', 'app.program_paper_form', 'app.program_instruction');

	function startTest() {
		$this->ProgramForm =& ClassRegistry::init('ProgramForm');
	}

	function endTest() {
		unset($this->ProgramForm);
		ClassRegistry::flush();
	}

}
