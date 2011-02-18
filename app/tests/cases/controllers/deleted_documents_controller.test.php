<?php
/* DeletedDocuments Test cases generated on: 2010-12-02 19:12:31 : 1291319071*/
App::import('Controller', 'DeletedDocuments');
App::import('Lib', 'AtlasTestCase');
class TestDeletedDocumentsController extends DeletedDocumentsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class DeletedDocumentsControllerTestCase extends AtlasTestCase {
	var $globalExpected;
	var $fixtures = array(
            'app.aco',
            'app.aro',
            'app.aro_aco',
            'chairman_report',
            'deleted_document',
            'document_filing_category',
            'document_queue_category',
            'document_transaction',
            'filed_document',
            'ftp_document_scanner',
            'kiosk',
            'kiosk_button',
            'location',
            'master_kiosk_button',
            'module_access_control',
            'navigation',
            'page',
            'press_release',
            'queued_document',
            'role',
            'self_scan_category',
            'self_sign_log',
            'self_sign_log_archive',
            'user',
            'user_transaction'
        );

	function startTest() {
		$this->DeletedDocuments =& new TestDeletedDocumentsController();
		$this->DeletedDocuments->constructClasses();
		
		$this->globalExpected = array(
			'deletedDocuments' => array(
				'0' => array(
					'DeletedDocument' => array(
						'id' => 1,
						'filename' => '201102181820401535034.pdf',
                        'queue_category_id' => '',
                        'admin_id' => 1,
                        'user_id' => 1,
                        'scanned_location_id' => '',
                        'filed_location_id' => 1,
                        'deleted_location_id' => 1,
                        'cat_1' => 1,
                        'cat_2' => 2,
                        'cat_3' => '',
                        'description' => '', 
                        'entry_method' => 'Upload',
                        'deleted_reason' => 'Duplicate Scan',
                        'last_activity_admin_id' => 2,
                        'created' => '2011-02-18 19:41:59',
                        'modified' => '2011-02-18 19:41:59',
					),
					'Admin' => array(
				        'id' => 1,
                        'role_id' => 2,
                        'firstname' => 'brandon',
                        'lastname' => 'cordell',
                        'middle_initial' => 'D',
                        'ssn' => '222222222',
                        'username' => 'brandoncordell',
                        'password' => 'asd123',
                        'address_1' => '123 main st',
                        'address_2' => '',
                        'city' => 'spring hill',
                        'state' => 'fl',
                        'zip' => '34609',
                        'phone' => '3525551234',
                        'alt_phone' => '',
                        'gender' => 'Male',
                        'dob' => '2010-09-22',
                        'email' => 'brandonc@ctsfla.com',
                        'status' => 1,
                        'deleted' => 0,
                        'signature' => 1,
                        'location_id' => 1,
                        'signature_created' => '2010-09-22 15:02:21',
                        'signature_modified' => '2010-09-22 15:02:21',
                        'created' => '2010-09-22 15:02:21',
                        'modified' => '2010-09-22 15:02:21'
					),
					'User' => Array(
                        'id' => 1,
                        'role_id' => 2,
                        'firstname' => 'brandon',
                        'lastname' => 'cordell',
                        'middle_initial' => 'D',
                        'ssn' => '222222222',
                        'username' => 'brandoncordell',
                        'password' => 'asd123',
                        'address_1' => '123 main st',
                        'address_2' => '',
                        'city' => 'spring hill',
                        'state' => 'fl',
                        'zip' => '34609',
                        'phone' => '3525551234',
                        'alt_phone' => '',
                        'gender' => 'Male',
                        'dob' => '09/22/2010',
                        'email' => 'brandonc@ctsfla.com',
                        'status' => 1,
                        'deleted' => 0,
                        'signature' => 1,
                        'location_id' => 1,
                        'signature_created' => '2010-09-22 15:02:21',
                        'signature_modified' => '2010-09-22 15:02:21',
                        'created' => '09/22/2010 - 03:02 pm',
                        'modified' => '09/22/2010 - 03:02 pm'
                    )
				)
			)
		);
	}

	function endTest() {
		unset($this->DeletedDocuments);
		ClassRegistry::flush();
	}

	function testAdminIndexNoDatesPassed() {
		$results = $this->testAction('/admin/deleted_documents', array('return' => 'vars'));
		$document_results = $results['deletedDocuments']['0']['DeletedDocument'];
		$this->globalExpected['deletedDocuments']['0']['DeletedDocument']['id'] = $document_results['id'];
		$this->globalExpected['deletedDocuments']['0']['DeletedDocument']['modified'] = $document_results['modified'];
		$this->assertEqual($document_results, $this->globalExpected['deletedDocuments']['0']['DeletedDocument']);
	}

	function testAdminIndexDeletedToday() {
		$results = $this->testAction('/admin/deleted_documents/index/today', array('return' => 'vars'));
		$document_results = $results['deletedDocuments']['0']['DeletedDocument'];
		$this->globalExpected['deletedDocuments']['0']['DeletedDocument']['id'] = $document_results['id'];
		$this->globalExpected['deletedDocuments']['0']['DeletedDocument']['modified'] = $document_results['modified'];
		$this->assertEqual($document_results, $this->globalExpected['deletedDocuments']['0']['DeletedDocument']);
	}

	function testAdminIndexDeletedYesterday() {
		$results = $this->testAction('/admin/deleted_documents/index/yesterday', array('return' => 'vars'));
		$document_results = $results['deletedDocuments']['0']['DeletedDocument'];
		$this->globalExpected['deletedDocuments']['0']['DeletedDocument']['id'] = $document_results['id'];
		$this->globalExpected['deletedDocuments']['0']['DeletedDocument']['modified'] = $document_results['modified'];
		$this->assertEqual($document_results, $this->globalExpected['deletedDocuments']['0']['DeletedDocument']);
	}

	function testAdminIndexDeletedLastSevenDays() {
		$results = $this->testAction('/admin/deleted_documents/index/last_7', array('return' => 'vars'));
		$document_results = $results['deletedDocuments']['0']['DeletedDocument'];
		$this->globalExpected['deletedDocuments']['0']['DeletedDocument']['id'] = $document_results['id'];
		$this->globalExpected['deletedDocuments']['0']['DeletedDocument']['modified'] = $document_results['modified'];
		$this->assertEqual($document_results, $this->globalExpected['deletedDocuments']['0']['DeletedDocument']);	
	}

	function testAdminView() {
		// @TODO research
		$this->assertTrue(true);
	}
	
	function testAdminRestoreNoId() {
		// @TODO research
		$this->assertTrue(true);
	}

	function testAdminRestore() {
		// @TODO research
		$this->assertTrue(true);
	}
}
?>