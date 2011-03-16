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
	var $fixtures = array('app.in_the_news');

	function startTest() {
		$this->InTheNews =& new TestInTheNewsController();
		$this->InTheNews->constructClasses();
	}

	function endTest() {
		unset($this->InTheNews);
		ClassRegistry::flush();
	}

	function testIndex() {

	}

	function testAdminEditWithValidData() {
        $this->InTheNews->data = array(
            'InTheNews' => array(
                'title' => 'Valid Title'
            )
        );

        $this->InTheNews->admin_edit(1);
        $this->assertEqual($this->InTheNews->redirectUrl, array('action' => 'index'));

		$this->assertFlashMessage($this->InTheNews, 'The in the news article has been saved', 'flash_success');
	}

    function testAdminEditWithInvalidData() {
        $this->InTheNews->data = array(
            'InTheNews' => array(
                'title' => ''
            )
        );

        $this->InTheNews->admin_edit(1);
		$this->assertFlashMessage($this->InTheNews, 'The in the news article could not be saved. Please, try again.', 'flash_failure');
    }

    function testAdminEditInvalidRecord() {
        $this->InTheNews->admin_edit();
        $this->assertEqual($this->InTheNews->redirectUrl, array('action' => 'index'));
		
		$this->assertFlashMessage($this->InTheNews, 'Invalid in the news article', 'flash_failure');
    }

	function testAdminDeleteValidRecord() {
            $this->InTheNews->admin_delete(1);
            $this->assertEqual($this->InTheNews->redirectUrl, array('action' => 'index'));
            $this->assertFalse($this->InTheNews->InTheNews->read(null, 1));
	}

    function testAdminDeleteInvalidRecord() {
        $this->InTheNews->admin_delete(100);
        $this->assertEqual($this->InTheNews->redirectUrl, array('action' => 'index'));

		$this->assertFlashMessage($this->InTheNews, 'In the news article was not deleted', 'flash_failure');
    }

    function testAdminDeleteWithNoSpecifiedRecord() {
        $this->InTheNews->admin_delete();
		
		$this->assertFlashMessage($this->InTheNews, 'In the news article was not deleted', 'flash_failure');
        $this->assertEqual($this->InTheNews->redirectUrl, array('action' => 'index'));
   }

}
?>