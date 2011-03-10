<?php
/* HelpfulArticles Test cases generated on: 2011-03-07 20:29:43 : 1299529783*/
App::import('Controller', 'HelpfulArticles');
App::import('Lib', 'AtlasTestCase');
class TestHelpfulArticlesController extends HelpfulArticlesController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class HelpfulArticlesControllerTestCase extends AtlasTestCase {
	var $fixtures = array('app.helpful_article');

	function startTest() {
		$this->HelpfulArticles =& new TestHelpfulArticlesController();
		$this->HelpfulArticles->constructClasses();
	}

	function endTest() {
		unset($this->HelpfulArticles);
		ClassRegistry::flush();
	}

	function testIndex() {

	}

	function testAdminEditWithValidData() {
        $this->HelpfulArticles->data = array(
            'HelpfulArticle' => array(
                'title' => 'Valid Title'
            )
        );

        $this->HelpfulArticles->admin_edit(1);
        $this->assertEqual($this->HelpfulArticles->redirectUrl, array('action' => 'index'));

		$this->assertFlashMessage($this->HelpfulArticles, 'The helpful article has been saved', 'flash_success');
	}

    function testAdminEditWithInvalidData() {
        $this->HelpfulArticles->data = array(
            'HelpfulArticle' => array(
                'title' => ''
            )
        );

        $this->HelpfulArticles->admin_edit(1);
		$this->assertFlashMessage($this->HelpfulArticles, 'The helpful article could not be saved. Please, try again.', 'flash_failure');
    }

    function testAdminEditInvalidRecord() {
        $this->HelpfulArticles->admin_edit();
        $this->assertEqual($this->HelpfulArticles->redirectUrl, array('action' => 'index'));
		
		$this->assertFlashMessage($this->HelpfulArticles, 'Invalid helpful article', 'flash_failure');
    }

	function testAdminDeleteValidRecord() {
            $this->HelpfulArticles->admin_delete(1);
            $this->assertEqual($this->HelpfulArticles->redirectUrl, array('action' => 'index'));
            $this->assertFalse($this->HelpfulArticles->HelpfulArticle->read(null, 1));
	}

    function testAdminDeleteInvalidRecord() {
        $this->HelpfulArticles->admin_delete(100);
        $this->assertEqual($this->HelpfulArticles->redirectUrl, array('action' => 'index'));

		$this->assertFlashMessage($this->HelpfulArticles, 'Helpful article was not deleted', 'flash_failure');
    }

    function testAdminDeleteWithNoSpecifiedRecord() {
        $this->HelpfulArticles->admin_delete();
		
		$this->assertFlashMessage($this->HelpfulArticles, 'Helpful article was not deleted', 'flash_failure');
        $this->assertEqual($this->HelpfulArticles->redirectUrl, array('action' => 'index'));
   }

}
?>