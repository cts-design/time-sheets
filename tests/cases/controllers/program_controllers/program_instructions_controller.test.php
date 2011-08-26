<?php
/* ProgramInstructions Test cases generated on: 2011-08-11 13:27:14 : 1313083634*/
App::import('Controller', 'ProgramInstructions');
App::import('Lib', 'AtlasTestCase');
class TestProgramInstructionsController extends ProgramInstructionsController {
	public $autoRender = false;

	public function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class ProgramInstructionsControllerTestCase extends AtlasTestCase {

	public function startTest() {
		$this->ProgramInstructions =& new TestProgramInstructionsController();
		$this->ProgramInstructions->constructClasses();
        $this->ProgramInstructions->params['controller'] = 'program_instructions';
        $this->ProgramInstructions->params['pass'] = array();
        $this->ProgramInstructions->params['named'] = array();	
		$this->testController = $this->ProgramInstructions;		
	}
	
	public function testAdminIndexNoId() {		
		$this->ProgramInstructions->params = Router::parse('/admin/program_instructions/index/');
		$this->ProgramInstructions->Component->initialize($this->ProgramInstructions);
		$this->ProgramInstructions->Session->write('Auth.User', array(
	        'id' => 10,
	        'role_id' => 1,
	        'username' => 'test'
	    ));
		$this->ProgramInstructions->data = array();
		$this->mockAcl($this->ProgramInstructions);		
		$this->ProgramInstructions->beforeFilter();		
	    $this->ProgramInstructions->Component->startup($this->ProgramInstructions);			
		$this->ProgramInstructions->admin_index();
		$this->assertEqual($this->ProgramInstructions->Session->read('Message.flash.element'), 'flash_failure');
		$this->ProgramInstructions->Session->destroy();	
	}

	public function testAdminInstructionsIndexAjax() {
		$this->ProgramInstructions->Session->write('Auth.User', array(
	        'id' => 2,
	        'role_id' => 2,
	        'username' => 'dnolan',
	        'location_id' => 1
	    ));
		$expectedResult = array(
				'id'=>'1',
				'program_id'=>'1',
				'type'=>'main',
				'text'=>'Updated instructions text',
				'name'=>'Main',
				'actions'=>'<a href="/admin/program_instructions/edit/1">Edit</a>'
				);	
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$result = $this->testAction('/admin/program_instructions/index/1', array('method' => 'get'));	
		$this->assertEqual($expectedResult, $result['data']['instructions'][0]);
	}	
	
	public function testAdminEditInstructions() {
		$this->ProgramInstructions->Component->initialize($this->ProgramInstructions);
		
        $data = array(
	        'ProgramInstruction' => array(
	            'id' => 1,
	            'text' => 'Updated instructions text',
	            'program_id' => 1,
	            'type' => 'main'			
			)
        );
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';			
		$result = $this->testAction('/admin/program_instructions/edit/1', 
			array('data' => $data));
		$instructions = $this->ProgramInstructions->ProgramInstruction->read(null, 1);
		$this->assertEqual($instructions['ProgramInstruction']['text'], 'Updated instructions text');
	}
	
	public function testAdminEditInstructionsNoData() {
		$this->ProgramInstructions->Component->initialize($this->ProgramInstructions);
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';			
		$result = $this->testAction('/admin/program_instructions/edit/1', array('data' => ''));
		$expectedResult = array('data'=>array('success'=>FALSE,'message'=>'Unable to save instructions.'));
		$this->assertEqual($result, $expectedResult);
	}	
	
	public function endTest() {
		Configure::write('debug', 2);
		unset($_SERVER['HTTP_X_REQUESTED_WITH']);		
		unset($this->ProgramInstructions);
		ClassRegistry::flush();
	}
}
?>