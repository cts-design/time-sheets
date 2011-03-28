<?php
/* ChairmanReports Test cases generated on: 2011-02-09 18:14:25 : 1297275265*/
App::import('Controller', 'ChairmanReports');
App::import('Lib', 'AtlasTestCase');
class TestChairmanReportsController extends ChairmanReportsController {
	var $autoRender = false;

        function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class ChairmanReportsControllerTestCase extends AtlasTestCase {
	function startTest() {
		$this->ChairmanReports =& new TestChairmanReportsController();
		$this->ChairmanReports->constructClasses();
		$this->ChairmanReports->Session->write('Auth.User', array(
	        'id' => 2,
	        'username' => 'dnolan',
	        'role_id' => 2
	    ));
	}

	function endTest() {
		unset($this->ChairmanReports);
		ClassRegistry::flush();
	}

	function testIndex() {
        $result = $this->testAction('/chairman_reports/index', array('return' => 'vars'));
        $result = Set::extract('/chairmanReports/.[1]', $result);
		$expected = array(
			'id' => 1,
            'title' => 'Lorem ipsum dolor sit amet',
            'file' => 'http://atlas.dev/files/public/file.pdf',
            'created' => '2011-02-09 15:20:21',
            'modified' => '2011-02-09 15:20:21'
		);
		$this->assertEqual($result[0]['ChairmanReport'], $expected);
    }

	function testAdminIndex() {
            //$result = $this->testAction('/admin/chairman_reports/index', array('return' => 'vars'));
            //debug($result);
	}

	function testAdminEditWithValidData() {
			$this->ChairmanReports->Session->write('Auth.User', array(
		        'id' => 2,
		        'role_id' => 2,
		        'username' => 'dnolan',
		        'location_id' => 1
		    ));	
			
            $this->ChairmanReports->data = array(
                'ChairmanReport' => array(
                    'title' => 'Valid Title'
                )
            );
			
		    $this->ChairmanReports->params = Router::parse('/admin/chairman_reports/edit/1');
		    $this->ChairmanReports->beforeFilter();
		    $this->ChairmanReports->Component->startup($this->ChairmanReports);
			$this->ChairmanReports->admin_edit(1);	
			$this->assertEqual($this->ChairmanReports->Session->read('Message.flash.element'), 'flash_success');
	}

        function testAdminEditWithInvalidData() {
            $this->ChairmanReports->data = array(
                'ChairmanReport' => array(
                    'title' => 'Invalid Title!!'
                )
            );

		    $this->ChairmanReports->params = Router::parse('/admin/chairman_reports/edit/1');
		    $this->ChairmanReports->beforeFilter();
		    $this->ChairmanReports->Component->startup($this->ChairmanReports);
			$this->ChairmanReports->admin_edit(1);	
			$this->assertEqual($this->ChairmanReports->Session->read('Message.flash.element'), 'flash_failure');
        }

        function testAdminEditInvalidRecord() {
		    $this->ChairmanReports->Session->write('Auth.User', array(
		        'id' => 2,
		        'role_id' => 2,
		        'username' => 'dnolan',
		        'location_id' => 1
		    ));	
			$this->ChairmanReports->params = Router::parse('/admin/chairman_reports/edit');
	   		$this->ChairmanReports->beforeFilter();
	    	$this->ChairmanReports->Component->startup($this->ChairmanReports);
	        $this->ChairmanReports->admin_edit();	
			$this->assertEqual($this->ChairmanReports->Session->read('Message.flash.element'), 'flash_failure');
	    }

	function testAdminDeleteValidRecord() {
	    $this->ChairmanReports->Session->write('Auth.User', array(
	        'id' => 2,
	        'username' => 'dnolan',
	        'role_id' => 2,
	        'location_id' => 1
	    ));			

		$this->ChairmanReports->params = Router::parse('/admin/chairman_reports/delete/1');
   		$this->ChairmanReports->beforeFilter();
    	$this->ChairmanReports->Component->startup($this->ChairmanReports);		
        $this->ChairmanReports->admin_delete(1);
		$this->assertEqual($this->ChairmanReports->Session->read('Message.flash.element'), 'flash_success');		
		$this->assertFalse($this->ChairmanReports->ChairmanReport->read(null, 1));
	}

        function testAdminDeleteInvalidRecord() {
		    $this->ChairmanReports->Session->write('Auth.User', array(
		        'id' => 2,
		        'username' => 'dnolan',
		        'role_id' => 2,
		        'location_id' => 1
		    ));			
	
			$this->ChairmanReports->params = Router::parse('/admin/chairman_reports/delete/100');
	   		$this->ChairmanReports->beforeFilter();
	    	$this->ChairmanReports->Component->startup($this->ChairmanReports);		
	        $this->ChairmanReports->admin_delete(100);
			$this->assertEqual($this->ChairmanReports->Session->read('Message.flash.element'), 'flash_failure');		
	    }

	    function testAdminDeleteWithNoSpecifiedRecord() {
		    $this->ChairmanReports->Session->write('Auth.User', array(
		        'id' => 2,
		        'username' => 'dnolan',
		        'role_id' => 2,
		        'location_id' => 1
		    ));			
			
		    $this->ChairmanReports->Session->write('Auth.User', array(
		        'id' => 2,
		        'role_id' => 2,
		        'username' => 'dnolan',
		        'location_id' => 1
		    ));
			$this->ChairmanReports->params = Router::parse('/admin/filed_documents/delete');
	   		$this->ChairmanReports->beforeFilter();
	    	$this->ChairmanReports->Component->startup($this->ChairmanReports);			
	        $this->ChairmanReports->admin_delete();			    	
	        $this->assertEqual($this->ChairmanReports->Session->read('Message.flash.element'), 'flash_failure');
	   }
}
?>