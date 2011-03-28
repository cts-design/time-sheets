<?php
/* InTheNews Test cases generated on: 2011-03-07 19:52:23 : 1299527543*/
App::import('Controller', 'InTheNews');
App::import('Lib', 'AtlasTestCase');
class TestInTheNewsController extends InTheNewsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class InTheNewsControllerTestCase extends AtlasTestCase {


	function startTest() {
		$this->InTheNews =& new TestInTheNewsController();
		$this->InTheNews->constructClasses();
	}

	function endTest() {
		unset($this->InTheNews);
		ClassRegistry::flush();
	}

	function testIndex() {
        $result = $this->testAction('/in_the_news/index', array('return' => 'vars'));
        $result = Set::extract('/inTheNews/.[1]', $result);
		$expected = array(
			'id' => 1,
			'title' => 'we were in the news!!',
			'reporter' => 'WSJ.com',
			'summary' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'link' => 'http://www.wsj.com/sally.beard/a-helpful-article',
			'posted_date' => '2011-03-07',
			'created' => '2011-03-07 20:29:20',
			'modified' => '2011-03-07 20:29:20'
		);
		$this->assertEqual($result[0]['InTheNews'], $expected);
	}

	function testAdminEditWithValidData() {
			$this->InTheNews->Session->write('Auth.User', array(
		        'id' => 2,
		        'role_id' => 2,
		        'username' => 'dnolan',
		        'location_id' => 1
		    ));	
			
            $this->InTheNews->data = array(
                'ChairmanReport' => array(
                    'title' => 'Valid Title'
                )
            );
			
		    $this->InTheNews->params = Router::parse('/admin/chairman_reports/edit/1');
		    $this->InTheNews->beforeFilter();
		    $this->InTheNews->Component->startup($this->InTheNews);
			$this->InTheNews->admin_edit(1);	
			$this->assertEqual($this->InTheNews->Session->read('Message.flash.element'), 'flash_success');
	}

    function testAdminEditWithInvalidData() {
			$this->InTheNews->Session->write('Auth.User', array(
		        'id' => 2,
		        'role_id' => 2,
		        'username' => 'dnolan',
		        'location_id' => 1
		    ));	
			
            $this->InTheNews->data = array(
                'InTheNews' => array(
                    'title' => ''
                )
            );
			
		    $this->InTheNews->params = Router::parse('/admin/chairman_reports/edit/1');
		    $this->InTheNews->beforeFilter();
		    $this->InTheNews->Component->startup($this->InTheNews);
			$this->InTheNews->admin_edit(1);	
			$this->assertEqual($this->InTheNews->Session->read('Message.flash.element'), 'flash_failure');
    }

    function testAdminEditInvalidRecord() {
		$this->InTheNews->Session->write('Auth.User', array(
	        'id' => 2,
	        'role_id' => 2,
	        'username' => 'dnolan',
	        'location_id' => 1
	    ));	
        $this->InTheNews->data = array(
            'InTheNews' => array(
                'title' => 'Valid Title',
                'edit_type' => 'user'
            )
        );
	    $this->InTheNews->params = Router::parse('/admin/in_the_news/edit/1');
	    $this->InTheNews->beforeFilter();
	    $this->InTheNews->Component->startup($this->InTheNews);
		$this->InTheNews->admin_edit(1);	
		$this->assertEqual($this->InTheNews->Session->read('Message.flash.element'), 'flash_success');
    }

	function testAdminDeleteValidRecord() {
	    $this->InTheNews->Session->write('Auth.User', array(
	        'id' => 2,
	        'username' => 'dnolan',
	        'role_id' => 2,
	        'location_id' => 1
	    ));			

		$this->InTheNews->params = Router::parse('/admin/in_the_news/delete/1');
   		$this->InTheNews->beforeFilter();
    	$this->InTheNews->Component->startup($this->InTheNews);		
        $this->InTheNews->admin_delete(1);
		$this->assertEqual($this->InTheNews->Session->read('Message.flash.element'), 'flash_success');
		$this->assertFalse($this->InTheNews->InTheNews->read(null, 2));
	}

    function testAdminDeleteInvalidRecord() {
	    $this->InTheNews->Session->write('Auth.User', array(
	        'id' => 2,
	        'role_id' => 2,
	        'username' => 'dnolan',
	        'location_id' => 1
	    ));	    	

		$this->InTheNews->params = Router::parse('/admin/in_the_news/delete/33');
   		$this->InTheNews->beforeFilter();
    	$this->InTheNews->Component->startup($this->InTheNews);			
        $this->InTheNews->admin_delete(33);
        $this->assertEqual($this->InTheNews->Session->read('Message.flash.element'), 'flash_failure');
    }

    function testAdminDeleteWithNoSpecifiedRecord() {
	    $this->InTheNews->Session->write('Auth.User', array(
	        'id' => 2,
	        'role_id' => 2,
	        'username' => 'dnolan',
	        'location_id' => 1
	    ));
		$this->InTheNews->params = Router::parse('/admin/in_the_news/delete');
   		$this->InTheNews->beforeFilter();
    	$this->InTheNews->Component->startup($this->InTheNews);			
        $this->InTheNews->admin_delete();			    	
        $this->assertEqual($this->InTheNews->Session->read('Message.flash.element'), 'flash_failure');
   }

}
?>