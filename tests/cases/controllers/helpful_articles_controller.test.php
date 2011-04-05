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
	function startTest() {
		$this->HelpfulArticles =& new TestHelpfulArticlesController();
		$this->HelpfulArticles->constructClasses();
	}

	function endTest() {
		unset($this->HelpfulArticles);
		ClassRegistry::flush();
	}

	function testIndex() {
        $result = $this->testAction('/helpful_articles/index', array('return' => 'vars'));
        $result = Set::extract('/helpfulArticles/.[1]', $result);
		$expected = array(
	        'id' => 1,
	        'title' => 'A Helpful Article From Some Publication',
	        'reporter' => 'Sally Beard - WSJ.com',
	        'summary' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
	        'link' => 'http://www.wsj.com/sally.beard/a-helpful-article',
	        'posted_date' => '2011-03-07',
	        'created' => '2011-03-07 20:29:20',
	        'modified' => '2011-03-07 20:29:20',
		);
		$this->assertEqual($result[0]['HelpfulArticle'], $expected);
	}
	
	function testAdminIndex() {
		$this->HelpfulArticles->Session->write('Auth.User', array(
	        'id' => 2,
	        'role_id' => 2,
	        'username' => 'dnolan',
	        'location_id' => 1
	    ));
		
		$result = $this->testAction('/admin/helpful_articles/index', array('return' => 'vars'));
        $result = Set::extract('/helpfulArticles/.[1]', $result);
		$expected = array(
	        'id' => 1,
	        'title' => 'A Helpful Article From Some Publication',
	        'reporter' => 'Sally Beard - WSJ.com',
	        'summary' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
	        'link' => 'http://www.wsj.com/sally.beard/a-helpful-article',
	        'posted_date' => '2011-03-07',
	        'created' => '2011-03-07 20:29:20',
	        'modified' => '2011-03-07 20:29:20',
		);
		$this->assertEqual($result[0]['HelpfulArticle'], $expected);
	}

	function testAdminAddWithValidData() {
		$this->HelpfulArticles->Session->write('Auth.User', array(
	        'id' => 2,
	        'role_id' => 2,
	        'username' => 'dnolan',
	        'location_id' => 1
	    ));
		
		$this->HelpfulArticles->data = array(
			'id' => 1,
			'title' => 'A Helpful Article From Some Publication',
			'reporter' => 'Sally Beard - WSJ.com',
			'summary' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'link' => 'http://www.wsj.com/sally.beard/a-helpful-article',
			'posted_date' => '2011-03-07'
		);
		
	    $this->HelpfulArticles->params = Router::parse('/admin/helpful_articles/add');
	    $this->HelpfulArticles->beforeFilter();
	    $this->HelpfulArticles->Component->startup($this->HelpfulArticles);
		$this->HelpfulArticles->admin_add();	
		$this->assertEqual($this->HelpfulArticles->Session->read('Message.flash.element'), 'flash_success');
	}

	function testAdminAddWithInvalidData() {
		$this->HelpfulArticles->Session->write('Auth.User', array(
	        'id' => 2,
	        'role_id' => 2,
	        'username' => 'dnolan',
	        'location_id' => 1
	    ));
		
		$this->HelpfulArticles->data = array(
			'id' => 1,
			'title' => '',
			'reporter' => 'Sally Beard - WSJ.com',
			'summary' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'link' => 'http://www.wsj.com/sally.beard/a-helpful-article',
			'posted_date' => '2011-03-07'
		);
		
	    $this->HelpfulArticles->params = Router::parse('/admin/helpful_articles/add');
	    $this->HelpfulArticles->beforeFilter();
	    $this->HelpfulArticles->Component->startup($this->HelpfulArticles);
		$this->HelpfulArticles->admin_add();	
		$this->assertEqual($this->HelpfulArticles->Session->read('Message.flash.element'), 'flash_failure');
	}

	function testAdminEditWithValidData() {
		$this->HelpfulArticles->Session->write('Auth.User', array(
	        'id' => 2,
	        'role_id' => 2,
	        'username' => 'dnolan',
	        'location_id' => 1
	    ));	
		
        $this->HelpfulArticles->data = array(
            'HelpfulArticle' => array(
                'title' => 'Valid Title'
            )
        );

	    $this->HelpfulArticles->params = Router::parse('/admin/helpful_articles/edit/1');
	    $this->HelpfulArticles->beforeFilter();
	    $this->HelpfulArticles->Component->startup($this->HelpfulArticles);
		$this->HelpfulArticles->admin_edit(1);	
		$this->assertEqual($this->HelpfulArticles->Session->read('Message.flash.element'), 'flash_success');
	}

    function testAdminEditWithInvalidData() {
		$this->HelpfulArticles->Session->write('Auth.User', array(
	        'id' => 2,
	        'role_id' => 2,
	        'username' => 'dnolan',
	        'location_id' => 1
	    ));	
		
       $this->HelpfulArticles->data = array(
            'HelpfulArticle' => array(
                'title' => ''
            )
        );

	    $this->HelpfulArticles->params = Router::parse('/admin/chairman_reports/edit/1');
	    $this->HelpfulArticles->beforeFilter();
	    $this->HelpfulArticles->Component->startup($this->HelpfulArticles);
		$this->HelpfulArticles->admin_edit(1);	
		$this->assertEqual($this->HelpfulArticles->Session->read('Message.flash.element'), 'flash_failure');
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