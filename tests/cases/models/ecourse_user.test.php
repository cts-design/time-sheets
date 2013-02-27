<?php
/* EcourseUser Test cases generated on: 2013-02-18 11:49:34 : 1361206174*/
App::import('Model', 'EcourseUser');
App::import('Lib', 'AtlasTestCase');
class EcourseUserTestCase extends AtlasTestCase {
	var $fixtures = array('app.ecourse_user', 'app.ecourse', 'app.ecourse_module', 'app.ecourse_module_question', 'app.ecourse_module_question_answer', 'app.user', 'app.role', 'app.location', 'app.ftp_document_scanner', 'app.kiosk', 'app.kiosk_button', 'app.master_kiosk_button', 'app.self_sign_log', 'app.self_sign_log_archive', 'app.kiosk_survey', 'app.kiosk_survey_question', 'app.kiosk_survey_response', 'app.kiosk_survey_response_answer', 'app.kiosks_kiosk_survey', 'app.queued_document', 'app.document_queue_category', 'app.bar_code_definition', 'app.document_filing_category', 'app.watched_filing_cat', 'app.program', 'app.program_step', 'app.program_instruction', 'app.program_email', 'app.program_form_field', 'app.program_document', 'app.program_response', 'app.program_response_doc', 'app.filed_document', 'app.program_response_activity', 'app.self_scan_category', 'app.event', 'app.event_category', 'app.events', 'app.event_registration', 'app.user_transaction');

	function startTest() {
		$this->EcourseUser =& ClassRegistry::init('EcourseUser');
	}

	function endTest() {
		unset($this->EcourseUser);
		ClassRegistry::flush();
	}

}
?>