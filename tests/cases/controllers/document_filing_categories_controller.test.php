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
    	$this->DocumentFilingCategories->Session->write('Auth.User', array(
	        'id' => 3,
			'role_id' => 3,
	        'username' => 'tester',
	    	));
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
		$this->assertTrue($this->RequestHandler->isAjax());
		unset($_SERVER['HTTP_X_REQUESTED_WITH']);
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
		$this->assertTrue($this->RequestHandler->isAjax());
		unset($_SERVER['HTTP_X_REQUESTED_WITH']);
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
		$this->assertTrue($this->RequestHandler->isAjax());
		unset($_SERVER['HTTP_X_REQUESTED_WITH']);
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
		$this->assertTrue($this->RequestHandler->isAjax());
		unset($_SERVER['HTTP_X_REQUESTED_WITH']);
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
		$this->assertTrue($this->RequestHandler->isAjax());
		unset($_SERVER['HTTP_X_REQUESTED_WITH']);
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
		$this->assertTrue($this->RequestHandler->isAjax());
		unset($_SERVER['HTTP_X_REQUESTED_WITH']);
		$this->assertTrue(array_key_exists('success', $result));
		$this->assertFalse($result['success']);	
	}
	
	function testAdminReorderCategoriesAjaxMoveUp() {
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$this->DocumentFilingCategories->params = Router::parse('/admin/document_filing_categories/reorder_categories');
		$this->DocumentFilingCategories->params['form'] = array('node' => '4', 'delta' => '-1');
		$this->DocumentFilingCategories->Component->initialize($this->DocumentFilingCategories);
		$this->DocumentFilingCategories->beforeFilter();
		$this->DocumentFilingCategories->Component->startup($this->DocumentFilingCategories);
		$result = json_decode($this->DocumentFilingCategories->admin_reorder_categories(), true);
		$this->assertTrue($this->RequestHandler->isAjax());
		unset($_SERVER['HTTP_X_REQUESTED_WITH']);
		$this->assertTrue(array_key_exists('success', $result));
		$this->assertTrue($result['success']);			
	}
	
	function testAdminReorderCategoriesAjaxMoveDown() {
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$this->DocumentFilingCategories->params = Router::parse('/admin/document_filing_categories/reorder_categories');
		$this->DocumentFilingCategories->params['form'] = array('node' => '4', 'delta' => '1');
		$this->DocumentFilingCategories->Component->initialize($this->DocumentFilingCategories);
		$this->DocumentFilingCategories->beforeFilter();
		$this->DocumentFilingCategories->Component->startup($this->DocumentFilingCategories);
		$result = json_decode($this->DocumentFilingCategories->admin_reorder_categories(), true);
		$this->assertTrue($this->RequestHandler->isAjax());
		unset($_SERVER['HTTP_X_REQUESTED_WITH']);
		$this->assertTrue(array_key_exists('success', $result));
		$this->assertTrue($result['success']);			
	}
	
	function testAdminReorderCategoriesBadData(){
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$this->DocumentFilingCategories->params = Router::parse('/admin/document_filing_categories/reorder_categories');
		$this->DocumentFilingCategories->params['form'] = array('tode' => '4', 'wow' => '1');
		$this->DocumentFilingCategories->Component->initialize($this->DocumentFilingCategories);
		$this->DocumentFilingCategories->beforeFilter();
		$this->DocumentFilingCategories->Component->startup($this->DocumentFilingCategories);
		$result = json_decode($this->DocumentFilingCategories->admin_reorder_categories(), true);
		$this->assertTrue($this->RequestHandler->isAjax());
		unset($_SERVER['HTTP_X_REQUESTED_WITH']);
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
		$this->assertTrue($this->RequestHandler->isAjax());
		unset($_SERVER['HTTP_X_REQUESTED_WITH']);;
		$this->assertTrue(array_key_exists('success', $result));
		$this->assertTrue($result['success']);			
	}
	
	function testAdminReparentCategories0Position() {
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$this->DocumentFilingCategories->params = Router::parse('/admin/document_filing_categories/reparent_categories');
		$this->DocumentFilingCategories->params['form'] = array('node' => '4', 'parent' => '3', 'position' => '0');
		$this->DocumentFilingCategories->Component->initialize($this->DocumentFilingCategories);
		$this->DocumentFilingCategories->beforeFilter();
		$this->DocumentFilingCategories->Component->startup($this->DocumentFilingCategories);
		$result = json_decode($this->DocumentFilingCategories->admin_reparent_categories(), true);
		$this->assertTrue($this->RequestHandler->isAjax());
		unset($_SERVER['HTTP_X_REQUESTED_WITH']);
		$this->assertTrue(array_key_exists('success', $result));
		$this->assertTrue($result['success']);			
	}

	function testAdminReparentCategoriesDeltaGreaterThan0() {
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$this->DocumentFilingCategories->params = Router::parse('/admin/document_filing_categories/reparent_categories');
		$this->DocumentFilingCategories->params['form'] = array('node' => '5', 'parent' => '3', 'position' => '1');
		$this->DocumentFilingCategories->Component->initialize($this->DocumentFilingCategories);
		$this->DocumentFilingCategories->beforeFilter();
		$this->DocumentFilingCategories->Component->startup($this->DocumentFilingCategories);
		$result = json_decode($this->DocumentFilingCategories->admin_reparent_categories(), true);
		$this->assertTrue($this->RequestHandler->isAjax());
		unset($_SERVER['HTTP_X_REQUESTED_WITH']);
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
		$this->assertTrue($this->RequestHandler->isAjax());
		unset($_SERVER['HTTP_X_REQUESTED_WITH']);
		$this->assertTrue(array_key_exists('success', $result));
		$this->assertFalse($result['success']);			
	}
	
	function testAdminEdit() { 
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$this->DocumentFilingCategories->params = Router::parse('/admin/document_filing_categories/edit');
		$this->DocumentFilingCategories->data = array('DocumentFilingCategory' => array('id' => '4', 'name' => 'Test Cat 2'));
		$this->DocumentFilingCategories->Component->initialize($this->DocumentFilingCategories);
		$this->DocumentFilingCategories->beforeFilter();
		$this->DocumentFilingCategories->Component->startup($this->DocumentFilingCategories);
		$result = json_decode($this->DocumentFilingCategories->admin_edit(), true);																	
		$this->assertTrue($this->RequestHandler->isAjax());
		unset($_SERVER['HTTP_X_REQUESTED_WITH']);
		$this->assertTrue(array_key_exists('success', $result));
		$this->assertTrue($result['success']);			
	}
	
	function testAdminEditArrayEmpty() { 
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$this->DocumentFilingCategories->params = Router::parse('/admin/document_filing_categories/edit');
		$this->DocumentFilingCategories->data = array();
		$this->DocumentFilingCategories->Component->initialize($this->DocumentFilingCategories);
		$this->DocumentFilingCategories->beforeFilter();
		$this->DocumentFilingCategories->Component->startup($this->DocumentFilingCategories);
		$result = json_decode($this->DocumentFilingCategories->admin_edit(), true);																	
		$this->assertTrue($this->RequestHandler->isAjax());
		unset($_SERVER['HTTP_X_REQUESTED_WITH']);
		$this->assertTrue(array_key_exists('success', $result));
		$this->assertFalse($result['success']);			
	}
		
	function testAdminToggleDisabled() {
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$this->DocumentFilingCategories->params = Router::parse('/admin/document_filing_categories/toggle_disabled');
		$this->DocumentFilingCategories->data = array('DocumentFilingCategory' => array('id' => 6, 'disabled' => 1));
		$this->DocumentFilingCategories->Component->initialize($this->DocumentFilingCategories);
		$this->DocumentFilingCategories->beforeFilter();
		$this->DocumentFilingCategories->Component->startup($this->DocumentFilingCategories);
		$result = json_decode($this->DocumentFilingCategories->admin_toggle_disabled(), true);																
		$this->assertTrue($this->RequestHandler->isAjax());
		unset($_SERVER['HTTP_X_REQUESTED_WITH']);
		$this->assertTrue(array_key_exists('success', $result));
		$this->assertTrue($result['success']);			
	}

	function testAdminToggleDisabledEnabled() {
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$this->DocumentFilingCategories->params = Router::parse('/admin/document_filing_categories/toggle_disabled');
		$this->DocumentFilingCategories->data = array('DocumentFilingCategory' => array('id' => 6, 'disabled' => 0));
		$this->DocumentFilingCategories->Component->initialize($this->DocumentFilingCategories);
		$this->DocumentFilingCategories->beforeFilter();
		$this->DocumentFilingCategories->Component->startup($this->DocumentFilingCategories);
		$result = json_decode($this->DocumentFilingCategories->admin_toggle_disabled(), true);																
		$this->assertTrue($this->RequestHandler->isAjax());
		unset($_SERVER['HTTP_X_REQUESTED_WITH']);
		$this->assertTrue(array_key_exists('success', $result));
		$this->assertTrue($result['success']);			
	}

	function testAdminToggleDisabledParentWithChildren() {
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$this->DocumentFilingCategories->params = Router::parse('/admin/document_filing_categories/toggle_disabled');
		$this->DocumentFilingCategories->data = array('DocumentFilingCategory' => array('id' => 1, 'disabled' => 1));
		$this->DocumentFilingCategories->Component->initialize($this->DocumentFilingCategories);
		$this->DocumentFilingCategories->beforeFilter();
		$this->DocumentFilingCategories->Component->startup($this->DocumentFilingCategories);
		$result = json_decode($this->DocumentFilingCategories->admin_toggle_disabled(), true);																
		$this->assertTrue($this->RequestHandler->isAjax());
		unset($_SERVER['HTTP_X_REQUESTED_WITH']);
		$this->assertTrue(array_key_exists('success', $result));
		$this->assertFalse($result['success']);			
	}
		
	function testAdminToggleDisabledRoot() {
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$this->DocumentFilingCategories->params = Router::parse('/admin/document_filing_categories/toggle_disabled');
		$this->DocumentFilingCategories->data = array('DocumentFilingCategory' => array('id' => 'source', 'disabled' => 1));
		$this->DocumentFilingCategories->Component->initialize($this->DocumentFilingCategories);
		$this->DocumentFilingCategories->beforeFilter();
		$this->DocumentFilingCategories->Component->startup($this->DocumentFilingCategories);
		$result = json_decode($this->DocumentFilingCategories->admin_toggle_disabled(), true);																
		$this->assertTrue($this->RequestHandler->isAjax());
		unset($_SERVER['HTTP_X_REQUESTED_WITH']);
		$this->assertTrue(array_key_exists('success', $result));
		$this->assertFalse($result['success']);			
	}
	
	function testAdminToggleDisabledEnableChildOfDiasabledParent() {
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$this->DocumentFilingCategories->params = Router::parse('/admin/document_filing_categories/toggle_disabled');
		$this->DocumentFilingCategories->data = array('DocumentFilingCategory' => array('id' => '7', 'disabled' => 0));
		$this->DocumentFilingCategories->Component->initialize($this->DocumentFilingCategories);
		$this->DocumentFilingCategories->beforeFilter();
		$this->DocumentFilingCategories->Component->startup($this->DocumentFilingCategories);
		$result = json_decode($this->DocumentFilingCategories->admin_toggle_disabled(), true);																
		$this->assertTrue($this->RequestHandler->isAjax());
		unset($_SERVER['HTTP_X_REQUESTED_WITH']);
		$this->assertTrue(array_key_exists('success', $result));
		$this->assertFalse($result['success']);			
	}
	
	function testAdminGetChildCats() {
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$this->DocumentFilingCategories->params = Router::parse('/admin/document_filing_categories/get_child_cats');
		$this->DocumentFilingCategories->params['url'] = array('id' => '1');
		$this->DocumentFilingCategories->Component->initialize($this->DocumentFilingCategories);
		$this->DocumentFilingCategories->beforeFilter();
		$this->DocumentFilingCategories->Component->startup($this->DocumentFilingCategories);
		$result = json_decode($this->DocumentFilingCategories->admin_get_child_cats(), true);																
		$this->assertTrue($this->RequestHandler->isAjax());
		unset($_SERVER['HTTP_X_REQUESTED_WITH']);
		$this->assertTrue(count($result) > 0);			
	}
	
	function testAdminGetGrandchildCats() {
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$this->DocumentFilingCategories->params = Router::parse('/admin/document_filing_categories/get_grand_child_cats');
		$this->DocumentFilingCategories->params['url'] = array('id' => '3');
		$this->DocumentFilingCategories->Component->initialize($this->DocumentFilingCategories);
		$this->DocumentFilingCategories->beforeFilter();
		$this->DocumentFilingCategories->Component->startup($this->DocumentFilingCategories);
		$result = json_decode($this->DocumentFilingCategories->admin_get_grand_child_cats(), true);																
		$this->assertTrue($this->RequestHandler->isAjax());
		unset($_SERVER['HTTP_X_REQUESTED_WITH']);
		$this->assertTrue(count($result) > 0);			
	}	
	
}
?>