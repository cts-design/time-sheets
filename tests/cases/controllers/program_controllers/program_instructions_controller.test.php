<?php
/* ProgramInstructions Test cases generated on: 2011-08-11 13:27:14 : 1313083634*/
App::import('Controller', 'ProgramInstructions');
App::import('Lib', 'AtlasTestCase');
class TestProgramInstructionsController extends ProgramInstructionsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class ProgramInstructionsControllerTestCase extends AtlasTestCase {
	var $fixtures = array('app.program_instruction', 'app.program', 'app.program_field', 'app.program_response', 'app.user', 'app.role', 'app.location', 'app.ftp_document_scanner', 'app.kiosk', 'app.kiosk_button', 'app.master_kiosk_button', 'app.self_sign_log', 'app.self_sign_log_archive', 'app.queued_document', 'app.document_queue_category', 'app.self_scan_category', 'app.document_filing_category', 'app.watched_filing_cat', 'app.user_transaction', 'app.filed_document', 'app.program_response_doc', 'app.program_email', 'app.program_paper_form');

	function startTest() {
		$this->ProgramInstructions =& new TestProgramInstructionsController();
		$this->ProgramInstructions->constructClasses();
	}

	function endTest() {
		unset($this->ProgramInstructions);
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