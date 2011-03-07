<?php
/* DocumentFilingCategories Test cases generated on: 2010-10-19 17:10:54 : 1287509754*/
App::import('Controller', 'DocumentFilingCategories');
App::import('Lib', 'AtlasTestCase');
class TestDocumentFilingCategoriesController extends DocumentFilingCategoriesController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class DocumentFilingCategoriesControllerTestCase extends AtlasTestCase {
	function startTest() {
		$this->DocumentFilingCategories =& new TestDocumentFilingCategoriesController(array('components' => array('RequestHandler')));
		$this->DocumentFilingCategories->constructClasses();
		$this->RequestHandler =& $this->DocumentFilingCategories->RequestHandler;
	}

	function endTest() {
		unset($this->DocumentFilingCategories);
		ClassRegistry::flush();
	}

	function testAdminIndex() {
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$this->DocumentFilingCategories->params = Router::parse('/admin/document_filing_categories/');
		$this->DocumentFilingCategories->params['form'] =  array('node' => 'source');		
		$this->DocumentFilingCategories->Component->initialize($this->DocumentFilingCategories);
		$this->DocumentFilingCategories->beforeFilter();
		$this->DocumentFilingCategories->Component->startup($this->DocumentFilingCategories);
		$result = json_decode($this->DocumentFilingCategories->admin_index(), true);
		$this->assertEqual($this->DocumentFilingCategories->layout, $this->RequestHandler->ajaxLayout);
		$this->assertTrue(array_key_exists('success', $result));
		//$this->assertTrue($result['success'], 'true');		
	}

}
?>