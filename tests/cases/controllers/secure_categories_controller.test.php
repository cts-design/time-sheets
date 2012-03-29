<?php

App::import('Controller', 'SecureCategories');
App::import('Lib', 'AtlasTestCase');
class TestSecureCategoriesController extends SecureCategoriesController {
	public $autoRender = false;

	public function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class SecureCategoriesControllerTestCase extends AtlasTestCase {
	

	public function startTest() {
		$this->SecureCategories =& new TestSecureCategoriesController();
		$this->SecureCategories->constructClasses();
        $this->SecureCategories->params['controller'] = 'bar_code_definitions';
        $this->SecureCategories->params['pass'] = array();
        $this->SecureCategories->params['named'] = array();	
		$this->testController = $this->SecureCategories;	
	}

	public function testAdminGetSecureFilingCatsAjax() {
		$this->SecureCategories->Component->initialize($this->SecureCategories);	
		$this->SecureCategories->Session->write('Auth.User', array(
	        'id' => 2,
	        'role_id' => 2,
	        'username' => 'dnolan',
	        'location_id' => 1
	    ));
		$expectedResult = array('data' => array(
			'cats' => array(0 => array('id' => 33, 'category' => 'YouthBuild', 'secure_admins' => array(0 => 2)))));
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$result = $this->testAction('/admin/secure_categories/get_secure_filing_cats/', array('method' => 'get'));
		$this->assertEqual($expectedResult, $result);
	}

	public function testAdminGetSecureQueueCatsAjax() {
		$this->SecureCategories->Component->initialize($this->SecureCategories);	
		$this->SecureCategories->Session->write('Auth.User', array(
	        'id' => 2,
	        'role_id' => 2,
	        'username' => 'dnolan',
	        'location_id' => 1
	    ));
		$expectedResult = array('data' => array(
			'cats' => array(0 => array('id' => 2, 'category' => 'Name2', 'secure_admins' => array(0 => 2)))));
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$result = $this->testAction('/admin/secure_categories/get_secure_queue_cats/', array('method' => 'get'));
		$this->assertEqual($expectedResult, $result);
	}	
	
	public function testAdminUpdateSecureFilingCatAjax() {
		$this->SecureCategories->Component->initialize($this->SecureCategories);	
		$this->SecureCategories->Session->write('Auth.User', array(
	        'id' => 2,
	        'role_id' => 2,
	        'username' => 'dnolan',
	        'location_id' => 1
	    ));
		$data['id'] = 33;
		$data['secure_admins'] = json_encode(array(1,2));
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$result = $this->testAction('/admin/secure_categories/update_filing_cat/', 
			array( 'form_data' => $data, 'method' => 'post'));
		$expectedResult = array('data'=>array('success'=> TRUE, 'message'=>'Filing category updated successfully'));
		$this->assertEqual($expectedResult, $result);
	}

	public function testAdminUpdateSecureFilingCatNoDataAjax() {
		$this->SecureCategories->Component->initialize($this->SecureCategories);	
		$this->SecureCategories->Session->write('Auth.User', array(
	        'id' => 2,
	        'role_id' => 2,
	        'username' => 'dnolan',
	        'location_id' => 1
	    ));
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$result = $this->testAction('/admin/secure_categories/update_filing_cat/', 
			array('method' => 'post'));
		$expectedResult = array('data'=>array('success'=> FALSE, 'message'=>'An error has occurred'));
		$this->assertEqual($expectedResult, $result);
	}		
	
	public function testAdminUpdateSecureQueueCatAjax() {
		$this->SecureCategories->Component->initialize($this->SecureCategories);	
		$this->SecureCategories->Session->write('Auth.User', array(
	        'id' => 2,
	        'role_id' => 2,
	        'username' => 'dnolan',
	        'location_id' => 1
	    ));
		$data['id'] = 2;
		$data['secure_admins'] = json_encode(array(1,2));
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$result = $this->testAction('/admin/secure_categories/update_queue_cat/', 
			array( 'form_data' => $data, 'method' => 'post'));
		$expectedResult = array('data'=>array('success'=> TRUE, 'message'=>'Queue category updated successfully'));
		$this->assertEqual($expectedResult, $result);
	}

	public function testAdminUpdateSecureQueueCatNoDataAjax() {
		$this->SecureCategories->Component->initialize($this->SecureCategories);	
		$this->SecureCategories->Session->write('Auth.User', array(
	        'id' => 2,
	        'role_id' => 2,
	        'username' => 'dnolan',
	        'location_id' => 1
	    ));
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$result = $this->testAction('/admin/secure_categories/update_queue_cat/', 
			array('method' => 'post'));
		$expectedResult = array('data'=>array('success'=> FALSE, 'message'=>'An error has occurred'));
		$this->assertEqual($expectedResult, $result);
	}		


	public function endTest() {
		Configure::write('debug', 2);
		unset($_SERVER['HTTP_X_REQUESTED_WITH']);		
		unset($this->SecureCategories);
		ClassRegistry::flush();
	}	

}