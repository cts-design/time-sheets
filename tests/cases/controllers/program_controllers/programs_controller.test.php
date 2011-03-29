<?php
/* Programs Test cases generated on: 2011-03-29 12:34:28 : 1301402068*/
App::import('Controller', 'Programs');
class TestProgramsController extends ProgramsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
		return $this->redirectUrl;
	}
}

class ProgramsControllerTestCase extends CakeTestCase {
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
		$this->Programs =& new TestProgramsController();
		$this->Programs->constructClasses();
		$this->Programs->Component->initialize($this->Programs);
	}

	function endTest() {
		$this->Programs->Session->destroy();
		unset($this->Programs);
		ClassRegistry::flush();
	}

	function testIndex() {
		$this->Programs->Session->write('Auth.User', array(
	        'id' => 9,
	        'role_id' => 1,
	        'username' => 'smith'
	    ));
		$expectedResult = array(
			'id' => 1,
			'name' => 'VPK',
			'type' => 'video_registration_docs',
			'media' => '/programs/vpk/vpk.flv',
			'instructions' => 'Please watch the video below. You will be taken to the registration page automatically when the video is over. ',
			'disabled' => 0,
			'created' => '2011-03-23 00:00:00',
			'modified' => '2011-03-23 00:00:00',
			'expires' => '2011-04-23 00:00:00'
		);		
		$result = $this->testAction('/programs/index/1', array('return' => 'vars'));
		$result = Set::Extract('Program', $result['program']);
		$this->assertEqual($result, $expectedResult);			
	}
	
	function testIndexDisabledProgram() {
		$this->Programs->Session->write('Auth.User', array(
	        'id' => 9,
	        'role_id' => 1,
	        'username' => 'smith'
	    ));
	    $this->Programs->params = Router::parse('/programs/index/2');		
	    $this->Programs->Component->startup($this->Programs);
		$this->Programs->index(2);	
		$this->assertEqual($this->Programs->Session->read('Message.flash.element'), 'flash_failure');		
	}
	
	function testIndexNoId() {
		$this->Programs->Session->write('Auth.User', array(
	        'id' => 9,
	        'role_id' => 1,
	        'username' => 'smith'
	    ));
	    $this->Programs->params = Router::parse('/programs/index');
	    $this->Programs->Component->startup($this->Programs);
		$this->Programs->index();	
		$this->assertEqual($this->Programs->Session->read('Message.flash.element'), 'flash_failure');		
	}
}
?>