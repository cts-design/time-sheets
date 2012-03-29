<?php

App::import('Vendor', 'chromephp/chromephp');
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

        $data = array(
            'audits' => array(
                'name' => 'Test Audit Create',
                'start_date' => '2012-03-01',
                'end_date' => '2012-03-10',
                'auditors' => 10,
                'customers' => '113441234\n333441234\n443441244\n552441234'
            )
        );

        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
        $result = $this->testAction('/admin/audits/create', array('method' => 'post', 'form_data' => $data));

        $this->assertTrue($result['data']['success']);
        $this->assertEqual($result['data']['message'], 'Audit added successfully');

        // bind habtm table
        $id = $this->Audits->Audit->getLastInsertId();
        $audit = $this->Audits->Audit->read(null, $id);

        $this->assertEqual(3, $audit['Audit']['id']);
        $this->assertEqual('Test Audit Create', $audit['Audit']['name']);			
        $this->assertEqual('2012-03-01', $audit['Audit']['start_date']);			
        $this->assertEqual('2012-03-10', $audit['Audit']['end_date']);			

        // testing habtm
        $expectedCustomerIds = array(12, 14, 15, 16);
        $savedCustomerIds = Set::extract('/User/id', $audit);

        $this->assertEqual(count($audit['User']), 4);
        $this->assertEqual($savedCustomerIds, $expectedCustomerIds);

        // test failing method
        $data = array(
            'audits' => array(
                'name' => '',
                'start_date' => '',
                'end_date' => '',
                'customers' => ''
            )
        );
        $result = $this->testAction('/admin/audits/create', array('method' => 'post', 'form_data' => $data));
        $this->assertFalse($result['data']['success']);
        $this->assertEqual($result['data']['message'], 'Unable to add audit, please try again');
    }
		
	public function endTest() {
		Configure::write('debug', 2);
		unset($_SERVER['HTTP_X_REQUESTED_WITH']);		
		unset($this->Audits);
		ClassRegistry::flush();
	}	

}
