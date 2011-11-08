<?php
/* Settings Test cases generated on: 2011-11-01 17:02:42 : 1320181362*/
App::import('Controller', 'Settings');
App::import('Lib', 'AtlasTestCase');
class TestSettingsController extends SettingsController {
	public $autoRender = false;

	public function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class SettingsControllerTestCase extends AtlasTestCase {
		
	public function startTest() {
		$this->Settings =& new TestSettingsController();
		$this->Settings->constructClasses();
        $this->Settings->params['controller'] = 'settings';
        $this->Settings->params['pass'] = array();
        $this->Settings->params['named'] = array();	
		$this->testController = $this->Settings;	
	}

	public function testAdminIndexAjax() {
		$this->Settings->Component->initialize($this->Settings);	
		$this->Settings->Session->write('Auth.User', array(
	        'id' => 2,
	        'role_id' => 2,
	        'username' => 'dnolan',
	        'location_id' => 1
	    ));
		$expectedResult = array(
			'settings'=>array(
				array(
					'Setting'=>array(
						'id'=>'1',
						'module'=>'SelfSign',
						'name'=>'KioskRegistration',
						'value'=>'[{"field":"phone"},{"field":"race"},{"field":"address_1"}]',
						'created'=>'2011-11-01 17:01:04',
						'modified'=>'2011-11-01 17:01:04'
					)
				)
			)
		);
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$result = $this->testAction('/admin/settings/index/', array('method' => 'get'));
		$this->assertEqual($expectedResult, $result);
	}
	
	public function testAdminKioskRegistraionGet(){
		$this->Settings->Component->initialize($this->Settings);	
		$this->Settings->Session->write('Auth.User', array(
	        'id' => 2,
	        'role_id' => 2,
	        'username' => 'dnolan',
	        'location_id' => 1
	    ));	
		$expectedResult = array(
			'data'=>array(
				'fields'=>array(
					array(
						'field'=>'phone'
					),
					array(
						'field'=>'race'
					),
					array(
						'field'=>'address_1'
					)
				)
			)
		);
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$result = $this->testAction('/admin/settings/kiosk_registration/', array('method' => 'get'));		
		$this->assertEqual($expectedResult, $result);
	}



	public function testAdminKioskRegistraionPost(){
		$this->Settings->Component->initialize($this->Settings);	
		$this->Settings->Session->write('Auth.User', array(
	        'id' => 2,
	        'role_id' => 2,
	        'username' => 'dnolan',
	        'location_id' => 1
	    ));	
		$data['fields'] =  'race, address_1';
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$result = $this->testAction('/admin/settings/kiosk_registration/', 
			array('form_data' => $data, 'method' => 'post'));
		$expectedResult	 = 	array(
			'Setting'=>array(
				'id'=>'1',
				'value'=>'[{"field":"race"},{"field":" address_1"}]'
				)
			);
		$settings = $this->Settings->Setting->findByName('KioskRegistration', array('id','value'));
		
		$this->assertEqual($expectedResult, $settings);
	}
	
	
	public function endTest() {
		Configure::write('debug', 2);
		unset($_SERVER['HTTP_X_REQUESTED_WITH']);		
		unset($this->Settings);
		ClassRegistry::flush();
	}	

}
?>