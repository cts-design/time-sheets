<?php

App::import('Controller', 'Alerts');
App::import('Lib', 'AtlasTestCase');
class TestAlertsController extends AlertsController {
	public $autoRender = false;

	public function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class AlertsControllerTestCase extends AtlasTestCase {
		
	public function startTest() {
		$this->Alerts =& new TestAlertsController();
		$this->Alerts->constructClasses();
        $this->Alerts->params['controller'] = 'alerts';
        $this->Alerts->params['pass'] = array();
        $this->Alerts->params['named'] = array();	
		$this->testController = $this->Alerts;	
	}

	public function testAdminIndexAjax() {
		$this->Alerts->Component->initialize($this->Alerts);	
		$this->Alerts->Session->write('Auth.User', array(
	        'id' => 2,
	        'role_id' => 2,
	        'username' => 'dnolan',
	        'location_id' => 1
	    ));
		$expectedResult = array(
			'data' => array (
				'alerts'=>array(
					array(
						'id' => '20',
						'name' => 'Vets',
						'user_id' => '2',
						'watched_id' => '10',
						'type' => 'Self Sign',
						'location_id' => '1',
						'send_email' => true,
						'disabled' => false,
						'created' => '2011-12-13 11:54:05',
						'modified' => '2011-12-13 14:05:06'
					),
					array(
						'id' => '17',
						'name' => 'Test',
						'user_id' => '2',
						'watched_id' => '22',
						'type' => 'Self Sign',
						'location_id' => '1',
						'send_email' => true,
						'disabled' => true,
						'created' => '2011-12-12 16:11:38',
						'modified' => '2011-12-13 14:04:52'
					)				
				)
			, 'success' => true));
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$result = $this->testAction('/admin/alerts/index/', array('method' => 'get'));
		$this->assertEqual($expectedResult, $result);
	}

	public function testAdminAddSelfSignAlert() {
		$this->Alerts->Component->initialize($this->Alerts);	
		$this->Alerts->Session->write('Auth.User', array(
	        'id' => 2,
	        'role_id' => 2,
	        'username' => 'dnolan',
	        'location_id' => 1
	    ));
		$data = array(
			'name' => 'Test Alert Add',
			'type' => 'self_sign',
			'send_email' => 1,
			'level1' => 10
			);
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$result = $this->testAction('/admin/alerts/add_self_sign_alert/', 
			array('method' => 'post', 'form_data' => $data));
		$id = $this->Alerts->Alert->getLastInsertId();
		$alert = $this->Alerts->Alert->read(null, $id);
		$this->assertEqual(23, $alert['Alert']['id']);
		$this->assertEqual('Test Alert Add', $alert['Alert']['name']);			
	}
	
	
	public function testAdminToggleEmail() {
		$this->Alerts->Component->initialize($this->Alerts);	
		$this->Alerts->Session->write('Auth.User', array(
	        'id' => 2,
	        'role_id' => 2,
	        'username' => 'dnolan',
	        'location_id' => 1
	    ));
		$data = array(
			'send_email' => 'true',
			'id' => 2
			);
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$result = $this->testAction('/admin/alerts/toggle_email/', 
			array('method' => 'post', 'form_data' => $data));
		$alert = $this->Alerts->Alert->read(null, 2);
		$this->assertEqual(1, $alert['Alert']['send_email']);			
	}
	
	public function testAdminToggleDisabled() {
		$this->Alerts->Component->initialize($this->Alerts);	
		$this->Alerts->Session->write('Auth.User', array(
	        'id' => 2,
	        'role_id' => 2,
	        'username' => 'dnolan',
	        'location_id' => 1
	    ));
		$data = array(
			'disabled' => 'true',
			'id' => 2
			);
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$result = $this->testAction('/admin/alerts/toggle_disabled/', 
			array('method' => 'post', 'form_data' => $data));
		$alert = $this->Alerts->Alert->read(null, 2);
		$this->assertEqual(1, $alert['Alert']['disabled']);			
	}

	public function testAdminGetAlertTypes() {
		$this->Alerts->Component->initialize($this->Alerts);	
		$this->Alerts->Session->write('Auth.User', array(
	        'id' => 2,
	        'role_id' => 2,
	        'username' => 'dnolan',
	        'location_id' => 1
	    ));
		$expectedResult = array(
			'data' => array(
				'types' => array( 
					array(
						'id' => 'selfSignAlertFormPanel',
						'label' => 'Self Sign'))));
						
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$result = $this->testAction('/admin/alerts/get_alert_types/', 
			array('method' => 'get'));
		$this->assertEqual($result, $expectedResult);			
	}

	public function testAdminDelete() {
		$this->Alerts->Component->initialize($this->Alerts);	
		$this->Alerts->Session->write('Auth.User', array(
	        'id' => 2,
	        'role_id' => 2,
	        'username' => 'dnolan',
	        'location_id' => 1
	    ));
		
		$data = array('id' => '20');
		
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$result = $this->testAction('/admin/alerts/delete/', 
			array('method' => 'post', 'form_data' => $data));	
		$this->assertTrue($result['data']['success']);			
	}
		
	public function endTest() {
		Configure::write('debug', 2);
		unset($_SERVER['HTTP_X_REQUESTED_WITH']);		
		unset($this->Alerts);
		ClassRegistry::flush();
	}	

}