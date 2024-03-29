<?php
/* Programs Test cases generated on: 2011-03-29 12:34:28 : 1301402068*/
App::import('Controller', 'Programs');
App::import('Lib', 'AtlasTestCase');

App::import('Component', 'Email');
Mock::generate('EmailComponent');

class TestProgramsController extends ProgramsController {
	
	public $name = 'Programs';
	
	public $autoRender = false;
	
	public function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}	
}

class ProgramsControllerTestCase extends AtlasTestCase {
	
	public function startTest() {
		$this->Programs =& new TestProgramsController();
		$this->Programs->constructClasses();
        $this->Programs->params['controller'] = 'programs';
        $this->Programs->params['pass'] = array();
        $this->Programs->params['named'] = array();	
		$this->testController = $this->Programs;
	}
	
	public function testIndexNoResponse() {
		$this->Programs->Component->initialize($this->Programs);
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
			'confirmation_id_length' => 9,
			'response_expires_in' => 30,
			'auth_required' => 1,
			'created' => '2011-03-23 00:00:00',
			'modified' => '2011-05-03 12:36:22',
			'expires' => '2011-04-23 00:00:00'
		);		
		$result = $this->testAction('/programs/index/1', array('method' => 'get'));
		$program = $result['program']['Program'];
		$this->assertEqual($result['title_for_layout'], 'VPK');
		$this->assertEqual($expectedResult, $program);	
	}	
	
	
	public function testIndexWithResponse() {
		$this->Programs->params['action'] = 'index';
        $this->Programs->params['url']['url'] = 'index/1';
		$this->Programs->Component->initialize($this->Programs);		
		$this->Programs->Session->write('Auth.User', array(
	        'id' => 9,
	        'role_id' => 1,
	        'username' => 'smith'
	    ));
		$this->mockAcl($this->Programs);		
		$this->Programs->beforeFilter();		
	    $this->Programs->Component->startup($this->Programs);
		$this->Programs->index(1);
		$expectedResult = array('controller' => 'programs', 'action' => 'view_media', 0 => 1, 1 => 'video');
		$this->assertEqual($this->Programs->redirectUrl, $expectedResult);
		$this->Programs->Session->destroy();		
	}

	public function testIndexProgramResponseNotApprroved() {
		$this->Programs->Component->initialize($this->Programs);
		$this->Programs->Session->write('Auth.User', array(
	        'id' => 13,
	        'role_id' => 1,
	        'username' => 'marley'
	    ));
		$expectedResult = array('controller' => 'program_responses', 'action' => 'not_approved', 0 => 7);
		$result = $this->testAction('/programs/index/7', array('method' => 'get'));
		$this->assertEqual($this->Programs->redirectUrl, $expectedResult);
	}

	public function testIndexMediaOnlyProgramNoResponse() {
		$this->Programs->Component->initialize($this->Programs);
		$this->Programs->Session->write('Auth.User', array(
	        'id' => 10,
	        'role_id' => 1,
	        'username' => 'test'
	    ));
		$result = $this->testAction('/programs/index/4', array('method' => 'get'));
		$this->assertEqual($result['title_for_layout'], 'TEST URI');
		$this->assertEqual($result['program']['Program']['id'], 4);	
	}
	
	public function testIndexMediaOnlyProgramWithResponse() {
		$this->Programs->Component->initialize($this->Programs);
		$this->Programs->Session->write('Auth.User', array(
	        'id' => 9,
	        'role_id' => 1,
	        'username' => 'smith'
	    ));
		$redirect = array('controller' => 'programs', 'action' => 'view_media', 0 => 4, 1 => 'uri');
		$result = $this->testAction('/programs/index/4', array('method' => 'get'));
		$this->assertEqual($redirect, $this->Programs->redirectUrl);
	}
	
	public function testIndexMediaOnlyProgramWithResponseNeedsApproval() {
		$this->Programs->Component->initialize($this->Programs);
		$this->Programs->Session->write('Auth.User', array(
	        'id' => 13,
	        'role_id' => 1,
	        'username' => 'marley'
	    ));
		$redirect = array('controller' => 'program_responses', 'action' => 'pending_approval', 0 => 4);
		$result = $this->testAction('/programs/index/4', array('method' => 'get'));
		$this->assertEqual($redirect, $this->Programs->redirectUrl);
	}
	
	public function testIndexFormOnlyProgram() {		
		$this->Programs->Component->initialize($this->Programs);
		$this->Programs->Session->write('Auth.User', array(
	        'id' => 9,
	        'role_id' => 1,
	        'username' => 'smith'
	    ));

		$result = $this->testAction('/programs/index/9', array('method' => 'get'));
		$redirect = array('controller' => 'program_responses', 'action' => 'index', 0 => 9);
		$this->assertEqual($this->Programs->redirectUrl, $redirect);
		$this->assertEqual($result['program']['Program']['id'], 9);
		$this->assertEqual($result['program']['Program']['name'], 'M.O.S.T.' );
	}
	
	public function testIndexMediaFormProgramNoResponse() {		
		$this->Programs->Component->initialize($this->Programs);
		$this->Programs->Session->write('Auth.User', array(
	        'id' => 9,
	        'role_id' => 1,
	        'username' => 'smith'
	    ));

		$result = $this->testAction('/programs/index/7', array('method' => 'get'));
		$this->assertEqual($result['program']['Program']['id'], 7);
		$this->assertEqual($result['program']['Program']['name'], 'TEST REG PDF FORM' );
	}
	
	public function testIndexMediaFormProgramWithResponse() {		
		$this->Programs->Component->initialize($this->Programs);
		$this->Programs->Session->write('Auth.User', array(
	        'id' => 9,
	        'role_id' => 1,
	        'username' => 'smith'
	    ));
		
		$result = $this->testAction('/programs/index/7', array('method' => 'get'));
		$this->assertEqual($result['program']['Program']['id'], 7);
		$this->assertEqual($result['program']['ProgramResponse'][0]['id'], 12);	
	}	
	
	public function testIndexDisabledProgram() {
		$this->Programs->params = Router::parse('/programs/index/2');
		
		$this->Programs->Component->initialize($this->Programs);
		$this->Programs->Session->write('Auth.User', array(
	        'id' => 9,
	        'role_id' => 1,
	        'username' => 'smith'
	    ));
		$this->mockAcl($this->Programs);		
		$this->Programs->beforeFilter();	    		
	    $this->Programs->Component->startup($this->Programs);
		$this->Programs->index(2);	
		$this->assertEqual($this->Programs->Session->read('Message.flash.element'), 'flash_failure');
		$this->Programs->Session->destroy();	
	}
	
	public function testIndexNoId() {
		$this->Programs->params = Router::parse('/programs/index');
		$this->Programs->Component->initialize($this->Programs);
		$this->Programs->Session->write('Auth.User', array(
	        'id' => 9,
	        'role_id' => 1,
	        'username' => 'smith'
	    ));
		$this->mockAcl($this->Programs);		
		$this->Programs->beforeFilter();
	    $this->Programs->Component->startup($this->Programs);
		$this->Programs->index();
		$this->assertEqual($this->Programs->redirectUrl, '/');
		$this->assertEqual($this->Programs->Session->read('Message.flash.element'), 'flash_failure');
		$this->Programs->Session->destroy();			
	}
	
	public function testIndexViewedMedia() {
		$this->Programs->params = Router::parse('/programs/index/1');
		$this->Programs->Component->initialize($this->Programs);
		$this->Programs->Session->write('Auth.User', array(
	        'id' => 11,
	        'role_id' => 1,
	        'username' => 'dduck'
	    ));
		$this->mockAcl($this->Programs);		
		$this->Programs->beforeFilter();	    
	    $this->Programs->Component->startup($this->Programs);
		$this->Programs->index(1);
		$expectedResult = array('controller' => 'program_responses', 'action' => 'index', 0 => 1);
		$this->assertEqual($this->Programs->redirectUrl, $expectedResult);
		$this->Programs->Session->destroy();			
	}
	
	public function testIndexCompletedForm() {
		$this->Programs->params = Router::parse('/programs/index/1');
		$this->Programs->Component->initialize($this->Programs);
		$this->Programs->Session->write('Auth.User', array(
	        'id' => 12,
	        'role_id' => 1,
	        'username' => 'rrabbit'
	    ));
		$this->mockAcl($this->Programs);		
		$this->Programs->beforeFilter();	    
	    $this->Programs->Component->startup($this->Programs);
		$this->Programs->index(1);
		$expectedResult = array('controller' => 'program_responses', 'action' => 'required_docs', 0 => 1);	
		$this->assertEqual($this->Programs->redirectUrl, $expectedResult);
		$this->Programs->Session->destroy();		
	}
	
	public function testIndexUploadedDocs() {
		$this->Programs->params = Router::parse('/programs/index/1');			
		$this->Programs->Component->initialize($this->Programs);
		$this->Programs->Session->write('Auth.User', array(
	        'id' => 13,
	        'role_id' => 1,
	        'username' => 'bmarley'
	    ));
		$this->mockAcl($this->Programs);		
		$this->Programs->beforeFilter();		
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
	
	public function testIndexDroppingOffDocs() {
		$this->Programs->params = Router::parse('/programs/index/1');	
		$this->Programs->Component->initialize($this->Programs);
		$this->Programs->Session->write('Auth.User', array(
	        'id' => 14,
	        'role_id' => 1,
	        'username' => 'bush'
	    ));
		$this->mockAcl($this->Programs);		
		$this->Programs->beforeFilter();		
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
	
	public function testIndexResponseComplete() {
		$this->Programs->params = Router::parse('/programs/index/1');	
		$this->Programs->Component->initialize($this->Programs);
		$this->Programs->Session->write('Auth.User', array(
	        'id' => 15,
	        'role_id' => 1,
	        'username' => 'jim'
	    ));    
		$this->mockAcl($this->Programs);		
		$this->Programs->beforeFilter();		
	    $this->Programs->Component->startup($this->Programs);
		$this->Programs->index(1);
		$expectedResult = array(
			'controller' => 'program_responses', 
			'action' => 'response_complete', 
			0 => 1);	
		$this->assertEqual($this->Programs->redirectUrl, $expectedResult);
		$this->Programs->Session->destroy();		
	}

	public function testGetStarted() {
		$this->Programs->params = Router::parse('/programs/get_started');
		$this->Programs->Component->initialize($this->Programs);
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
		$this->mockAcl($this->Programs);		
		$this->Programs->beforeFilter();	
	    $this->Programs->Component->startup($this->Programs);
		$this->Programs->get_started();
		
		$result = $this->Programs->Program->ProgramResponse->find('first', array('conditions' => array(
			'ProgramResponse.program_id' => 1,
			'ProgramResponse.user_id' => 10)));

		$this->assertEqual($result['ProgramResponse']['id'], 12);
		$this->assertEqual($result['ProgramResponse']['user_id'], 10);
		$this->assertEqual($this->Programs->redirectUrl, '/programs/view_media/1');
		$this->Programs->Session->destroy();		
	}
	
	public function testViewMediaNoId() {		
		$this->Programs->params = Router::parse('/programs/view_media');
		$this->Programs->Component->initialize($this->Programs);
		$this->Programs->Session->write('Auth.User', array(
	        'id' => 10,
	        'role_id' => 1,
	        'username' => 'test'
	    ));
		$this->mockAcl($this->Programs);		
		$this->Programs->beforeFilter();		
	    $this->Programs->Component->startup($this->Programs);			
		$this->Programs->view_media();
		$this->assertEqual($this->Programs->Session->read('Message.flash.element'), 'flash_failure');
		$this->Programs->Session->destroy();	
	}
	
	public function testViewMedia()	{
		$this->Programs->params = Router::parse('/programs/view_media/1');
		$this->Programs->Component->initialize($this->Programs);
		$this->Programs->data = array(
			'ProgramResponse' => array(
				'viewed_media' => 1,
				'program_id' => 1
			)
		);
		$this->Programs->Session->write('Auth.User', array(
	        'id' => 9,
	        'role_id' => 1,
	        'username' => 'smith'
	    ));
		$this->Programs->Email =& new MockEmailComponent();
	    $this->Programs->Email->setReturnValue('send', true);
		$this->Programs->Email->enabled = true;
		$this->Programs->Session->write('step2', 'form');
		$this->mockAcl($this->Programs);		
		$this->Programs->beforeFilter();		
	    $this->Programs->Component->startup($this->Programs);			
		$this->Programs->view_media(1);
		
		$result = $this->Programs->Program->ProgramResponse->find('first', array('conditions' => array(
			'ProgramResponse.user_id' => 9,
			'ProgramResponse.program_id' => 1,
			'ProgramResponse.expires_on >=' => date('Y-m-d H:i:s', strtotime('6/1/11'))
		)));
		$redirect = array(
			'controller' => 'program_responses',
			'action' => 'index',
			0 => 1
		);
		$this->assertEqual($this->Programs->redirectUrl, $redirect);
		$this->assertEqual($result['ProgramResponse']['viewed_media'], 1);
		$this->assertEqual($result['ProgramResponse']['user_id'], 9);
		$this->assertEqual($result['ProgramResponse']['program_id'], 1);	
		$this->Programs->Session->destroy();		
	}

	public function testViewMediaBadData()	{
		$this->Programs->params = Router::parse('/programs/view_media/1');
		$this->Programs->Component->initialize($this->Programs);
		$this->Programs->data = array(
			'ProgramResponse' => array(
				'viewed_media' => '',
				'program_id' => 1
			)
		);
		$this->Programs->Session->write('Auth.User', array(
	        'id' => 9,
	        'role_id' => 1,
	        'username' => 'smith'
	    ));
		$this->mockAcl($this->Programs);		
		$this->Programs->beforeFilter();		
	    $this->Programs->Component->startup($this->Programs);			
		$this->Programs->view_media(1);
		
		$this->assertEqual($this->Programs->Session->read('Message.flash.element'), 'flash_failure');		
		$this->Programs->Session->destroy();		
	}
	
	public function testLoadMedia() {
		$this->Programs->Component->initialize($this->Programs);
		$this->Programs->Session->write('Auth.User', array(
	        'id' => 9,
	        'role_id' => 1,
	        'username' => 'smith'
	    ));		
		$result = $this->testAction('programs/load_media/1', array('return' => 'vars'));
		$expectedResult = array(
			'id' => 'vpk.flv', 
			'name' => 'vpk',
			'extension' => 'flv',
			'path' => '/storage/program_media/'
		);
		$this->assertEqual($result, $expectedResult);
	}
	
	public function testLoadMediaNoId() {
		$this->Programs->params = Router::parse('/programs/load_media/');
		$this->Programs->Component->initialize($this->Programs);
		$this->Programs->Session->write('Auth.User', array(
	        'id' => 9,
	        'role_id' => 1,
	        'username' => 'smith'
	    ));
		$this->mockAcl($this->Programs);		
		$this->Programs->beforeFilter();		
	    $this->Programs->Component->startup($this->Programs);			
		$this->Programs->load_media();
		$this->assertEqual($this->Programs->Session->read('Message.flash.element'), 'flash_failure');		
		$this->Programs->Session->destroy();			
	}
	
	public function testAdminIndex() {
		$this->Programs->Component->initialize($this->Programs);
		$result = $this->testAction('/admin/programs', array('method' => 'get'));
		$this->assertEqual($result['title_for_layout'], 'Programs');
	}	
	
	public function testAdminIndexAjax() {
		$this->Programs->Component->initialize($this->Programs);
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$result = $this->testAction('/admin/programs', array('method' => 'get'));	
		$this->assertTrue($result['data']['success']);
	} 	
	

		
	public function endTest() {
		Configure::write('debug', 2);
		unset($_SERVER['HTTP_X_REQUESTED_WITH']);
		unset($this->Programs);
		ClassRegistry::flush();
	}	
}
?>