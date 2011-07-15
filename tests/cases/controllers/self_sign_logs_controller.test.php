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

	
	function endTest() {
		Configure::write('debug', 2);
		unset($_SERVER['HTTP_X_REQUESTED_WITH']);
		unset($this->SelfSignLogs);
		ClassRegistry::flush();
	}

}
?>