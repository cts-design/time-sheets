<?php
/* FiledDocument Test cases generated on: 2010-11-24 15:11:26 : 1290612386*/
App::import('Model', 'FiledDocument');

class FiledDocumentTestCase extends CakeTestCase {
		var $fixtures = array(
	    'app.aco',
	    'app.aro',
	    'app.aros_aco',
	    'chairman_report',
	    'deleted_document',
	    'document_filing_category',
	    'document_queue_category',
	    'document_transaction',
	    'featured_employer',
	    'filed_document',
	    'ftp_document_scanner',
	    'hot_job',
	    'kiosk',
	    'kiosk_button',
	    'location',
	    'master_kiosk_button',
	    'module_access_control',
	    'navigation',
	    'page',
	    'press_release',
	    'queued_document',
	    'rfp',
	    'role',
	    'self_scan_category',
	    'self_sign_log',
	    'self_sign_log_archive',
	    'user',
	    'user_transaction'
	);

	function startTest() {
		$this->FiledDocument =& ClassRegistry::init('FiledDocument');
	}

	function endTest() {
		unset($this->FiledDocument);
		ClassRegistry::flush();
	}
	
	function testValidation() {
		$this->FiledDocument->create();		
		$invalidData = array(
			'FiledDocument' => array(
				'id' => '',
				'filename' => '',
				'admin_id' => '',
				'user_id' => 12,
				'filed_location_id' => '',
				'cat_1' => 1,
				'cat_2' => 2,
				'cat_3'	=> 3,
				'entry_method' => 'Scan',
				'last_activity_admin' => 2
			)
		);			
		$this->FiledDocument->save($invalidData);
		$invalidFields = $this->FiledDocument->invalidFields();
		$this->assertEqual($invalidFields['filename'], 'Filename is required');
		$this->assertEqual($invalidFields['admin_id'], 'Admin Id required');
		$this->assertEqual($invalidFields['filed_location_id'], 'Filed location is required');
		$this->assertEqual($invalidFields['id'], 'Id is required');
	}
	
	function testBeforeDelete() {
		$data = array(
			'FiledDocument' => array(
				'last_activity_admin_id' => 1,
				'reason' => 'Duplicate Scan',
				'deleted_location_id' => 1
			)
		);
		$this->FiledDocument->set($data);
		$this->assertTrue($this->FiledDocument->delete(1));	
	}
}
?>