<?php
/* ProgramPaperForm Test cases generated on: 2011-04-21 19:27:45 : 1303414065*/
App::import('Model', 'ProgramPaperForm');

class ProgramPaperFormTestCase extends CakeTestCase {
	var $fixtures = array('app.program_paper_form', 'app.program', 'app.program_field', 'app.program_response', 'app.user', 'app.role', 'app.location', 'app.ftp_document_scanner', 'app.kiosk', 'app.kiosk_button', 'app.master_kiosk_button', 'app.self_sign_log', 'app.self_sign_log_archive', 'app.queued_document', 'app.document_queue_category', 'app.self_scan_category', 'app.document_filing_category', 'app.watched_filing_cat', 'app.user_transaction', 'app.filed_document', 'app.program_response_doc', 'app.program_email');

	function startTest() {
		$this->ProgramPaperForm =& ClassRegistry::init('ProgramPaperForm');
	}

	function endTest() {
		unset($this->ProgramPaperForm);
		ClassRegistry::flush();
	}

}
?>