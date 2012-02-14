<?php
/* QueuedDocuments Test cases generated on: 2010-11-08 13:11:02 : 1289221982*/
App::import('Controller', 'QueuedDocuments');
App::import('Lib', 'AtlasTestCase');
App::import('Component', 'Email');
Mock::generate('EmailComponent');
class TestQueuedDocumentsController extends QueuedDocumentsController {
	public $autoRender = false;

	public function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class QueuedDocumentsControllerTestCase extends AtlasTestCase {
	public function startTest() {
		$this->QueuedDocuments =& new TestQueuedDocumentsController();
		$this->QueuedDocuments->constructClasses();
        $this->QueuedDocuments->params['controller'] = 'queued_documents';
        $this->QueuedDocuments->params['pass'] = array();
        $this->QueuedDocuments->params['named'] = array();	
		$this->testController = $this->QueuedDocuments;
	}

	public function testAdminIndex() {
		$this->QueuedDocuments->Component->initialize($this->QueuedDocuments);	
		$this->QueuedDocuments->Session->write('Auth.User', array(
	        'id' => 2,
	        'role_id' => 2,
	        'username' => 'dnolan',
	        'location_id' => 1
	    ));
	    $expectedResult = array('canFile' => 1, 'canDelete' => 1, 'canReassign' => 1, 'canAddCustomer' => 1);
		$result = $this->testAction('/admin/queued_documents/index/', array('method' => 'get'));
		$this->assertEqual($expectedResult, $result);	
	}	

	public function testAdminIndexAjax() {
		$this->QueuedDocuments->Component->initialize($this->QueuedDocuments);	
		$this->QueuedDocuments->Session->write('Auth.User', array(
	        'id' => 2,
	        'role_id' => 2,
	        'username' => 'dnolan',
	        'location_id' => 1
	    ));

		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$result = $this->testAction('/admin/queued_documents/index/', array('method' => 'get'));
		$this->assertEqual($result['data']['totalCount'], 2);	
	}

	public function testAdminLockDocumentAjax() {
		$this->QueuedDocuments->Component->initialize($this->QueuedDocuments);	
		$this->QueuedDocuments->Session->write('Auth.User', array(
	        'id' => 2,
	        'role_id' => 2,
	        'username' => 'dnolan',
	        'location_id' => 1
	    ));
	    $data['doc_id'] = '48';
	    $expectedResult  = '48'; 
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$result = $this->testAction('/admin/queued_documents/lock_document/', array('form_data' => $data, 'method' => 'post'));	   
		$this->assertEqual($result['data']['QueuedDocument']['id'], $expectedResult); 
	}

	public function testAdminReassignQueueAjax() {
		$this->QueuedDocuments->Component->initialize($this->QueuedDocuments);	
		$this->QueuedDocuments->Session->write('Auth.User', array(
	        'id' => 2,
	        'role_id' => 2,
	        'username' => 'dnolan',
	        'location_id' => 1
	    ));
	    $data['id'] = '48';
	    $data['queue_category_id'] = 2;
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$result = $this->testAction('/admin/queued_documents/reassign_queue/', array('form_data' => $data, 'method' => 'post'));
		$this->assertTrue($result['data']['success']); 		
	}

	public function testAdminView() {
		$this->QueuedDocuments->Component->initialize($this->QueuedDocuments);	
		$this->QueuedDocuments->Session->write('Auth.User', array(
	        'id' => 2,
	        'role_id' => 2,
	        'username' => 'dnolan',
	        'location_id' => 1
	    ));
	    $expectedResult = array(
	    	    'id' => '201112300934197126580.pdf',
			    'name' => '201112300934197126580',
			    'extension' => 'pdf',
			    'cache' => '1',
			    'path' => '/storage/2011/12/'
	    );
		$result = $this->testAction('/admin/queued_documents/view/48', array('method' => 'get'));
		$this->assertEqual($expectedResult, $result);			
	}

	public function testAdminFileDocument() {
		$this->QueuedDocuments->Component->initialize($this->QueuedDocuments);	
		$this->QueuedDocuments->Session->write('Auth.User', array(
	        'id' => 2,
	        'role_id' => 2,
	        'username' => 'dnolan',
	        'location_id' => 1
	    ));
	    $this->QueuedDocuments->Email = new MockEmailComponent();
	    $this->QueuedDocuments->Email->enabled = true;
	    $data = array('id' => 49, 'user_id' => 9, 'cat_1' => 10);
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$result = $this->testAction('/admin/queued_documents/file_document/', 
			array('method' => 'post', 'form_data' => $data));
		$this->assertTrue($result['data']['success']); 				
	}

	public function testAdminFileDocumentReQueue() {
		$this->QueuedDocuments->Component->initialize($this->QueuedDocuments);	
		$this->QueuedDocuments->Session->write('Auth.User', array(
	        'id' => 2,
	        'role_id' => 2,
	        'username' => 'dnolan',
	        'location_id' => 1
	    ));
	    $this->QueuedDocuments->Email = new MockEmailComponent();
	    $this->QueuedDocuments->Email->enabled = true;
	    $data = array('id' => 49, 'user_id' => 9, 'cat_1' => 10, 'requeue' => 1);
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$result = $this->testAction('/admin/queued_documents/file_document/', 
			array('method' => 'post', 'form_data' => $data));
		$this->assertTrue($result['data']['success']); 
		$this->assertEqual($result['data']['locked'], 50);				
	}	

	public function testAdminUnlockDocument() {
		$this->QueuedDocuments->Component->initialize($this->QueuedDocuments);	
		$this->QueuedDocuments->Session->write('Auth.User', array(
	        'id' => 2,
	        'role_id' => 2,
	        'username' => 'dnolan',
	        'location_id' => 1
	    ));
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$result = $this->testAction('/admin/queued_documents/unlock_document/', array('method' => 'get'));
		$this->assertTrue($result['data']['success']); 		
	}
 
	public function testAdminDelete() {
		$this->QueuedDocuments->Component->initialize($this->QueuedDocuments);	
		$this->QueuedDocuments->Session->write('Auth.User', array(
	        'id' => 2,
	        'role_id' => 2,
	        'username' => 'dnolan',
	        'location_id' => 1
	    ));
	    $data['id'] = 48;
	    $data['deleted_reason'] = 'Not Readable';
	    $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$result = $this->testAction('/admin/queued_documents/delete', array('method' => 'post', 'form_data' => $data));
		$this->assertTrue($result['data']['success']);			
	}	



	public function endTest() {
		Configure::write('debug', 2);
		unset($_SERVER['HTTP_X_REQUESTED_WITH']);		
		unset($this->QueuedDocuments);
		ClassRegistry::flush();
	}

}