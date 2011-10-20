<?php
/* ProgramEmails Test cases generated on: 2011-08-11 13:27:14 : 1313083634*/
App::import('Controller', 'ProgramEmails');
App::import('Lib', 'AtlasTestCase');
class TestProgramEmailsController extends ProgramEmailsController {
	public $autoRender = false;

	public function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class ProgramEmailsControllerTestCase extends AtlasTestCase {

	public function startTest() {
		$this->ProgramEmails =& new TestProgramEmailsController();
		$this->ProgramEmails->constructClasses();
        $this->ProgramEmails->params['controller'] = 'program_emails';
        $this->ProgramEmails->params['pass'] = array();
        $this->ProgramEmails->params['named'] = array();	
		$this->testController = $this->ProgramEmails;		
	}
	
	public function testAdminIndexNoId() {		
		$this->ProgramEmails->params = Router::parse('/admin/program_emails/index/');
		$this->ProgramEmails->Component->initialize($this->ProgramEmails);
		$this->ProgramEmails->Session->write('Auth.User', array(
	        'id' => 10,
	        'role_id' => 1,
	        'username' => 'test'
	    ));
		$this->ProgramEmails->data = array();
		$this->mockAcl($this->ProgramEmails);		
		$this->ProgramEmails->beforeFilter();		
	    $this->ProgramEmails->Component->startup($this->ProgramEmails);			
		$this->ProgramEmails->admin_index();
		$this->assertEqual($this->ProgramEmails->Session->read('Message.flash.element'), 'flash_failure');
		$this->ProgramEmails->Session->destroy();	
	}
	
	public function testAdminEmailsIndexAjax() {
		$this->ProgramEmails->Session->write('Auth.User', array(
	        'id' => 2,
	        'role_id' => 2,
	        'username' => 'dnolan',
	        'location_id' => 1
	    ));
		$expectedResult = 
			array(
			'id'=>'1',
			'program_id'=>'1',
			'cat_id'=>NULL,
			'to'=>NULL,
			'from'=>NULL,
			'subject'=>'Required Media Complete ',
			'body'=>'Thank you for viewing the VPK Orientation. Please be certain to log back into the system and complete the VPK online process if you exited at this stage. ',
			'type'=>'media',
			'name'=>'VPK Media',
			'disabled'=>'No',
			'created'=>'2011-04-04 10:44:25',
			'modified'=>'2011-04-04 10:44:28'
		);
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$result = $this->testAction('/admin/program_emails/index/1', array('method' => 'get'));	
		$this->assertEqual($expectedResult, $result['data']['emails'][0]);
	}	
	
	public function testAdminEditEmails() {
		$this->ProgramEmails->Component->initialize($this->ProgramEmails);		
        $data = array(
	        'ProgramEmail' => array(
	            'id' => 1,
	            'body' => 'Updated Emails text',
	            'program_id' => 1,
	            'type' => 'media'			
			)
        );
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';			
		$result = $this->testAction('/admin/program_emails/edit/1', array('data' => $data));
		$email = $this->ProgramEmails->ProgramEmail->read(null, 1);
		$this->assertEqual($email['ProgramEmail']['body'], 'Updated Emails text');
	}
	
	public function testAdminEditEmailsNoData() {
		$this->ProgramEmails->Component->initialize($this->ProgramEmails);
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';			
		$result = $this->testAction('/admin/program_Emails/edit/1', array('data' => ''));
		$expectedResult = array('data'=>array('success'=>FALSE,'message'=>'Unable to save email.'));
		$this->assertEqual($result, $expectedResult);
	}
	
	public function testAdminToggleDisabledDisable() {
		$this->ProgramEmails->Component->initialize($this->ProgramEmails);
		
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';			
		$result = $this->testAction('/admin/program_emails/toggle_disabled/1/1', array('method' => 'get'));
		$email = $this->ProgramEmails->ProgramEmail->read(null, 1);
		$this->assertTrue($email['ProgramEmail']['disabled']);
	}
	
	public function testAdminToggleDisabledEnable() {
		$this->ProgramEmails->Component->initialize($this->ProgramEmails);
		
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';			
		$result = $this->testAction('/admin/program_emails/toggle_disabled/1/0', array('method' => 'get'));
		$email = $this->ProgramEmails->ProgramEmail->read(null, 1);
		$this->assertFalse($email['ProgramEmail']['disabled']);
	}
	
	public function endTest() {
		Configure::write('debug', 2);
		unset($_SERVER['HTTP_X_REQUESTED_WITH']);		
		unset($this->ProgramEmails);
		ClassRegistry::flush();
	}
}
?>