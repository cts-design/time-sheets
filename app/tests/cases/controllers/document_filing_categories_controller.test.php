<?php
/* DocumentFilingCategories Test cases generated on: 2010-10-19 17:10:54 : 1287509754*/
App::import('Controller', 'DocumentFilingCategories');

class TestDocumentFilingCategoriesController extends DocumentFilingCategoriesController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class DocumentFilingCategoriesControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.document_filing_category');

	function startTest() {
		$this->DocumentFilingCategories =& new TestDocumentFilingCategoriesController();
		$this->DocumentFilingCategories->constructClasses();
	}

	function endTest() {
		unset($this->DocumentFilingCategories);
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