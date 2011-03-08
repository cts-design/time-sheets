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
		unset($_SERVER['HTTP_X_REQUESTED_WITH']);
		$this->assertEqual($this->DocumentFilingCategories->layout, $this->RequestHandler->ajaxLayout);
		$this->assertTrue(array_key_exists('success', $result[0]));
		$this->assertTrue($result[0]['success']);		
	}
	
	function testAdminIndexGetChildNode(){
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$this->DocumentFilingCategories->params = Router::parse('/admin/document_filing_categories/');
		$this->DocumentFilingCategories->params['form'] =  array('node' => '1');		
		$this->DocumentFilingCategories->Component->initialize($this->DocumentFilingCategories);
		$this->DocumentFilingCategories->beforeFilter();
		$this->DocumentFilingCategories->Component->startup($this->DocumentFilingCategories);
		$result = json_decode($this->DocumentFilingCategories->admin_index(), true);
		unset($_SERVER['HTTP_X_REQUESTED_WITH']);
		$this->assertEqual($this->DocumentFilingCategories->layout, $this->RequestHandler->ajaxLayout);
		$this->assertTrue(array_key_exists('success', $result[0]));
		$this->assertTrue($result[0]['success']);		
		
	}
	
	function testAdminIndexNoResults(){
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$this->DocumentFilingCategories->params = Router::parse('/admin/document_filing_categories/');
		$this->DocumentFilingCategories->params['form'] =  array('node' => '10');		
		$this->DocumentFilingCategories->Component->initialize($this->DocumentFilingCategories);
		$this->DocumentFilingCategories->beforeFilter();
		$this->DocumentFilingCategories->Component->startup($this->DocumentFilingCategories);
		$result = json_decode($this->DocumentFilingCategories->admin_index(), true);
		unset($_SERVER['HTTP_X_REQUESTED_WITH']);
		$this->assertEqual($this->DocumentFilingCategories->layout, $this->RequestHandler->ajaxLayout);
		$this->assertTrue(array_key_exists('success', $result));
		$this->assertFalse($result['success']);		
		
	}
	
	function testAdminAdd() {
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$this->DocumentFilingCategories->params = Router::parse('/admin/document_filing_categories/add');
		$this->DocumentFilingCategories->params['form']['parentId'] = 'source';
		$this->DocumentFilingCategories->params['form']['catName'] = 'Test';
		$this->DocumentFilingCategories->params['form']['parentPath'] = '/source';	
		$this->DocumentFilingCategories->Component->initialize($this->DocumentFilingCategories);
		$this->DocumentFilingCategories->beforeFilter();
		$this->DocumentFilingCategories->Component->startup($this->DocumentFilingCategories);
		$result = json_decode($this->DocumentFilingCategories->admin_add(), true);																
		$this->assertEqual($this->DocumentFilingCategories->layout, $this->RequestHandler->ajaxLayout);
		$this->assertTrue(array_key_exists('success', $result));
		$this->assertTrue($result['success']);	
	}
	
	function testAdminAddChildTooDeep() {
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$this->DocumentFilingCategories->params = Router::parse('/admin/document_filing_categories/add');
		$this->DocumentFilingCategories->params['form']['parentId'] = '4';
		$this->DocumentFilingCategories->params['form']['catName'] = 'Test';
		$this->DocumentFilingCategories->params['form']['parentPath'] = '/source/1/3';	
		$this->DocumentFilingCategories->Component->initialize($this->DocumentFilingCategories);
		$this->DocumentFilingCategories->beforeFilter();
		$this->DocumentFilingCategories->Component->startup($this->DocumentFilingCategories);
		$result = json_decode($this->DocumentFilingCategories->admin_add(), true);
		unset($_SERVER['HTTP_X_REQUESTED_WITH']);																
		$this->assertEqual($this->DocumentFilingCategories->layout, $this->RequestHandler->ajaxLayout);
		$this->assertTrue(array_key_exists('success', $result));
		$this->assertFalse($result['success']);	
	}
	
	function testAdminAddBadData() {
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$this->DocumentFilingCategories->params = Router::parse('/admin/document_filing_categories/add');
		$this->DocumentFilingCategories->params['form'] = array('parentId' => 'FOO', 'catName' => 'Test', 'parentPath' => '/source');
		$this->DocumentFilingCategories->Component->initialize($this->DocumentFilingCategories);
		$this->DocumentFilingCategories->beforeFilter();
		$this->DocumentFilingCategories->Component->startup($this->DocumentFilingCategories);
		$result = json_decode($this->DocumentFilingCategories->admin_add(), true);
		unset($_SERVER['HTTP_X_REQUESTED_WITH']);																
		$this->assertEqual($this->DocumentFilingCategories->layout, $this->RequestHandler->ajaxLayout);
		$this->assertTrue(array_key_exists('success', $result));
		$this->assertFalse($result['success']);	
	}
	
	function testAdminReorderCategoriesAjaxMoveUp() {
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$this->DocumentFilingCategories->params = Router::parse('/admin/document_filing_categories/reorder_categories_ajax');
		$this->DocumentFilingCategories->params['form'] = array('node' => '4', 'delta' => '-1');
		$this->DocumentFilingCategories->Component->initialize($this->DocumentFilingCategories);
		$this->DocumentFilingCategories->beforeFilter();
		$this->DocumentFilingCategories->Component->startup($this->DocumentFilingCategories);
		$result = json_decode($this->DocumentFilingCategories->admin_reorder_categories_ajax(), true);
		unset($_SERVER['HTTP_X_REQUESTED_WITH']);																
		$this->assertEqual($this->DocumentFilingCategories->layout, $this->RequestHandler->ajaxLayout);
		$this->assertTrue(array_key_exists('success', $result));
		$this->assertTrue($result['success']);			
	}
	
	function testAdminReorderCategoriesAjaxMoveDown() {
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$this->DocumentFilingCategories->params = Router::parse('/admin/document_filing_categories/reorder_categories_ajax');
		$this->DocumentFilingCategories->params['form'] = array('node' => '4', 'delta' => '1');
		$this->DocumentFilingCategories->Component->initialize($this->DocumentFilingCategories);
		$this->DocumentFilingCategories->beforeFilter();
		$this->DocumentFilingCategories->Component->startup($this->DocumentFilingCategories);
		$result = json_decode($this->DocumentFilingCategories->admin_reorder_categories_ajax(), true);
		unset($_SERVER['HTTP_X_REQUESTED_WITH']);																
		$this->assertEqual($this->DocumentFilingCategories->layout, $this->RequestHandler->ajaxLayout);
		$this->assertTrue(array_key_exists('success', $result));
		$this->assertTrue($result['success']);			
	}
	
	function testAdminReorderCategoriesBadData(){
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$this->DocumentFilingCategories->params = Router::parse('/admin/document_filing_categories/reorder_categories_ajax');
		$this->DocumentFilingCategories->params['form'] = array('tode' => '4', 'wow' => '1');
		$this->DocumentFilingCategories->Component->initialize($this->DocumentFilingCategories);
		$this->DocumentFilingCategories->beforeFilter();
		$this->DocumentFilingCategories->Component->startup($this->DocumentFilingCategories);
		$result = json_decode($this->DocumentFilingCategories->admin_reorder_categories_ajax(), true);
		unset($_SERVER['HTTP_X_REQUESTED_WITH']);																
		$this->assertEqual($this->DocumentFilingCategories->layout, $this->RequestHandler->ajaxLayout);
		$this->assertTrue(array_key_exists('success', $result));
		$this->assertFalse($result['success']);	
	}
	
	function testAdminReparentCategories() {
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$this->DocumentFilingCategories->params = Router::parse('/admin/document_filing_categories/reparent_categories');
		$this->DocumentFilingCategories->params['form'] = array('node' => '4', 'parent' => '3', 'position' => '1');
		$this->DocumentFilingCategories->Component->initialize($this->DocumentFilingCategories);
		$this->DocumentFilingCategories->beforeFilter();
		$this->DocumentFilingCategories->Component->startup($this->DocumentFilingCategories);
		$result = json_decode($this->DocumentFilingCategories->admin_reparent_categories(), true);
		unset($_SERVER['HTTP_X_REQUESTED_WITH']);																
		$this->assertEqual($this->DocumentFilingCategories->layout, $this->RequestHandler->ajaxLayout);
		$this->assertTrue(array_key_exists('success', $result));
		$this->assertTrue($result['success']);			
	}
	
	function testAdminReparentCategoriesBadData() {
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$this->DocumentFilingCategories->params = Router::parse('/admin/document_filing_categories/reparent_categories');
		$this->DocumentFilingCategories->params['form'] = array('chode' => '4', 'noob' => '3', 'wow' => '1');
		$this->DocumentFilingCategories->Component->initialize($this->DocumentFilingCategories);
		$this->DocumentFilingCategories->beforeFilter();
		$this->DocumentFilingCategories->Component->startup($this->DocumentFilingCategories);
		$result = json_decode($this->DocumentFilingCategories->admin_reparent_categories(), true);
		unset($_SERVER['HTTP_X_REQUESTED_WITH']);																
		$this->assertEqual($this->DocumentFilingCategories->layout, $this->RequestHandler->ajaxLayout);
		$this->assertTrue(array_key_exists('success', $result));
		$this->assertFalse($result['success']);			
	}
			
}
?>