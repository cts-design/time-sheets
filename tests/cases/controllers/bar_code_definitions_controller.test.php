<?php
/* BarCodeDefinitions Test cases generated on: 2011-10-04 11:42:46 : 1317742966*/
App::import('Controller', 'BarCodeDefinitions');
App::import('Lib', 'AtlasTestCase');
class TestBarCodeDefinitionsController extends BarCodeDefinitionsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class BarCodeDefinitionsControllerTestCase extends AtlasTestCase {
	

	function startTest() {
		$this->BarCodeDefinitions =& new TestBarCodeDefinitionsController();
		$this->BarCodeDefinitions->constructClasses();
        $this->BarCodeDefinitions->params['controller'] = 'bar_code_definitions';
        $this->BarCodeDefinitions->params['pass'] = array();
        $this->BarCodeDefinitions->params['named'] = array();	
		$this->testController = $this->BarCodeDefinitions;	
	}

	function testAdminIndexAjax() {
		$this->BarCodeDefinitions->Component->initialize($this->BarCodeDefinitions);	
		$this->BarCodeDefinitions->Session->write('Auth.User', array(
	        'id' => 2,
	        'role_id' => 2,
	        'username' => 'dnolan',
	        'location_id' => 1
	    ));
		$expectedResult = array(
			'data'=>array(
				'definitions'=>array(
					array(
					'id'=>1,
					'name'=>'Lorem ipsum dolor sit amet',
					'number'=>'1',
					'Cat1-name'=>'Valid Category',
					'Cat2-name'=>'A Nested Valid Category',
					'Cat3-name'=>'Another Nested Category',
					'DocumentQueueCategory-name' => 'Name1'
				)
			),
			'total'=>1,
			'success'=>TRUE
		));
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$result = $this->testAction('/admin/bar_code_definitions/index/', array('method' => 'get'));
		$this->assertEqual($expectedResult, $result);
	}

	function testAdminAdd() {
		$this->BarCodeDefinitions->Component->initialize($this->BarCodeDefinitions);
		$this->BarCodeDefinitions->Session->write('Auth.User', array(
	        'id' => 2,
	        'role_id' => 2,
	        'username' => 'dnolan',
	        'location_id' => 1
	    ));
        $data = array(
	        'BarCodeDefinition' => array(
	            'name' => 'New Bar Code Definition',
	            'number' => 54321,
	            'document_queue_category_id' => 1,
	            'cat_1' => 1,
	            'cat_2' => 3,
	            'cat_3' => 4			
			)
        );
		$data['BarCodeDefinition'] = json_encode($data['BarCodeDefinition']);
		$expectedResult = array(
			'data'=>array(
				'definitions'=>array(
					'id'=>'2',
					'name'=>'New Bar Code Definition',
					'number'=>'54321',
					'cat_1'=>'1',
					'cat_2'=>'3',
					'cat_3'=>'4',
					'Cat1-name'=>'Valid Category',
					'Cat2-name'=>'A Nested Valid Category',
					'Cat3-name'=>'Another Nested Category',
					'DocumentQueueCategory-name'=>'Name1'
				),
			'success'=>TRUE,
			'total' => 2,
			'message'=>'Bar code definition added successfully.'
			)
		);
		
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';			
		$result = $this->testAction('/admin/bar_code_definitions/add', array('data' => $data));
		unset($result['data']['definitions']['created']);
		unset($result['data']['definitions']['document_queue_category_id']);
		unset($result['data']['definitions']['modified']);
		$this->assertEqual($expectedResult, $result);
	}

	function testAdminEdit() {
		$this->BarCodeDefinitions->Component->initialize($this->BarCodeDefinitions);
		$this->BarCodeDefinitions->Session->write('Auth.User', array(
	        'id' => 2,
	        'role_id' => 2,
	        'username' => 'dnolan',
	        'location_id' => 1
	    ));
        $data = array(
	        'BarCodeDefinition' => array(
	            'id' => 1,
	            'name' => 'Updated Bar Code Definition',
	            'number' => 12345,
	            'cat_1' => 3,
	            'cat_2' => 2,
	            'cat_3' => 1,
	            'document_queue_category_id' => 1			
			)
        );
		$data['BarCodeDefinition'] = json_encode($data['BarCodeDefinition']);
		$expectedResult = array(
			'data'=>array(
				'definitions'=>array(
					'id'=>'1',
					'name'=>'Updated Bar Code Definition',
					'number'=>'12345',
					'cat_1'=>'3',
					'cat_2'=>'2',
					'cat_3'=>'1',
					'created'=>'2011-10-04 11:42:00',
					'Cat1-name'=>'A Nested Valid Category',
					'Cat2-name'=>'Disabled Category',
					'Cat3-name'=>'Valid Category',
					'DocumentQueueCategory-name'=>'Name1'
				),
			'success'=>TRUE,
			'message'=>'Bar code definition updated successfully.'
			)
		);
		
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';			
		$result = $this->testAction('/admin/bar_code_definitions/edit', array('data' => $data));
		unset($result['data']['definitions']['modified']);
		unset($result['data']['definitions']['document_queue_category_id']);
		$this->assertEqual($expectedResult, $result);
	}

	function testAdminDelete() {
		$this->BarCodeDefinitions->Component->initialize($this->BarCodeDefinitions);
		$this->BarCodeDefinitions->Session->write('Auth.User', array(
	        'id' => 2,
	        'role_id' => 2,
	        'username' => 'dnolan',
	        'location_id' => 1
	    ));
        $data = array(
	        'BarCodeDefinition' => array(
	            'id' => 1		
			)
        );
		$expectedResult = array(
			'data' => array(
				'definitions' => array(),
				'success' => TRUE
			)
		);
		$data['BarCodeDefinition'] = json_encode($data['BarCodeDefinition']);	
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';			
		$result = $this->testAction('/admin/bar_code_definitions/delete', array('data' => $data));
		$this->assertEqual($expectedResult, $result);
	}
	
	function endTest() {
		Configure::write('debug', 2);
		unset($_SERVER['HTTP_X_REQUESTED_WITH']);		
		unset($this->BarCodeDefinitions);
		ClassRegistry::flush();
	}	

}
?>