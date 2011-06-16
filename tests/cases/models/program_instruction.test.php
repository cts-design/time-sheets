<?php
/* ProgramInstruction Test cases generated on: 2011-05-10 13:53:30 : 1305050010*/
App::import('Model', 'ProgramInstruction');

class ProgramInstructionTestCase extends CakeTestCase {
	var $fixtures = array('app.program_instruction', 'app.program', 'app.program_field', 'app.program_response', 'app.user', 'app.role', 'app.location', 'app.ftp_document_scanner', 'app.kiosk', 'app.kiosk_button', 'app.master_kiosk_button', 'app.self_sign_log', 'app.self_sign_log_archive', 'app.queued_document', 'app.document_queue_category', 'app.self_scan_category', 'app.document_filing_category', 'app.watched_filing_cat', 'app.user_transaction', 'app.filed_document', 'app.program_response_doc', 'app.program_email', 'app.program_paper_form');

	function startTest() {
		$this->ProgramInstruction =& ClassRegistry::init('ProgramInstruction');
	}

	function endTest() {
		unset($this->ProgramInstruction);
		ClassRegistry::flush();
	}

}
?>