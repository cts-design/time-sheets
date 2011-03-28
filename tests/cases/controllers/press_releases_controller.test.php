<?php
/* PressReleases Test cases generated on: 2011-02-09 15:21:32 : 1297264892*/
App::import('Controller', 'PressReleases');
App::import('Lib', 'AtlasTestCase');
class TestPressReleasesController extends PressReleasesController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class PressReleasesControllerTestCase extends AtlasTestCase {
	function startTest() {
		$this->PressReleases =& new TestPressReleasesController();
		$this->PressReleases->constructClasses();
	}

	function endTest() {
		unset($this->PressReleases);
		ClassRegistry::flush();
	}

	function testIndex() {
        $result = $this->testAction('/press_releases/index', array('return' => 'vars'));
        $result = Set::extract('/pressReleases/.[1]', $result);
		$expected = array(
			'id' => 1,
            'title' => 'Lorem ipsum dolor sit amet',
            'file' => 'http://atlas.dev/files/public/file.pdf',
            'created' => '2011-02-09 15:20:21',
            'modified' => '2011-02-09 15:20:21'
		);
		$this->assertEqual($result[0]['PressRelease'], $expected);
    }

	function testAdminIndex() {
            //$result = $this->testAction('/admin/press_releases/index', array('return' => 'vars'));
            //debug($result);
	}

	function testAdminEditWithValidData() {
			$this->PressReleases->Session->write('Auth.User', array(
		        'id' => 2,
		        'role_id' => 2,
		        'username' => 'dnolan',
		        'location_id' => 1
		    ));	
			
            $this->PressReleases->data = array(
                'PressRelease' => array(
                    'title' => 'Valid Title'
                )
            );
			
		    $this->PressReleases->params = Router::parse('/admin/press_releases/edit/1');
		    $this->PressReleases->beforeFilter();
		    $this->PressReleases->Component->startup($this->PressReleases);
			$this->PressReleases->admin_edit(1);	
			$this->assertEqual($this->PressReleases->Session->read('Message.flash.element'), 'flash_success');
	}

        function testAdminEditWithInvalidData() {
            $this->PressReleases->data = array(
                'PressRelease' => array(
                    'title' => 'Invalid Title!!'
                )
            );

		    $this->PressReleases->params = Router::parse('/admin/press_releases/edit/1');
		    $this->PressReleases->beforeFilter();
		    $this->PressReleases->Component->startup($this->PressReleases);
			$this->PressReleases->admin_edit(1);	
			$this->assertEqual($this->PressReleases->Session->read('Message.flash.element'), 'flash_failure');
        }

        function testAdminEditInvalidRecord() {
		    $this->PressReleases->Session->write('Auth.User', array(
		        'id' => 2,
		        'role_id' => 2,
		        'username' => 'dnolan',
		        'location_id' => 1
		    ));	
			$this->PressReleases->params = Router::parse('/admin/press_releases/edit');
	   		$this->PressReleases->beforeFilter();
	    	$this->PressReleases->Component->startup($this->PressReleases);
	        $this->PressReleases->admin_edit();	
			$this->assertEqual($this->PressReleases->Session->read('Message.flash.element'), 'flash_failure');
	    }

	function testAdminDeleteValidRecord() {
	    $this->PressReleases->Session->write('Auth.User', array(
	        'id' => 2,
	        'username' => 'dnolan',
	        'role_id' => 2,
	        'location_id' => 1
	    ));			

		$this->PressReleases->params = Router::parse('/admin/press_releases/delete/1');
   		$this->PressReleases->beforeFilter();
    	$this->PressReleases->Component->startup($this->PressReleases);		
        $this->PressReleases->admin_delete(1);
		$this->assertEqual($this->PressReleases->Session->read('Message.flash.element'), 'flash_success');		
		$this->assertFalse($this->PressReleases->PressRelease->read(null, 1));
	}

        function testAdminDeleteInvalidRecord() {
		    $this->PressReleases->Session->write('Auth.User', array(
		        'id' => 2,
		        'username' => 'dnolan',
		        'role_id' => 2,
		        'location_id' => 1
		    ));			
	
			$this->PressReleases->params = Router::parse('/admin/press_releases/delete/100');
	   		$this->PressReleases->beforeFilter();
	    	$this->PressReleases->Component->startup($this->PressReleases);		
	        $this->PressReleases->admin_delete(100);
			$this->assertEqual($this->PressReleases->Session->read('Message.flash.element'), 'flash_failure');		
	    }

	    function testAdminDeleteWithNoSpecifiedRecord() {
		    $this->PressReleases->Session->write('Auth.User', array(
		        'id' => 2,
		        'username' => 'dnolan',
		        'role_id' => 2,
		        'location_id' => 1
		    ));			
			
		    $this->PressReleases->Session->write('Auth.User', array(
		        'id' => 2,
		        'role_id' => 2,
		        'username' => 'dnolan',
		        'location_id' => 1
		    ));
			$this->PressReleases->params = Router::parse('/admin/filed_documents/delete');
	   		$this->PressReleases->beforeFilter();
	    	$this->PressReleases->Component->startup($this->PressReleases);			
	        $this->PressReleases->admin_delete();			    	
	        $this->assertEqual($this->PressReleases->Session->read('Message.flash.element'), 'flash_failure');
	   }
}
?>