<?php
/* DocumentQueueCategories Test cases generated on: 2010-11-05 19:11:14 : 1288984814*/
App::import('Controller', 'DocumentQueueCategories');

class TestDocumentQueueCategoriesController extends DocumentQueueCategoriesController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class DocumentQueueCategoriesControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.document_queue_category');

	function startTest() {
		$this->DocumentQueueCategories =& new TestDocumentQueueCategoriesController();
		$this->DocumentQueueCategories->constructClasses();
	}

	function endTest() {
		unset($this->DocumentQueueCategories);
		ClassRegistry::flush();
	}

	function testAdminIndex() {

	}

	function testAdminView() {

	}

	function testAdminAdd() {

	}

	function testAdminEdit() {

	}

	function testAdminDelete() {

	}

}
?>