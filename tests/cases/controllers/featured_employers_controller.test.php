<?php
/* FeaturedEmployers Test cases generated on: 2011-03-01 20:14:27 : 1299010467*/
App::import('Controller', 'FeaturedEmployers');
App::import('Lib', 'AtlasTestCase');
class TestFeaturedEmployersController extends FeaturedEmployersController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class FeaturedEmployersControllerTestCase extends AtlasTestCase {
	function startTest() {
		$this->FeaturedEmployers =& new TestFeaturedEmployersController();
		$this->FeaturedEmployers->constructClasses();
	}

	function endTest() {
		unset($this->FeaturedEmployers);
		ClassRegistry::flush();
	}

	function testIndex() {
        $result = $this->testAction('/featured_employers/index', array('return' => 'vars'));
        $result = Set::extract('/featuredEmployers/.[1]', $result);
		$expected = array(
			'id' => 1,
	        'name' => 'Lorem ipsum dolor sit amet',
	        'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
	        'image' => 'Lorem ipsum dolor sit amet',
	        'url' => 'Lorem ipsum dolor sit amet'
		);
		$this->assertEqual($result[0]['FeaturedEmployer'], $expected);
    }

	function testAdminIndex() {
		$this->FeaturedEmployers->Session->write('Auth.User', array(
	        'id' => 2,
	        'role_id' => 2,
	        'username' => 'dnolan',
	        'location_id' => 1
	    ));
			
        $result = $this->testAction('/admin/featured_employers/index', array('return' => 'vars'));
        $result = Set::extract('/featuredEmployers/.[1]', $result);
		$expected = array(
			'id' => 1,
	        'name' => 'Lorem ipsum dolor sit amet',
	        'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
	        'image' => 'Lorem ipsum dolor sit amet',
	        'url' => 'Lorem ipsum dolor sit amet'
		);
		$this->assertEqual($result[0]['FeaturedEmployer'], $expected);
	}

	function testAdminEditWithValidData() {
			$this->FeaturedEmployers->Session->write('Auth.User', array(
		        'id' => 2,
		        'role_id' => 2,
		        'username' => 'dnolan',
		        'location_id' => 1
		    ));	
			
            $this->FeaturedEmployers->data = array(
                'FeaturedEmployer' => array(
                    'title' => 'Valid Title'
                )
            );
			
		    $this->FeaturedEmployers->params = Router::parse('/admin/featured_employers/edit/1');
		    $this->FeaturedEmployers->beforeFilter();
		    $this->FeaturedEmployers->Component->startup($this->FeaturedEmployers);
			$this->FeaturedEmployers->admin_edit(1);	
			$this->assertEqual($this->FeaturedEmployers->Session->read('Message.flash.element'), 'flash_success');
	}

        function testAdminEditInvalidRecord() {
		    $this->FeaturedEmployers->Session->write('Auth.User', array(
		        'id' => 2,
		        'role_id' => 2,
		        'username' => 'dnolan',
		        'location_id' => 1
		    ));	
			$this->FeaturedEmployers->params = Router::parse('/admin/featured_employers/edit');
	   		$this->FeaturedEmployers->beforeFilter();
	    	$this->FeaturedEmployers->Component->startup($this->FeaturedEmployers);
	        $this->FeaturedEmployers->admin_edit();	
			$this->assertEqual($this->FeaturedEmployers->Session->read('Message.flash.element'), 'flash_failure');
	    }

	function testAdminDeleteValidRecord() {
	    $this->FeaturedEmployers->Session->write('Auth.User', array(
	        'id' => 2,
	        'username' => 'dnolan',
	        'role_id' => 2,
	        'location_id' => 1
	    ));			

		$this->FeaturedEmployers->params = Router::parse('/admin/featured_employers/delete/1');
   		$this->FeaturedEmployers->beforeFilter();
    	$this->FeaturedEmployers->Component->startup($this->FeaturedEmployers);		
        $this->FeaturedEmployers->admin_delete(1);
		$this->assertEqual($this->FeaturedEmployers->Session->read('Message.flash.element'), 'flash_success');		
		$this->assertFalse($this->FeaturedEmployers->FeaturedEmployer->read(null, 1));
	}

        function testAdminDeleteInvalidRecord() {
		    $this->FeaturedEmployers->Session->write('Auth.User', array(
		        'id' => 2,
		        'username' => 'dnolan',
		        'role_id' => 2,
		        'location_id' => 1
		    ));			
	
			$this->FeaturedEmployers->params = Router::parse('/admin/featured_employers/delete/100');
	   		$this->FeaturedEmployers->beforeFilter();
	    	$this->FeaturedEmployers->Component->startup($this->FeaturedEmployers);		
	        $this->FeaturedEmployers->admin_delete(100);
			$this->assertEqual($this->FeaturedEmployers->Session->read('Message.flash.element'), 'flash_failure');		
		}

	    function testAdminDeleteWithNoSpecifiedRecord() {
		    $this->FeaturedEmployers->Session->write('Auth.User', array(
		        'id' => 2,
		        'username' => 'dnolan',
		        'role_id' => 2,
		        'location_id' => 1
		    ));			
			
		    $this->FeaturedEmployers->Session->write('Auth.User', array(
		        'id' => 2,
		        'role_id' => 2,
		        'username' => 'dnolan',
		        'location_id' => 1
		    ));
			$this->FeaturedEmployers->params = Router::parse('/admin/featured_employers/delete');
	   		$this->FeaturedEmployers->beforeFilter();
	    	$this->FeaturedEmployers->Component->startup($this->FeaturedEmployers);			
	        $this->FeaturedEmployers->admin_delete();			    	
	        $this->assertEqual($this->FeaturedEmployers->Session->read('Message.flash.element'), 'flash_failure');
	   }
		
		function testAdminAddWithoutUpload() {
			$this->FeaturedEmployers->Session->write('Auth.User', array(
		        'id' => 2,
		        'role_id' => 2,
		        'username' => 'dnolan',
		        'location_id' => 1
		    ));
			
			$this->FeaturedEmployers->data = array(
				'FeaturedEmployer' => array(
					'name' => 'New Featured Employer',
					'description' => 'lorem ipsum...',
					'image' => array(
						'error' => 4
					),
					'url' => 'http://featuredemployerurl.com'
				)
			);
			
			$this->FeaturedEmployers->params = Router::parse('/admin/featured_employers/add');
	   		$this->FeaturedEmployers->beforeFilter();
	    	$this->FeaturedEmployers->Component->startup($this->FeaturedEmployers);			
	        $this->FeaturedEmployers->admin_add();			    	
	        $this->assertEqual($this->FeaturedEmployers->Session->read('Message.flash.element'), 'flash_success');
		}

}
?>