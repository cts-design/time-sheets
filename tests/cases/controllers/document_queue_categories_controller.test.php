<?php
/* DocumentQueueCategories Test cases generated on: 2010-11-05 19:11:14 : 1288984814*/
App::import('Controller', 'DocumentQueueCategories');
App::import('Lib', 'AtlasTestCase');
class TestDocumentQueueCategoriesController extends DocumentQueueCategoriesController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class DocumentQueueCategoriesControllerTestCase extends AtlasTestCase {
	function startTest() {
		$this->DocumentQueueCategories =& new TestDocumentQueueCategoriesController();
		$this->DocumentQueueCategories->constructClasses();
		$this->DocumentQueueCategories->Component->initialize($this->DocumentQueueCategories);
		$this->DocumentQueueCategories->Session->write('Auth.User', array(
			'id' => 1,
			'username' => 'bcordell'
		));
	}

	function endTest() {
		unset($this->DocumentQueueCategories);
		ClassRegistry::flush();
	}

	function testAdminAddWithValidRecord() {
		$this->DocumentQueueCategories->data = array(
			'DocumentQueueCategory' => array(
				'ftp_path' => '/ftp/path/to/filess',
				'name' => 'Name3',
				'deleted' => 0
			),
		);
		
		$this->DocumentQueueCategories->params = Router::parse('/admin/document_queue_categories/add');
		$this->DocumentQueueCategories->beforeFilter();
		$this->DocumentQueueCategories->Component->startup($this->DocumentQueueCategories);
		$this->DocumentQueueCategories->admin_add();
		
		$this->assertFlashMessage($this->DocumentQueueCategories, 'The document queue category has been saved', 'flash_success');
	}
	
	function testAdminAddWithInvalidRecord() {
		$this->DocumentQueueCategories->data = array(
			'DocumentQueueCategory' => array(
				'ftp_path' => '',
				'name' => 'Name3',
				'deleted' => 0
			),
		);
		
		$this->DocumentQueueCategories->params = Router::parse('/admin/document_queue_categories/add');
		$this->DocumentQueueCategories->beforeFilter();
		$this->DocumentQueueCategories->Component->startup($this->DocumentQueueCategories);
		$this->DocumentQueueCategories->admin_add();
		
		$this->assertFlashMessage($this->DocumentQueueCategories, 'The document queue category could not be saved. Please, try again.', 'flash_failure');
	}

	function testAdminEditWithNoId() {
		$this->DocumentQueueCategories->params = Router::parse('/admin/document_queue_categories/edit/');
		$this->DocumentQueueCategories->beforeFilter();
		$this->DocumentQueueCategories->Component->startup($this->DocumentQueueCategories);
		$this->DocumentQueueCategories->admin_edit();
		
		$this->assertFlashMessage($this->DocumentQueueCategories, 'Invalid document queue category', 'flash_failure');		
	}

	function testAdminEdit() {
		$this->DocumentQueueCategories->data = array(
			'DocumentQueueCategory' => array(
				'id' => 1,
				'ftp_path' => '/ftp/path/to/file',
				'name' => 'New Name',
				'deleted' => 1,
				'created' => '2010-11-05 19:19:36',
				'modified' => '2010-11-05 19:19:36'
			),
		);
		
		$this->DocumentQueueCategories->params = Router::parse('/admin/document_queue_categories/edit/1');
		$this->DocumentQueueCategories->beforeFilter();
		$this->DocumentQueueCategories->Component->startup($this->DocumentQueueCategories);
		$this->DocumentQueueCategories->admin_edit();
		
		// was the record changed?
		$result = $this->DocumentQueueCategories->DocumentQueueCategory->read(null, 1);
		$this->assertEqual($result['DocumentQueueCategory']['name'], 'New Name');
		$this->assertFlashMessage($this->DocumentQueueCategories, 'The document queue category has been saved', 'flash_success');
	}

	function testAdminDelete() {
		$this->DocumentQueueCategories->params = Router::parse('/admin/document_queue_categories/delete/2');
		$this->DocumentQueueCategories->beforeFilter();
		$this->DocumentQueueCategories->DocumentQueueCategory->recursive = -1;
		$this->DocumentQueueCategories->Component->startup($this->DocumentQueueCategories);
		$this->DocumentQueueCategories->admin_delete(2);
		
		$result = $this->DocumentQueueCategories->DocumentQueueCategory->read(null, 2);
		
		// was the record changed?
		$this->assertEqual($result['DocumentQueueCategory']['deleted'], 1);	
	}

}
?>