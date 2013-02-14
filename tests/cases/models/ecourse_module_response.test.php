<?php
/* EcourseModuleResponse Test cases generated on: 2013-02-11 10:15:14 : 1360595714*/
App::import('Model', 'EcourseModuleResponse');
App::import('Lib', 'AtlasTestCase');
class EcourseModuleResponseTestCase extends AtlasTestCase {
	var $fixtures = array('app.ecourse_module_response', 'app.ecourse_response', 'app.ecourse', 'app.user', 'app.role', 'app.location', 'app.ftp_document_scanner', 'app.kiosk', 'app.kiosk_button', 'app.master_kiosk_button', 'app.self_sign_log', 'app.self_sign_log_archive', 'app.kiosk_survey', 'app.kiosk_survey_question', 'app.kiosk_survey_response', 'app.kiosk_survey_response_answer', 'app.kiosks_kiosk_survey', 'app.queued_document', 'app.document_queue_category', 'app.bar_code_definition', 'app.document_filing_category', 'app.watched_filing_cat', 'app.program', 'app.program_step', 'app.program_instruction', 'app.program_email', 'app.program_form_field', 'app.program_document', 'app.program_response', 'app.program_response_doc', 'app.filed_document', 'app.program_response_activity', 'app.self_scan_category', 'app.event', 'app.event_category', 'app.events', 'app.event_registration', 'app.user_transaction', 'app.ecourse_module_response_time');

	function startTest() {
		$this->EcourseModuleResponse =& ClassRegistry::init('EcourseModuleResponse');
	}

	function endTest() {
		unset($this->EcourseModuleResponse);
		ClassRegistry::flush();
	}

}
?>