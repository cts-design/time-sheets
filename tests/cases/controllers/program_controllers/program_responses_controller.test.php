<?php
/* ProgramResponses Test cases generated on: 2011-03-28 21:10:55 : 1301346655*/
App::Import('Controller', 'ProgramResponses');

App::Import('Lib', 'AtlasTestCase');

App::import('Component', 'Email');
Mock::generate('EmailComponent');

class TestProgramResponsesController extends ProgramResponsesController {
	public $autoRender = false;

	public function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class ProgramResponsesControllerTestCase extends AtlasTestCase {

	public function startTest() {
		$this->ProgramResponses =& new TestProgramResponsesController();
		$this->ProgramResponses->constructClasses();
        $this->ProgramResponses->params['controller'] = 'program_responses';
        $this->ProgramResponses->params['pass'] = array();
        $this->ProgramResponses->params['named'] = array();	
		$this->testController = $this->ProgramResponses;
	}
	
	public function testIndex() {
		$this->ProgramResponses->Component->initialize($this->ProgramResponses);
		$this->ProgramResponses->Session->write('Auth.User', array(
	        'id' => 11,
	        'role_id' => 1,
	        'username' => 'duck'
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
		
		$result = $this->testAction('/program_responses/index/1');
		$result = Set::extract('ProgramField', $result['program']);
		$this->assertEqual($result, $expectedResult);
	}
	
	public function testIndexPostResponse() {
		$this->ProgramResponses->Component->initialize($this->ProgramResponses);
		$this->ProgramResponses->Session->write('Auth.User', array(
	        'id' => 11,
	        'role_id' => 1,
	        'username' => 'duck'
	    ));
	    $data = array(
	    	'ProgramResponse' => array(
	    		'question' => 'answer'
	    	)
		);
		$this->Programs->Email =& new MockEmailComponent();
	    $this->Programs->Email->setReturnValue('send', true);
		$this->Programs->Email->enabled = true;	    
	    $result = $this->testAction('/program_responses/index/1', array('data' => $data));
	    $response = $this->ProgramResponses->ProgramResponse->find('first', array('conditions' => array(
	   		'ProgramResponse.program_id' => 1,
	   		'ProgramResponse.user_id' => 11,
			'ProgramResponse.expires_on >= ' => date('Y-m-d H:i:s') 
	   	)));
		$this->assertTrue($response['ProgramResponse']['id'], 2);
		$this->assertTrue($response['ProgramResponse']['user_id'], 11);
		$this->assertTrue($response['ProgramResponse']['answers'], '{"question":"answer"}')	;
	}
	
	public function testIndexNoId() {
		$this->ProgramResponses->params = Router::parse('/program_responses/index');	
		$this->ProgramResponses->Component->initialize($this->ProgramResponses);
		$this->ProgramResponses->Session->write('Auth.User', array(
	        'id' => 9,
	        'role_id' => 1,
	        'username' => 'smith'
	    ));
	    $this->mockAcl($this->ProgramResponses);
	    $this->ProgramResponses->beforeFilter();
	    $this->ProgramResponses->Component->startup($this->ProgramResponses);
		$this->ProgramResponses->index();	
		$this->assertEqual($this->ProgramResponses->Session->read('Message.flash.element'), 'flash_failure');		
	}

	public function testRequiredDocsNoId() {
		$this->ProgramResponses->params = Router::parse('/program_responses/required_docs');	
		$this->ProgramResponses->Component->initialize($this->ProgramResponses);
		$this->ProgramResponses->Session->write('Auth.User', array(
	        'id' => 9,
	        'role_id' => 1,
	        'username' => 'smith'
	    ));
	    $this->mockAcl($this->ProgramResponses);
	    $this->ProgramResponses->beforeFilter();
	    $this->ProgramResponses->Component->startup($this->ProgramResponses);
		$this->ProgramResponses->required_docs();	
		$this->assertEqual($this->ProgramResponses->Session->read('Message.flash.element'), 'flash_failure');		
	}

	public function testRequiredDocsReset() {
		$this->ProgramResponses->Component->initialize($this->ProgramResponses);
		$this->ProgramResponses->Session->write('Auth.User', array(
	        'id' => 13,
	        'role_id' => 1,
	        'username' => 'marley'
	    ));
		$result = $this->testAction('/program_responses/required_docs/1/1');
		$response = $this->ProgramResponses->ProgramResponse->find('first', array('conditions' => array(
			'ProgramResponse.user_id' => 13,
			'ProgramResponse.program_id' => 1,
			'ProgramResponse.expires_on >= ' => date('Y-m-d H:i:s') 	
		)));
		$this->assertEqual($response['ProgramResponse']['id'], 4);
		$this->assertEqual($response['ProgramResponse']['user_id'], 13);
		$this->assertEqual($response['ProgramResponse']['program_id'], 1);
		$this->assertEqual($response['ProgramResponse']['uploaded_docs'], 0);		
	}
	
	public function testRequiredDocsBadData() {
		$this->ProgramResponses->Component->initialize($this->ProgramResponses);
		$this->ProgramResponses->Session->write('Auth.User', array(
	        'id' => 13,
	        'role_id' => 1,
	        'username' => 'marley'
	    ));
		$data = array('QueuedDocument' => array(
			'submittedfile' => 'badData'	
		));
		$result = $this->testAction('/program_responses/required_docs/1', array('data' => $data));
		$this->assertEqual($this->ProgramResponses->Session->read('Message.flash.element'), 'flash_failure');			
	}
	
	public function testRequiredDocsNoFile() {
		$this->ProgramResponses->Component->initialize($this->ProgramResponses);
		$this->ProgramResponses->Session->write('Auth.User', array(
	        'id' => 13,
	        'role_id' => 1,
	        'username' => 'marley'
	    ));
		$data = array('ProgramResponse' => array(
			'test' => 'badData'	
		));
		$result = $this->testAction('/program_responses/required_docs/1', array('data' => $data));
		$redirect = array('action' => 'required_docs', 0 => 1);
		$this->assertEqual($this->ProgramResponses->redirectUrl, $redirect);
		$this->assertEqual($this->ProgramResponses->Session->read('Message.flash.element'), 'flash_failure');			
	}	
	
	public function testResponseCompleteNoId() {
		$this->ProgramResponses->Component->initialize($this->ProgramResponses);
		$this->ProgramResponses->Session->write('Auth.User', array(
	        'id' => 15,
	        'role_id' => 1,
	        'username' => 'jim'
	    ));
		$result = $this->testAction('/program_responses/response_complete');
		$this->assertFalse($result['programResponse']);
		$this->assertEqual($this->ProgramResponses->Session->read('Message.flash.element'), 'flash_failure');			
	}
	
	public function testResponseComplete() {
		$this->ProgramResponses->Component->initialize($this->ProgramResponses);
		$this->ProgramResponses->Session->write('Auth.User', array(
	        'id' => 15,
	        'role_id' => 1,
	        'username' => 'jim'
	    ));
		$result = $this->testAction('/program_responses/response_complete/1');
		$response = $result['programResponse']['ProgramResponse'];
		$this->assertEqual($response['id'], 6);
		$this->assertEqual($response['program_id'], 1);
		$this->assertEqual($response['user_id'], 15);
		$this->assertEqual($response['complete'], 1);			
	}
	
	public function testViewCertNoId() {
		$this->ProgramResponses->Component->initialize($this->ProgramResponses);
		$this->ProgramResponses->Session->write('Auth.User', array(
	        'id' => 15,
	        'role_id' => 1,
	        'username' => 'jim'
	    ));
		$result = $this->testAction('/program_responses/view_cert/');
		$this->assertEqual($this->ProgramResponses->Session->read('Message.flash.element'), 'flash_failure');
		$this->assertEqual($this->ProgramResponses->redirectUrl, array('action' => 'index'));		
	}
	
	public function testViewCert() {
		$this->ProgramResponses->Component->initialize($this->ProgramResponses);
		$this->ProgramResponses->Session->write('Auth.User', array(
	        'id' => 15,
	        'role_id' => 1,
	        'username' => 'jim'
	    ));
		$expectedResult = array(
			'id' => '20110601093409467863.pdf',
			'name' => '20110601093409467863',
			'extension' => 'pdf',
			'cache' => 1,
			'path' => '/storage/2010/11/'	
		);
		$result = $this->testAction('/program_responses/view_cert/1');
		$this->assertEqual($result, $expectedResult);
	}
	
	public function testProvidedDocsUploadedDocs() {
		$this->ProgramResponses->Component->initialize($this->ProgramResponses);
		$this->ProgramResponses->Session->write('Auth.User', array(
	        'id' => 12,
	        'role_id' => 1,
	        'username' => 'rabbit'
	    ));
		$result = $this->testAction('/program_responses/provided_docs/1/uploaded_docs');
		$response = $this->ProgramResponses->ProgramResponse->find('first', array('conditions' => array(
			'ProgramResponse.id' => 3,
			'ProgramResponse.user_id' => 12,
		)));
		$this->assertEqual($response['ProgramResponse']['id'], 3);
		$this->assertEqual($response['ProgramResponse']['uploaded_docs'], 1);
	}
	
	public function testProvidedDocsDropingOffDocs() {
		$this->ProgramResponses->Component->initialize($this->ProgramResponses);
		$this->ProgramResponses->Session->write('Auth.User', array(
	        'id' => 12,
	        'role_id' => 1,
	        'username' => 'rabbit'
	    ));
		$result = $this->testAction('/program_responses/provided_docs/1/dropping_off_docs');
		$response = $this->ProgramResponses->ProgramResponse->find('first', array('conditions' => array(
			'ProgramResponse.id' => 3,
			'ProgramResponse.user_id' => 12,
		)));
		$this->assertEqual($response['ProgramResponse']['id'], 3);
		$this->assertEqual($response['ProgramResponse']['dropping_off_docs'], 1);
	}
	
	public function testAdminIndexOpen() {
		$this->ProgramResponses->Component->initialize($this->ProgramResponses);
		$this->ProgramResponses->Session->write('Auth.User', array(
	        'id' => 2,
	        'role_id' => 2,
	        'username' => 'dnolan',
	        'location_id' => 1
	    ));
		$data['filter'] = 'open';
		$data['page'] = 1;	
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';	
		$result = $this->testAction('/admin/program_responses/index/1/',  array('method' => 'get', 'data' => $data));
		$this->assertTrue($result['data']['success']);
		$this->assertEqual($result['data']['totalCount'], 5);
		$this->assertEqual($result['data']['responses'][0]['id'], 1);
		$this->assertEqual($result['data']['responses'][0]['status'], 'Open');
		$this->assertEqual($result['data']['responses'][1]['id'], 2);
		$this->assertEqual($result['data']['responses'][1]['status'], 'Open');
		$this->assertEqual($result['data']['responses'][2]['id'], 3);
		$this->assertEqual($result['data']['responses'][2]['status'], 'Open');
		$this->assertEqual($result['data']['responses'][3]['id'], 4);
		$this->assertEqual($result['data']['responses'][3]['status'], 'Open');
		$this->assertEqual($result['data']['responses'][4]['id'], 5);
		$this->assertEqual($result['data']['responses'][4]['status'], 'Open');
	}

	public function testAdminIndexClosed() {
		$this->ProgramResponses->Component->initialize($this->ProgramResponses);
		$this->ProgramResponses->Session->write('Auth.User', array(
	        'id' => 2,
	        'role_id' => 2,
	        'username' => 'dnolan',
	        'location_id' => 1
	    ));
		$data['filter'] = 'closed';
		$data['page'] = 1;	
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';	
		$result = $this->testAction('/admin/program_responses/index/1/',  array('method' => 'get', 'data' => $data));
		$this->assertTrue($result['data']['success']);
		$this->assertEqual($result['data']['totalCount'], 1);
		$this->assertEqual($result['data']['responses'][0]['id'], 6);
		$this->assertEqual($result['data']['responses'][0]['status'], 'Closed');		
	}
	
	public function testAdminIndexExpired() {
		$this->ProgramResponses->Component->initialize($this->ProgramResponses);
		$this->ProgramResponses->Session->write('Auth.User', array(
	        'id' => 2,
	        'role_id' => 2,
	        'username' => 'dnolan',
	        'location_id' => 1
	    ));
		$data['filter'] = 'expired';
		$data['page'] = 1;	
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';	
		$result = $this->testAction('/admin/program_responses/index/1/',  array('method' => 'get', 'data' => $data));
		$this->assertTrue($result['data']['success']);
		$this->assertEqual($result['data']['totalCount'], 1);
		$this->assertEqual($result['data']['responses'][0]['id'], 7);
		$this->assertEqual($result['data']['responses'][0]['expires_on'], '2011-06-08 16:13:30');
	}
	
	public function testAdminIndexUnApproved() {
		$this->ProgramResponses->Component->initialize($this->ProgramResponses);
		$this->ProgramResponses->Session->write('Auth.User', array(
	        'id' => 2,
	        'role_id' => 2,
	        'username' => 'dnolan',
	        'location_id' => 1
	    ));
		$data['filter'] = 'unapproved';
		$data['page'] = 1;	
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';	
		$result = $this->testAction('/admin/program_responses/index/1/',  array('method' => 'get', 'data' => $data));
		$this->assertTrue($result['data']['success']);
		$this->assertEqual($result['data']['totalCount'], 1);
		$this->assertEqual($result['data']['responses'][0]['id'], 8);
	}
	
	public function testAdminIndexNoResults() {
		$this->ProgramResponses->Component->initialize($this->ProgramResponses);
		$this->ProgramResponses->Session->write('Auth.User', array(
	        'id' => 2,
	        'role_id' => 2,
	        'username' => 'dnolan',
	        'location_id' => 1
	    ));
		$data['filter'] = 'open';
		$data['page'] = 1;	
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';	
		$result = $this->testAction('/admin/program_responses/index/2/',  array('method' => 'get', 'data' => $data));
		$this->assertTrue($result['data']['success']);
		$this->assertEqual($result['data']['totalCount'], 0);
		$this->assertFalse($result['data']['responses']);
		$this->assertEqual($result['data']['message'], 'No results at this time.');
	}
	
	public function testAdminViewNoAjax() {
		$this->ProgramResponses->Component->initialize($this->ProgramResponses);
		$this->ProgramResponses->Session->write('Auth.User', array(
	        'id' => 2,
	        'role_id' => 2,
	        'username' => 'dnolan',
	        'location_id' => 1
	    ));	
		$expectedResult = array('title_for_layout' => 'Program Response', 'approval' => 'false');
		$result = $this->testAction('/admin/program_responses/view/1/',  array('method' => 'get'));
		$this->assertEqual($result, $expectedResult);
	}
	
	public function testAdminViewUser() {
		$this->ProgramResponses->Component->initialize($this->ProgramResponses);
		$this->ProgramResponses->Session->write('Auth.User', array(
	        'id' => 2,
	        'role_id' => 2,
	        'username' => 'dnolan',
	        'location_id' => 1
	    ));	
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';		
		$result = $this->testAction('/admin/program_responses/view/1/user',  array('method' => 'get'));
		$this->assertEqual($result['user']['id'], 9);
	}

	public function testAdminViewAnswers() {
		$this->ProgramResponses->Component->initialize($this->ProgramResponses);
		$this->ProgramResponses->Session->write('Auth.User', array(
	        'id' => 2,
	        'role_id' => 2,
	        'username' => 'dnolan',
	       	'location_id' => 1
	    ));	
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';		
		$result = $this->testAction('/admin/program_responses/view/3/answers',  array('method' => 'get'));
		$expectedResult = array(
			'title_for_layout' => 'Program Response',
			'approval' => 'false',
			'answers' => array('question' => 'answer', 'question2' => 'answer2'),
			'viewedMedia' => 'Yes',
			'completedForm' => 'Yes');
		$this->assertEqual($result, $expectedResult);	
	}
	
	public function testAdminViewNoAnswers() {
		$this->ProgramResponses->Component->initialize($this->ProgramResponses);
		$this->ProgramResponses->Session->write('Auth.User', array(
	        'id' => 2,
	        'role_id' => 2,
	        'username' => 'dnolan',
	       	'location_id' => 1
	    ));	
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';		
		$result = $this->testAction('/admin/program_responses/view/2/answers',  array('method' => 'get'));
		$expectedResult = array(
			'title_for_layout' => 'Program Response',
			'approval' => 'false',
			'answers' => NULL,
			'viewedMedia' => 'Yes',
			'completedForm' => 'No');
		$this->assertEqual($result, $expectedResult);
	}

	public function testAdminViewDocuments() {
		$this->ProgramResponses->Component->initialize($this->ProgramResponses);
		$this->ProgramResponses->Session->write('Auth.User', array(
	        'id' => 2,
	        'role_id' => 2,
	        'username' => 'dnolan',
	       	'location_id' => 1
	    ));	
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';		
		$result = $this->testAction('/admin/program_responses/view/2/documents',  array('method' => 'get'));
		$doc = array(
			'name' => 'Birth Proof',
			'filedDate' => '2011-05-06 10:15:29',
			'id' => 9,
			'link' => '<a href="/admin/filed_documents/view/9" target="_blank">View Doc</a>');
		$this->assertEqual($result['docs'][0], $doc);
		
	}
	
	public function testAdminViewNoDocuments() {
		$this->ProgramResponses->Component->initialize($this->ProgramResponses);
		$this->ProgramResponses->Session->write('Auth.User', array(
	        'id' => 2,
	        'role_id' => 2,
	        'username' => 'dnolan',
	        'location_id' => 1
	    ));	
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';		
		$result = $this->testAction('/admin/program_responses/view/5/documents',  array('method' => 'get'));
		$this->assertEqual($result['docs'], 'No program response documents filed for this user.');
	}
	
	public function testAdminApproveNoId() {
		$this->ProgramResponses->Component->initialize($this->ProgramResponses);
		$this->ProgramResponses->Session->write('Auth.User', array(
	        'id' => 2,
	        'role_id' => 2,
	        'username' => 'dnolan',
	        'location_id' => 1
	    ));	
		$expectedResult = array('data' => array('success' => FALSE, 'message' => 'Invalid program response id.'));
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';		
		$result = $this->testAction('/admin/program_responses/approve/',  array('method' => 'get'));
		$this->assertEqual($result, $expectedResult);
	}	

	public function testAdminApprove() {
		$this->ProgramResponses->Component->initialize($this->ProgramResponses);
		$this->ProgramResponses->Session->write('Auth.User', array(
	        'id' => 2,
	        'role_id' => 2,
	        'username' => 'dnolan',
	        'location_id' => 1
	    ));	
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';		
		$result = $this->testAction('/admin/program_responses/approve/8',  array('method' => 'get'));
		$response = $this->ProgramResponses->ProgramResponse->findById(8);
		$this->assertTrue($result['data']['success']);
		$this->assertEqual($result['data']['message'], 'Program response was approved successfully.');
		$this->assertEqual($response['ProgramResponse']['id'], 8);
		$this->assertEqual($response['ProgramResponse']['complete'], 1);
		$this->assertEqual($response['ProgramResponse']['needs_approval'], 0);

	}
	
	public function testAdminApproveNoPaperForms() {
		$this->ProgramResponses->Component->initialize($this->ProgramResponses);
		$this->ProgramResponses->Session->write('Auth.User', array(
	        'id' => 2,
	        'role_id' => 2,
	        'username' => 'dnolan',
	        'location_id' => 1
	    ));	
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';		
		$result = $this->testAction('/admin/program_responses/approve/2',  array('method' => 'get'));
		$this->assertFalse($result['data']['success']);
		$this->assertEqual($result['data']['message'], 'You must generate all program forms before approving response.');
	}
	
	public function testAdminApproveNoRequiredDocs() {
		$this->ProgramResponses->Component->initialize($this->ProgramResponses);
		$this->ProgramResponses->Session->write('Auth.User', array(
	        'id' => 2,
	        'role_id' => 2,
	        'username' => 'dnolan',
	        'location_id' => 1
	    ));	
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';		
		$result = $this->testAction('/admin/program_responses/approve/5',  array('method' => 'get'));
		$this->assertFalse($result['data']['success']);
		$this->assertEqual($result['data']['message'],
			'All required documents must be filed to customer before approving response.');
	}
	
	public function testAdminGenerateForm() {
		$this->ProgramResponses->Component->initialize($this->ProgramResponses);
		$this->ProgramResponses->Session->write('Auth.User', array(
	        'id' => 2,
	        'role_id' => 2,
	        'username' => 'dnolan',
	        'location_id' => 1
	    ));	
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';		
		$result = $this->testAction('/admin/program_responses/generate_form/1/5',  array('method' => 'get'));
		$this->assertTrue($result['data']['success']);
		$this->assertEqual($result['data']['message'], 'Form generated and filed successfully.');
	}
	
	public function testAdminToggleExpired() {
		$this->ProgramResponses->Component->initialize($this->ProgramResponses);
		$this->ProgramResponses->Session->write('Auth.User', array(
	        'id' => 2,
	        'role_id' => 2,
	        'username' => 'dnolan',
	        'location_id' => 1
	    ));	
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';		
		$result = $this->testAction('/admin/program_responses/toggle_expired/5/expired',  array('method' => 'get'));
		$this->assertTrue($result['data']['success']);
		$this->assertEqual($result['data']['message'], 'Response marked expired successfully.');
		
		$result = $this->testAction('/admin/program_responses/toggle_expired/5/unexpire',  array('method' => 'get'));
		$this->assertTrue($result['data']['success']);
		$this->assertEqual($result['data']['message'], 'Response marked un-expired successfully.');				
	}	
	
		 
	public function endTest() {
		Configure::write('debug', 2);
		unset($_SERVER['HTTP_X_REQUESTED_WITH']);		
		unset($this->ProgramResponses);
		ClassRegistry::flush();
	}
}
?>