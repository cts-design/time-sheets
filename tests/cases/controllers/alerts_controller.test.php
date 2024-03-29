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
							'id' => '17',
							'name' => 'Test',
							'user_id' => '2',
							'watched_id' => '22',
							'detail' => NULL,
							'type' => 'Self Sign',
							'location_id' => '1',
							'send_email' => '1',
							'disabled' => 1,
							'created' => '2011-12-12 16:11:38',
							'modified' => '2011-12-13 14:04:52'
						),				
						array(
							'id' => '20',
							'name' => 'Vets',
							'user_id' => '2',
							'watched_id' => '10',
							'detail' => NULL,
							'type' => 'Self Sign',
							'location_id' => '1',
							'send_email' => '1',
							'disabled' => 0,
							'created' => '2011-12-13 11:54:05',
							'modified' => '2011-12-13 14:05:06'
						),
						array(
							'id' => '33',
							'name' => 'Test 1 More Time',
							'user_id' => '2',
							'watched_id' => '9',
							'detail' => NULL,
							'type' => 'Self Scan',
							'location_id' => '1',
							'send_email' => '0',
							'disabled' => 0,
							'created' => '2011-12-28 11:39:03',
							'modified' => '2011-12-28 11:39:03'
						),
						array(
							'id' => '34',
							'name' => 'Ya',
							'user_id' => '2',
							'watched_id' => '9',
							'detail' => NULL,
							'type' => 'Self Scan',
							'location_id' => '1',
							'send_email' => '1',
							'disabled' => 0,
							'created' => '2011-12-28 11:39:40',
							'modified' => '2011-12-30 08:58:37'
						),
						array(
							'id' => '35',
							'name' => 'Test ',
							'user_id' => '2',
							'watched_id' => '36',
							'detail' => NULL,
							'type' => 'Self Scan',
							'location_id' => '1',
							'send_email' => '0',
							'disabled' => 0,
							'created' => '2011-12-28 13:33:00',
							'modified' => '2011-12-28 13:33:00'
						),						
						array(
							'id' => '38',
							'name' => 'Test Cus Filed Doc',
							'user_id' => '2',
							'watched_id' => '9',
							'detail' => NULL,
							'type' => 'Customer Filed Document',
							'location_id' => NULL,
							'send_email' => '1',
							'disabled' => 0,
							'created' => '2011-12-30 15:35:30',
							'modified' => '2011-12-30 15:35:30'
						),
						array(
							'id' => '40',
							'name' => 'Test Detail',
							'user_id' => '2',
							'watched_id' => '0',
							'detail' => 'veteran',
							'type' => 'Customer Details',
							'location_id' => NULL,
							'send_email' => '1',
							'disabled' => 0,
							'created' => '2012-01-09 09:49:31',
							'modified' => '2012-01-09 09:55:03'
						),
						array(
							'id' => '41',
							'name' => 'Test Login Alert',
							'user_id' => '2',
							'watched_id' => '9',
							'detail' => NULL,
							'type' => 'Customer Login',
							'location_id' => NULL,
							'send_email' => '1',
							'disabled' => 0,
							'created' => '2012-01-10 13:09:14',
							'modified' => '2012-01-10 13:09:14'
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
		$this->assertEqual(42, $alert['Alert']['id']);
		$this->assertEqual('Test Alert Add', $alert['Alert']['name']);			
	}
	
	
	public function testAdminAddCustomerDetailsAlert() {
		$this->Alerts->Component->initialize($this->Alerts);	
		$this->Alerts->Session->write('Auth.User', array(
	        'id' => 2,
	        'role_id' => 2,
	        'username' => 'dnolan',
	        'location_id' => 1
	    ));
		$data = array(
			'name' => 'Cutomer Detail Alert',
			'type' => 'customer_detail',
			'send_email' => 1,
			'detail' => 'veteran'
		);
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$result = $this->testAction('/admin/alerts/add_customer_details_alert/', 
			array('method' => 'post', 'form_data' => $data));
		$id = $this->Alerts->Alert->getLastInsertId();
		$alert = $this->Alerts->Alert->read(null, $id);
		$this->assertEqual(42, $alert['Alert']['id']);
		$this->assertEqual('Cutomer Detail Alert', $alert['Alert']['name']);			
	}

	public function testAdminAddSelfScanAlert() {
		$this->Alerts->Component->initialize($this->Alerts);	
		$this->Alerts->Session->write('Auth.User', array(
	        'id' => 2,
	        'role_id' => 2,
	        'username' => 'dnolan',
	        'location_id' => 1
	    ));
		$data = array(
			'name' => 'Self Scan Alert',
			'type' => 'self_scan',
			'send_email' => 1,
			'location' => 1,
			'firstname' => 9
		);
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$result = $this->testAction('/admin/alerts/add_self_scan_alert/', 
			array('method' => 'post', 'form_data' => $data));
		$id = $this->Alerts->Alert->getLastInsertId();
		$alert = $this->Alerts->Alert->read(null, $id);
		$this->assertEqual(42, $alert['Alert']['id']);
		$this->assertEqual('Self Scan Alert', $alert['Alert']['name']);			
	}
	
	
	public function testAdminAddCusFiledDocAlert() {
		$this->Alerts->Component->initialize($this->Alerts);	
		$this->Alerts->Session->write('Auth.User', array(
	        'id' => 2,
	        'role_id' => 2,
	        'username' => 'dnolan',
	        'location_id' => 1
	    ));
		$data = array(
			'name' => 'Cus Filed Doc',
			'type' => 'customer_filed_document',
			'send_email' => 1,
			'firstname' => 9
		);
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$result = $this->testAction('/admin/alerts/add_cus_filed_doc_alert/', 
			array('method' => 'post', 'form_data' => $data));
		$id = $this->Alerts->Alert->getLastInsertId();
		$alert = $this->Alerts->Alert->read(null, $id);
		$this->assertEqual(42, $alert['Alert']['id']);
		$this->assertEqual('Cus Filed Doc', $alert['Alert']['name']);			
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
			'id' => '20'
			);
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$result = $this->testAction('/admin/alerts/toggle_email/', 
			array('method' => 'post', 'form_data' => $data));
		$alert = $this->Alerts->Alert->read(null, 20);
		$this->assertEqual(1, $alert['Alert']['send_email']);			
	}

	public function testAdminAddCustomerLoginAlert() {
		$this->Alerts->Component->initialize($this->Alerts);	
		$this->Alerts->Session->write('Auth.User', array(
	        'id' => 2,
	        'role_id' => 2,
	        'username' => 'dnolan',
	        'location_id' => 1
	    ));
		$data = array(
			'name' => 'Cus Login Alert',
			'type' => 'customer_login',
			'send_email' => 1,
			'firstname' => 9
		);
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$result = $this->testAction('/admin/alerts/add_customer_login_alert/', 
			array('method' => 'post', 'form_data' => $data));
		$id = $this->Alerts->Alert->getLastInsertId();
		$alert = $this->Alerts->Alert->read(null, $id);
		$this->assertEqual(42, $alert['Alert']['id']);
		$this->assertEqual('Cus Login Alert', $alert['Alert']['name']);			
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
			'id' => '20'
			);
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$result = $this->testAction('/admin/alerts/toggle_disabled/', 
			array('method' => 'post', 'form_data' => $data));
		$alert = $this->Alerts->Alert->read(null, 20);
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
						'label' => 'Self Sign'),
					array(
						'id' => 'customerDetailsAlertFromPanel',
						'label' => 'Customer Details'),
					array(
						'label' => 'Self Scan',
						'id' => 'selfScanAlertFormPanel'), 
					array(
						'label' => 'Customer Filed Document',
						'id' => 'cusFiledDocAlertFormPanel'),
					array(
						'label' => 'Customer Login',
						'id' => 'customerLoginAlertFormPanel'
					))));
															
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