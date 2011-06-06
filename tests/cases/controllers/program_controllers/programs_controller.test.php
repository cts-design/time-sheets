<?php
/* Programs Test cases generated on: 2011-03-29 12:34:28 : 1301402068*/
App::import('Controller', 'Programs');
App::import('Lib', 'AtlasTestCase');
class TestProgramsController extends ProgramsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class ProgramsControllerTestCase extends AtlasTestCase {
	
	function startTest() {
		$this->Programs = new TestProgramsController();
		$this->Programs->constructClasses();	
        $this->Programs->params['controller'] = 'programs';
        $this->Programs->params['pass'] = array();
        $this->Programs->params['named'] = array();	
	}
	
	function testIndexWithResponse() {
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
		$expectedResult = array('controller' => 'programs', 'action' => 'view_media', 0 => 1);
		$this->assertEqual($this->Programs->redirectUrl, $expectedResult );		
	}
	
	function testIndexDisabledProgram() {
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
	
	function testIndexNoId() {
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
	
	function testIndexViewedMedia() {
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
	
	function testIndexCompletedForm() {
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
	
	function testIndexUploadedDocs() {
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
	
	function testIndexDroppingOffDocs() {
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
	
	function testIndexResponseComplete() {
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

	function testGetStarted() {
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

		$this->assertEqual($result['ProgramResponse']['id'], 7);
		$this->assertEqual($result['ProgramResponse']['user_id'], 10);
		$this->assertEqual($this->Programs->redirectUrl, '/programs/view_media/1');
		$this->Programs->Session->destroy();	
		
	}
	
	function testViewMediaNoId() {		
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
	}	
	
	function endTest() {
		$this->Programs->Session->destroy();
		unset($this->Programs);
		ClassRegistry::flush();
	}	
}
?>