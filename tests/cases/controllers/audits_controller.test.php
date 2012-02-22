<?php

App::import('Controller', 'Audits');
App::import('Lib', 'AtlasTestCase');
class TestAuditsController extends AuditsController {
	public $autoRender = false;

	public function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class AuditsControllerTestCase extends AtlasTestCase {
		
	public function startTest() {
		$this->Audits =& new TestAuditsController();
		$this->Audits->constructClasses();
        $this->Audits->params['controller'] = 'audits';
        $this->Audits->params['pass'] = array();
        $this->Audits->params['named'] = array();	
		$this->testController = $this->Audits;	
	}

	public function testAdminRead() {
		$this->Audits->Component->initialize($this->Audits);	
		$this->Audits->Session->write('Auth.User', array(
	        'id' => 2,
	        'role_id' => 2,
	        'username' => 'bcordell',
	        'location_id' => 1
	    ));

		$expectedResult = array(
            'data' => array (
                'audits'=>array(
                    array(
                        'id' => 1,
                        'name' => 'Audit 1',
                        'start_date' => '2012-12-01',
                        'end_date' => '2012-12-31',
                        'disabled' => 0,
                        'created' => '2012-12-01 08:00:00',
                        'modified' => '2012-12-01 08:00:00'
                    )
                ),
                'success' => true
            )
        );

        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$result = $this->testAction('/admin/audits/read', array('method' => 'get'));
		$this->assertEqual($expectedResult, $result);
	}

    public function testAdminCreate() {
		$this->Audits->Component->initialize($this->Audits);	
		$this->Audits->Session->write('Auth.User', array(
	        'id' => 2,
	        'role_id' => 2,
	        'username' => 'bcordell',
	        'location_id' => 1
	    ));
    }
		
	public function endTest() {
		Configure::write('debug', 2);
		unset($_SERVER['HTTP_X_REQUESTED_WITH']);		
		unset($this->Audits);
		ClassRegistry::flush();
	}	

}
