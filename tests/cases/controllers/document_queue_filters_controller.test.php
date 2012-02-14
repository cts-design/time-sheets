<?php
/* DocumentQueueFilters Test cases generated on: 2012-01-19 10:48:04 : 1326988084*/
App::import('Controller', 'DocumentQueueFilters');
App::import('Lib', 'AtlasTestCase');
class TestDocumentQueueFiltersController extends DocumentQueueFiltersController {
	public $autoRender = false;

	public function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class DocumentQueueFiltersControllerTestCase extends AtlasTestCase {

	public function startTest() {
		$this->DocumentQueueFilters =& new TestDocumentQueueFiltersController();
		$this->DocumentQueueFilters->constructClasses();
        $this->DocumentQueueFilters->params['controller'] = 'document_queue_filters';
        $this->DocumentQueueFilters->params['pass'] = array();
        $this->DocumentQueueFilters->params['named'] = array();	
		$this->testController = $this->DocumentQueueFilters;
	}

	public function testAdminSetFilters() {
		$this->DocumentQueueFilters->Component->initialize($this->DocumentQueueFilters);	
		$this->DocumentQueueFilters->Session->write('Auth.User', array(
	        'id' => 2,
	        'role_id' => 2,
	        'username' => 'dnolan',
	        'location_id' => 1
	    ));
	    $data = array('id' => 2, 'locations' => '["1","2"]');
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$result = $this->testAction('/admin/document_queue_filters/set_filters/', 
			array('method' => 'post', 'form_data' => $data));
		$this->assertTrue($result['data']['success']); 			
	}

	public function testAdminGetFilters() {
		$this->DocumentQueueFilters->Component->initialize($this->DocumentQueueFilters);	
		$this->DocumentQueueFilters->Session->write('Auth.User', array(
	        'id' => 1,
	        'role_id' => 2,
	        'username' => 'bcordell',
	        'location_id' => 1
	    ));
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$result = $this->testAction('/admin/document_queue_filters/get_filters/', 
			array('method' => 'get'));
		$this->assertTrue($result['data']['success']); 
		$this->assertEqual($result['data']['filters']['id'], 2); 			
	}

	public function endTest() {
		Configure::write('debug', 2);
		unset($_SERVER['HTTP_X_REQUESTED_WITH']);		
		unset($this->DocumentQueueFilters);
		ClassRegistry::flush();
	}

}