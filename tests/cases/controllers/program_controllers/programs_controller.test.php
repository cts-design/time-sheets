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
            'app.aros_aco',
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
            'app.program_response',
            'app.watched_filing_cat',
            'app.program_instruction',
            'app.program_response_doc',
            'app.program_paper_form',
            'app.program_email',
            'app.module_access_control',
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
		unset($this->Programs);
		ClassRegistry::flush();
	}
	
	function testIndexNoResponse() {
		$this->Programs->Session->write('Auth.User', array(
	        'id' => 10,
	        'role_id' => 1,
	        'username' => 'test'
	    ));
		$expectedResult = array(
			'id' => 1,
			'name' => 'VPK',
			'type' => 'video_form_docs',
			'media' => 'vpk.flv',
			'atlas_registration_type' => '',
			'media_expires' => 0,
			'disabled' => 0,
			'queue_category_id' => 6,
			'cert_type' => 'coe',
			'approval_required' => 1,
			'form_esign_required' => 1,
			'conformation_id_length' => 9,
			'response_expires_in' => 30,
			'created' => '2011-03-23 00:00:00',
			'modified' => '2011-05-03 12:36:22',
			'expires' => '2011-04-23 00:00:00'
		);		
		$result = $this->testAction('/programs/index/1', array('return' => 'vars'));
		$program = $result['program']['Program'];
		$this->assertEqual($result['title_for_layout'], 'VPK');
		$this->assertEqual($expectedResult, $program);	
	}	

	function testIndexWithResponse() {
		$this->Programs->Session->write('Auth.User', array(
	        'id' => 9,
	        'role_id' => 1,
	        'username' => 'smith'
	    ));
	    $this->Programs->params = Router::parse('/programs/index/1');
	    $this->Programs->Component->startup($this->Programs);
		$this->Programs->index(1);
		$expectedResult = array('controller' => 'programs', 'action' => 'view_media', 0 => 1);
		$this->assertEqual($this->Programs->redirectUrl, $expectedResult );
		$this->Programs->Session->destroy();			
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
		$this->Programs->Session->destroy();	
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
		$this->assertEqual($this->Programs->redirectUrl, '/');
		$this->assertEqual($this->Programs->Session->read('Message.flash.element'), 'flash_failure');
		$this->Programs->Session->destroy();			
	}
	
	function testIndexViewedMedia() {
		$this->Programs->Session->write('Auth.User', array(
	        'id' => 11,
	        'role_id' => 1,
	        'username' => 'dduck'
	    ));
	    $this->Programs->params = Router::parse('/programs/index/1');
	    $this->Programs->Component->startup($this->Programs);
		$this->Programs->index(1);
		$expectedResult = array('controller' => 'program_responses', 'action' => 'index', 0 => 1);
		$this->assertEqual($this->Programs->redirectUrl, $expectedResult);
		$this->Programs->Session->destroy();			
	}
	
	function testIndexCompletedForm() {
		$this->Programs->Session->write('Auth.User', array(
	        'id' => 12,
	        'role_id' => 1,
	        'username' => 'rrabbit'
	    ));
	    $this->Programs->params = Router::parse('/programs/index/1');
	    $this->Programs->Component->startup($this->Programs);
		$this->Programs->index(1);
		$expectedResult = array('controller' => 'program_responses', 'action' => 'required_docs', 0 => 1);	
		$this->assertEqual($this->Programs->redirectUrl, $expectedResult);
		$this->Programs->Session->destroy();		
	}
	
	function testIndexUploadedDocs() {
		$this->Programs->Session->write('Auth.User', array(
	        'id' => 13,
	        'role_id' => 1,
	        'username' => 'bmarley'
	    ));
	    $this->Programs->params = Router::parse('/programs/index/1');
	    $this->Programs->Component->startup($this->Programs);
		$this->Programs->index(1);
		$expectedResult = array(
			'controller' => 'program_responses', 
			'action' => 'provided_docs', 
			0 => 1,
			1 => 'uploaded_docs');	
		$this->assertEqual($this->Programs->redirectUrl, $expectedResult);
		$this->Programs->Session->destroy();		
	}
	
	function testIndexDroppingOffDocs() {
		$this->Programs->Session->write('Auth.User', array(
	        'id' => 14,
	        'role_id' => 1,
	        'username' => 'bush'
	    ));
	    $this->Programs->params = Router::parse('/programs/index/1');
	    $this->Programs->Component->startup($this->Programs);
		$this->Programs->index(1);
		$expectedResult = array(
			'controller' => 'program_responses', 
			'action' => 'provided_docs', 
			0 => 1,
			1 => 'dropping_off_docs');	
		$this->assertEqual($this->Programs->redirectUrl, $expectedResult);
		$this->Programs->Session->destroy();		
	}
	
	function testIndexResponseComplete() {
		$this->Programs->Session->write('Auth.User', array(
	        'id' => 15,
	        'role_id' => 1,
	        'username' => 'jim'
	    ));
	    $this->Programs->params = Router::parse('/programs/index/1');
	    $this->Programs->Component->startup($this->Programs);
		$this->Programs->index(1);
		$expectedResult = array(
			'controller' => 'program_responses', 
			'action' => 'response_complete', 
			0 => 1);	
		$this->assertEqual($this->Programs->redirectUrl, $expectedResult);
		$this->Programs->Session->destroy();		
	}

	function testGetStarted() {
		$this->Programs->Session->write('Auth.User', array(
	        'id' => 10,
	        'role_id' => 1,
	        'username' => 'test'
	    ));
		$this->Programs->data = array(
			'ProgramResponse' => array(
				'program_id' => 1
			),
			'Program' => array(
				'redirect' => '/programs/view_media/1'
			)
		);
		$this->Programs->params = Router::parse('/programs/get_started');
	    $this->Programs->Component->startup($this->Programs);
		$this->Programs->get_started();
		
		$result = $this->Programs->Program->ProgramResponse->find('first', array('conditions' => array(
			'ProgramResponse.program_id' => 1,
			'ProgramResponse.user_id' => 10)));

		$this->assertEqual($result['ProgramResponse']['id'], 7);
		$this->assertEqual($result['ProgramResponse']['user_id'], 10);
		$this->assertEqual($this->Programs->redirectUrl, '/programs/view_media/1');
		$this->Programs->Session->destroy();	
		
	}
}
?>