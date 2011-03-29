<?php
/* ProgramRegistrations Test cases generated on: 2011-03-28 21:10:55 : 1301346655*/
App::Import('Controller', 'ProgramRegistrations');

class TestProgramRegistrationsController extends ProgramRegistrationsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class ProgramRegistrationsControllerTestCase extends CakeTestCase {
	var $fixtures = array(
            'app.aco',
            'app.aro',
            'app.aro_aco',
            'app.chairman_report',
            'app.deleted_document',
            'app.document_filing_category',
            'app.document_queue_category',
            'app.document_transaction',
            'app.filed_document',
            'app.ftp_document_scanner',
            'app.kiosk',
            'app.kiosk_button',
            'app.location',
            'app.master_kiosk_button',
            'app.module_access_control',
            'app.navigation',
            'app.page',
            'app.press_release',
            'app.program',
            'app.program_registration',
            'app.program_field',
            'app.queued_document',
            'app.role',
            'app.self_scan_category',
            'app.self_sign_log',
            'app.self_sign_log_archive',
            'app.user',
            'app.user_transaction'
        );


	function startTest() {
		$this->ProgramRegistrations =& new TestProgramRegistrationsController();
		$this->ProgramRegistrations->constructClasses();
	}

	function endTest() {
		unset($this->ProgramRegistrations);
		ClassRegistry::flush();
	}
	
	function testIndex() {
		$this->ProgramRegistrations->Session->write('Auth.User', array(
	        'id' => 9,
	        'role_id' => 1,
	        'username' => 'smith'
	    ));
		
		$expectedResult = array(
			array(
				'id' => 1,
				'program_id' => 1,
				'label' => 'Preferred program schedule',
				'type' => 'select',
				'name' => 'preferred_program_schedule',
				'attributes' => '{\"class\":\"testClass\"}',
				'options' => '{\"\" : \"Select\", \"Winter\\/Spring only\":\"Winter\\/Spring only (540 Hours)\",\"School Year\":\"School Year (540 Hours)\", \"Summer Program\":\"Summer Program (300 Hours)\", \"Fall\\/Winter only\":\"Fall\\/Winter only (540 Hours)\"}',
				'validation' => '{\"rule\":\"notEmpty\"}',
				'created' => '2011-03-23 16:42:20',
				'modified' => '2011-03-24 16:42:24'
			),
			array(
				'id' => 2,
				'program_id' => 1,
				'label' => 'Preferred program setting',
				'type' => 'select',
				'name' => 'preferred_program_setting',
				'attributes' => NULL,
				'options' => '{\"\":\"Select\", \"Private provider\":\"Private provider (child care, private school, faith-based)\", \"Public school\":\"Public school\"}',
				'validation' => '{\"rule\":\"notEmpty\"}',
				'created' => '2011-03-24 15:01:17',
				'modified' => '2011-03-24 15:01:22'
			),
		);
		
		$result = $this->testAction('/program_registrations/index/1', array('return' => 'vars'));
		$result = Set::extract('ProgramField', $result['program']);
		$this->assertEqual($result, $expectedResult);
	}
	
	function testIndexNoId() {
		$this->ProgramRegistrations->Session->write('Auth.User', array(
	        'id' => 9,
	        'role_id' => 1,
	        'username' => 'smith'
	    ));
	    $this->ProgramRegistrations->params = Router::parse('/program_registrations/index');
	    $this->ProgramRegistrations->Component->startup($this->ProgramRegistrations);
		$this->ProgramRegistrations->index();	
		$this->assertEqual($this->ProgramRegistrations->Session->read('Message.flash.element'), 'flash_failure');		
	}

}
?>