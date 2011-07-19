<?php
/* SelfSignLogs Test cases generated on: 2010-09-30 15:09:39 : 1285861959*/
App::import('Controller', 'SelfSignLogs');
App::import('Lib', 'AtlasTestCase');
class TestSelfSignLogsController extends SelfSignLogsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class SelfSignLogsControllerTestCase extends AtlasTestCase {
		
	function startTest() {
		$this->SelfSignLogs =& new TestSelfSignLogsController();
		$this->SelfSignLogs->constructClasses();
        $this->SelfSignLogs->params['controller'] = 'self_sign_logs';
        $this->SelfSignLogs->params['pass'] = array();
        $this->SelfSignLogs->params['named'] = array();	
		$this->testController = $this->SelfSignLogs;
	}

	function testAdminIndex() {
		$this->SelfSignLogs->Component->initialize($this->SelfSignLogs);
		$this->SelfSignLogs->Session->write('Auth.User', array(
	        'id' => 2,
	        'role_id' => 2,
	        'username' => 'dnolan'
	    ));
		$expectedResult = array(
		'title_for_layout' => 'Self Sign Queue',
		'data' => 
			array(
				'results' => 1,
				'success' => TRUE,
				'logs' => array(
					0 => array(
						'id' => 1,
						'status' => 'Open',
						'lastname' => 'Smith',
						'firstname' => 'Daniel',
						'last4' => 1234,
						'service' => 'Orientations',
						'created' => '2111-07-25 09:50:24',
						'admin' => ' ',
						'location' => 'Citrus',
						'kioskId' => 2))));
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$result = $this->testAction('/admin/self_sign_logs/index', array('method' => 'get'));
		$this->assertEqual($expectedResult, $result);		
	}
	
	
	function testAdminIndexWithLocation() {
		$this->SelfSignLogs->params = Router::parse('/admin/self_sign_logs/index/');	
		$data['locations'] = 1;
		$this->SelfSignLogs->Component->initialize($this->SelfSignLogs);
		
		$this->SelfSignLogs->Session->write('Auth.User', array(
	        'id' => 2,
	        'role_id' => 2,
	        'username' => 'dnolan'
	    ));	
		$expectedResult = array(
			'title_for_layout' => 'Self Sign Queue',
			'data' => 
				array(
					'results' => 1,
					'success' => TRUE,
					'logs' => array(
						0 => array(
							'id' => 1,
							'status' => 'Open',
							'lastname' => 'Smith',
							'firstname' => 'Daniel',
							'last4' => 1234,
							'service' => 'Orientations',
							'created' => '2111-07-25 09:50:24',
							'admin' => ' ',
							'location' => 'Citrus',
							'kioskId' => 2))));
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$result = $this->testAction('/admin/self_sign_logs/index/', array('method' => 'get', 'data' => $data));
		$this->assertEqual($expectedResult, $result);
	}
	
	function testAdminIndexWithService() {
		$this->SelfSignLogs->params = Router::parse('/admin/self_sign_logs/index/');	
		$data['services'] = '14,12';
		$this->SelfSignLogs->Component->initialize($this->SelfSignLogs);
		
		$this->SelfSignLogs->Session->write('Auth.User', array(
	        'id' => 2,
	        'role_id' => 2,
	        'username' => 'dnolan',
	        'location_id' => 1
	    ));	
		$expectedResult = array(
			'title_for_layout' => 'Self Sign Queue',
			'data' => 
				array(
					'results' => 1,
					'success' => TRUE,
					'logs' => array(
						0 => array(
							'id' => 1,
							'status' => 'Open',
							'lastname' => 'Smith',
							'firstname' => 'Daniel',
							'last4' => 1234,
							'service' => 'Orientations',
							'created' => '2111-07-25 09:50:24',
							'admin' => ' ',
							'location' => 'Citrus',
							'kioskId' => 2))));
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$result = $this->testAction('/admin/self_sign_logs/index/', array('method' => 'get', 'data' => $data));
		$this->assertEqual($expectedResult, $result);
	}	

	function testAdminUpdateStatus() {
		$this->SelfSignLogs->params = Router::parse('/admin/self_sign_logs/update_status/1/2');	
		$this->SelfSignLogs->Component->initialize($this->SelfSignLogs);	
		$this->SelfSignLogs->Session->write('Auth.User', array(
	        'id' => 2,
	        'role_id' => 2,
	        'username' => 'dnolan',
	        'location_id' => 1
	    ));
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$testedAction = $this->testAction('/admin/self_sign_logs/update_status/1/2', array('method' => 'get'));
		$result = $this->SelfSignLogs->SelfSignLog->findById(1);
		$this->assertEqual($result['SelfSignLog']['status'], 2);
		
		$testedAction = $this->testAction('/admin/self_sign_logs/update_status/1/0', array('method' => 'get'));
		$result = $this->SelfSignLogs->SelfSignLog->findById(1);
		$this->assertEqual($result['SelfSignLog']['status'], 0);	
		
		$testedAction = $this->testAction('/admin/self_sign_logs/update_status/1/1', array('method' => 'get'));
		$result = $this->SelfSignLogs->SelfSignLog->findById(1);
		$this->assertEqual($result['SelfSignLog']['status'], 1);	
	}

	function testAdminReassign() {
		$this->SelfSignLogs->params = Router::parse('/admin/self_sign_logs/reassign');	
		$this->SelfSignLogs->Component->initialize($this->SelfSignLogs);	
		$this->SelfSignLogs->Session->write('Auth.User', array(
	        'id' => 2,
	        'role_id' => 2,
	        'username' => 'dnolan',
	        'location_id' => 1
	    ));
		$data = array(
			'SelfSignLog' => array(
				'id' => 1,
				'level_1' => 14,
				'level_2' => 24,
				'level_3' => 36
			)
		);
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$testedAction = $this->testAction('/admin/self_sign_logs/reassign', array(
			'method' => 'post', 'data' => $data));
		$result = $this->SelfSignLogs->SelfSignLog->findById(1);
		$this->assertTrue($testedAction['data']['success']);
		$this->assertEqual($result['SelfSignLog']['id'], 1);
		$this->assertEqual($result['SelfSignLog']['level_1'], 14);
		$this->assertEqual($result['SelfSignLog']['level_2'], 24);
		$this->assertEqual($result['SelfSignLog']['level_3'], 36);		
	}
	
	function testAdminGetServices() {
		$this->SelfSignLogs->params = Router::parse('/admin/self_sign_logs/get_services/');
		$data['locations'] = '1,2';	
		$this->SelfSignLogs->Component->initialize($this->SelfSignLogs);	
		$this->SelfSignLogs->Session->write('Auth.User', array(
	        'id' => 2,
	        'role_id' => 2,
	        'username' => 'dnolan',
	        'location_id' => 1
	    ));
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$result = $this->testAction('/admin/self_sign_logs/get_services/1', array(
			'method' => 'get',
			'data' => $data));
		$expectedResult = array(
			'data' => array(
				'services' => array(
					0 => array(
						'id' => 1,
						'name' => 'Cash Assistance & Food Stamps'
					),
					1 => array(
						'id' => 10,
						'name' => 'Veteran Services'
					),
					2 => array(
						'id' => 45,
						'name' => 'Scan Documents'
					),
					3 => array(
						'id' => 14,
						'name' => 'Orientations'
					),
					4 => array(
						'id' => 6,
						'name' => 'Look For A Job'
					),
					5 => array(
						'id' => 73,
						'name' => 'Register To Win A Kindle'
					)
				)
			)
		);	
		$this->assertEqual($expectedResult, $result);				
	}

	function testAdminGetKioskButtons() {
		$this->SelfSignLogs->params = Router::parse('/admin/self_sign_logs/get_kiosk_buttons/2');	
		$this->SelfSignLogs->Component->initialize($this->SelfSignLogs);		
		$this->SelfSignLogs->Session->write('Auth.User', array(
	        'id' => 2,
	        'role_id' => 2,
	        'username' => 'dnolan'
	    ));
		$expectedResult = array(
			'data' => array(
				'buttons' => array(
					0 => array(
						'id' => 1,
						'name' => 'Cash Assistance & Food Stamps'
					),
					1 => array(
						'id' => 10,
						'name' => 'Veteran Services'
					),
					2 => array(
						'id' => 45,
						'name' => 'Scan Documents'
					),
					3 => array(
						'id' => 14,
						'name' => 'Orientations'
					),
					4 => array(
						'id' => 6,
						'name' => 'Look For A Job'
					),
					5 => array(
						'id' => 73,
						'name' => 'Register To Win A Kindle'
					)
				)
			)
		);		
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$result = $this->testAction('/admin/self_sign_logs/get_kiosk_buttons/2', array('method' => 'get'));
		$this->assertEqual($result, $expectedResult);
	}

	function testAdminGetKioskButtonsWithParentId() {
		$this->SelfSignLogs->params = Router::parse('/admin/self_sign_logs/get_kiosk_buttons/2/14');	
		$this->SelfSignLogs->Component->initialize($this->SelfSignLogs);		
		$this->SelfSignLogs->Session->write('Auth.User', array(
	        'id' => 2,
	        'role_id' => 2,
	        'username' => 'dnolan'
	    ));	
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$result = $this->testAction('/admin/self_sign_logs/get_kiosk_buttons/2/14', array('method' => 'get'));
		$expectedResult = array(
			'data' => array(
				'buttons' => array(
					0 => array(
						'id' => 26,
						'name' => 'Cash Assistance (WTP)'
					)
				)
			)
		);			
		$this->assertEqual($result, $expectedResult);
	}
	
	function endTest() {
		Configure::write('debug', 2);
		unset($_SERVER['HTTP_X_REQUESTED_WITH']);
		unset($this->SelfSignLogs);
		ClassRegistry::flush();
	}

}
?>