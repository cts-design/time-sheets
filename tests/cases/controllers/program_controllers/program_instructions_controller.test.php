<?php
/* ProgramInstructions Test cases generated on: 2011-08-11 13:27:14 : 1313083634*/
App::import('Controller', 'ProgramInstructions');
App::import('Lib', 'AtlasTestCase');
class TestProgramInstructionsController extends ProgramInstructionsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class ProgramInstructionsControllerTestCase extends AtlasTestCase {
	var $fixtures = array('app.program_instruction', 'app.program', 'app.program_field', 'app.program_response', 'app.user', 'app.role', 'app.location', 'app.ftp_document_scanner', 'app.kiosk', 'app.kiosk_button', 'app.master_kiosk_button', 'app.self_sign_log', 'app.self_sign_log_archive', 'app.queued_document', 'app.document_queue_category', 'app.self_scan_category', 'app.document_filing_category', 'app.watched_filing_cat', 'app.user_transaction', 'app.filed_document', 'app.program_response_doc', 'app.program_email', 'app.program_paper_form');

	function startTest() {
		$this->ProgramInstructions =& new TestProgramInstructionsController();
		$this->ProgramInstructions->constructClasses();
	}

	function endTest() {
		unset($this->ProgramInstructions);
		ClassRegistry::flush();
	}

	public function testAdminInstructionsIndex() {
		$this->Programs->Component->initialize($this->Programs);
		$result = $this->testAction('/admin/programs/instructions_index', array('method' => 'get'));	
		$this->assertEqual($result['title_for_layout'], 'Program Instructions');
	}
	
	public function testAdminEditInstructionsBadData() {		
		$this->Programs->params = Router::parse('/admin/programs/edit_instructions/1');
		$this->Programs->Component->initialize($this->Programs);
		$this->Programs->Session->write('Auth.User', array(
	        'id' => 10,
	        'role_id' => 1,
	        'username' => 'test'
	    ));
		$this->Programs->data = array();
		$this->mockAcl($this->Programs);		
		$this->Programs->beforeFilter();		
	    $this->Programs->Component->startup($this->Programs);			
		$this->Programs->admin_edit_instructions(1);
		$this->assertEqual($this->Programs->Session->read('Message.flash.element'), 'flash_failure');
		$this->Programs->Session->destroy();	
	}

	public function testAdminEditInstructionsNoId() {		
		$this->Programs->params = Router::parse('/admin/programs/edit_instructions/');
		$this->Programs->Component->initialize($this->Programs);
		$this->Programs->Session->write('Auth.User', array(
	        'id' => 10,
	        'role_id' => 1,
	        'username' => 'test'
	    ));
		$this->mockAcl($this->Programs);		
		$this->Programs->beforeFilter();		
	    $this->Programs->Component->startup($this->Programs);			
		$this->Programs->admin_edit_instructions();
		$this->assertEqual($this->Programs->Session->read('Message.flash.element'), 'flash_failure');
		$this->Programs->Session->destroy();	
	}
	
	public function testAdminEditInstructions() {
		$this->Programs->Component->initialize($this->Programs);
		
        $data = array(
	        'ProgramInstruction' => array(
	            'id' => 1,
	            'text' => 'Updated instructions text',
	            'program_id' => 1,
	            'type' => 'main'			
			)
        );			
		$result = $this->testAction('/admin/programs/edit_instructions/1', 
			array('data' => $data));
		$instructions = $this->Programs->Program->ProgramInstruction->read(null, 1);
		$this->assertEqual($instructions['ProgramInstruction']['text'], 'Updated instructions text');		
		$this->assertEqual($result['title_for_layout'], 'Edit Main Instructions');
	}

}
?>