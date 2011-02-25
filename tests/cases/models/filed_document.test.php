<?php
/* FiledDocument Test cases generated on: 2010-11-24 15:11:26 : 1290612386*/
App::import('Model', 'FiledDocument');

class FiledDocumentTestCase extends CakeTestCase {
	var $fixtures = array('app.filed_document', 'app.user', 'app.role', 'app.location', 'app.ftp_document_scanner', 'app.kiosk', 'app.kiosk_button', 'app.master_kiosk_button', 'app.self_sign_log', 'app.self_sign_log_archive', 'app.queued_document', 'app.document_queue_category', 'app.user_transaction');

	function startTest() {
		$this->FiledDocument =& ClassRegistry::init('FiledDocument');
	}

	function endTest() {
		unset($this->FiledDocument);
		ClassRegistry::flush();
	}

}
?>