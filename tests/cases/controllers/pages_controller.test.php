<?php
/* Pages Test cases generated on: 2011-02-04 14:51:59 : 1296831119*/
App::import('Controller', 'Pages');
App::import('Lib', 'AtlasTestCase');
class TestPagesController extends PagesController {
	var $autoRender = false;
        var $autoLayout = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class PagesControllerTestCase extends AtlasTestCase {
	function startTest() {
		$this->Pages =& new TestPagesController();
		$this->Pages->constructClasses();
        $this->Pages->params['controller'] = 'pages';
        $this->Pages->params['pass'] = array();
        $this->Pages->params['named'] = array();
        $this->testController = $this->Pages;
	}

	function endTest() {
        $this->Pages->Session->destroy();
		unset($this->Pages);
		ClassRegistry::flush();
	}

    function testAdminIndex() {
        $this->Pages->params['action'] = 'admin_index';
        $this->Pages->Component->initialize($this->Pages);
        $this->Pages->Session->write('Auth.User', array(
            'id' => 1,
            'role_id' => 2,
            'username' => 'bcordell',
            'location_id' => 1
        ));

        $result = $this->testAction('/admin/pages', array('return' => 'vars'));
        $expectedResult = array(
            array(
                'Page' => array(
                    'id' => 1,
                    'title' => 'Test Page',
                    'slug' => 'test_page',
                    'content' => 'This is the test page.',
                    'published' => 1,
                    'authentication_required' => 0,
                    'locked' => 0,
                    'created' => '2011-02-04 14:50:04',
                    'modified' => '2011-02-04 14:50:04'
                )
            ),
            array(
                'Page' => array(
                    'id' => 2,
                    'title' => 'About Us',
                    'slug' => 'about_us',
                    'content' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat
                                      dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus.
                                      Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim,
                                      rhoncus duis vestibulum nunc mattis convallis.',
                    'published' => 1,
                    'authentication_required' => 1,
                    'locked' => 1,
                    'created' => '2011-02-04 14:50:04',
                    'modified' => '2011-02-04 14:50:04'
                )
            ),
            array(
                'Page' => array(
                    'id' => 3,
                    'title' => 'Unpub',
                    'slug' => 'unpub',
                    'content' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat
                                      dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus.
                                      Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim,
                                      rhoncus duis vestibulum nunc mattis convallis.',
                    'published' => 0,
                    'authentication_required' => 0,
                    'locked' => 1,
                    'created' => '2011-02-04 14:50:04',
                    'modified' => '2011-02-04 14:50:04'
                )
            )
        );

        $this->assertEqual($result['pages'], $expectedResult);
    }

    function testAdminAddWithValidData() {
        $this->Pages->params['action'] = 'admin_add';
        $this->Pages->Component->initialize($this->Pages);
        $this->Pages->Session->write('Auth.User', array(
            'id' => 1,
            'role_id' => 2,
            'username' => 'bcordell',
            'location_id' => 1
        ));
        $data = array(
            'Page' => array(
                'title' => 'Valid Title',
                'slug'  => 'valid_slug',
                'content' => 'valid content yo!',
                'authentication_required' => 0
            )
        );
        $result = $this->testAction('/admin/pages/add', array('data' => $data));

        $this->assertEqual($this->Pages->Session->read('Message.flash.element'), 'flash_success');
        $this->assertEqual($this->Pages->redirectUrl, array('action' => 'index'));
    }

    function testAdminAddWithInvalidData() {
        $this->Pages->params['action'] = 'admin_add';
        $this->Pages->Component->initialize($this->Pages);
        $this->Pages->Session->write('Auth.User', array(
            'id' => 1,
            'role_id' => 2,
            'username' => 'bcordell',
            'location_id' => 1
        ));
        $data = array(
            'Page' => array(
                'title' => '',
                'slug'  => 'valid_slug',
                'content' => 'valid content yo!',
                'authentication_required' => 0
            )
        );
        $result = $this->testAction('/admin/pages/add', array('data' => $data));
        $this->assertEqual($this->Pages->Session->read('Message.flash.element'), 'flash_failure');
    }

    // TODO: 
    // try to test editing homepage deleting cache on save
    // test empty this->data on admin_edit (check page retreived properly)
    function testAdminEditPage() {
        $this->Pages->params['action'] = 'admin_edit';
        $this->Pages->Component->initialize($this->Pages);
        $this->Pages->Session->write('Auth.User', array(
            'id' => 1,
            'role_id' => 2,
            'username' => 'bcordell',
            'location_id' => 1
        ));

        $this->testAction('/admin/pages/edit/1', array('return' => 'vars'));
        $expected = array(
            'Page' => array(
                'id' => 1,
                'title' => 'Test Page',
                'slug' => 'test_page',
                'content' => 'This is the test page.',
                'published' => 1,
                'authentication_required' => 0,
                'locked' => 0,
                'created' => '2011-02-04 14:50:04',
                'modified' => '2011-02-04 14:50:04'
            )
        );
        $this->assertEqual($this->Pages->data, $expected);
    }

    function testAdminEditWithValidData() {
        $this->Pages->params['action'] = 'admin_edit';
        $this->Pages->Component->initialize($this->Pages);
        $this->Pages->Session->write('Auth.User', array(
            'id' => 1,
            'role_id' => 2,
            'username' => 'bcordell',
            'location_id' => 1
        ));

        $page = $this->Pages->Page->findById(1);
        $page['Page']['title'] = 'Title edit';

        $result = $this->testAction('/admin/pages/edit/1', array('data' => $page));

        $this->assertEqual($this->Pages->redirectUrl, array('action' => 'index'));
        $this->assertEqual($this->Pages->Session->read('Message.flash.element'), 'flash_success');
    }

    function testAdminEditWithInvalidData() {
        $this->Pages->params['action'] = 'admin_add';
        $this->Pages->Component->initialize($this->Pages);
        $this->Pages->Session->write('Auth.User', array(
            'id' => 1,
            'role_id' => 2,
            'username' => 'bcordell',
            'location_id' => 1
        ));

        $page = $this->Pages->Page->findById(1);
        $page['Page']['title'] = '';

        $result = $this->testAction('/admin/pages/edit/1', array('data' => $page));
        $this->assertTrue(array_key_exists('title', $this->Pages->Page->invalidFields()));
        $this->assertEqual($this->Pages->Session->read('Message.flash.element'), 'flash_failure');
    }

    function testAdminEditWithNoSpecifiedRecord() {
        $this->Pages->params['action'] = 'admin_edit';
        $this->Pages->Component->initialize($this->Pages);
        $this->Pages->Session->write('Auth.User', array(
            'id' => 1,
            'role_id' => 2,
            'username' => 'bcordell',
            'location_id' => 1
        ));

        $result = $this->testAction('/admin/pages/edit');
        $this->assertEqual($this->Pages->Session->read('Message.flash.element'), 'flash_failure');
    }

    function testAdminDeleteValidRecord() {
        $this->Pages->params['action'] = 'admin_delete';
        $this->Pages->Component->initialize($this->Pages);
        $this->Pages->Session->write('Auth.User', array(
            'id' => 1,
            'role_id' => 2,
            'username' => 'bcordell',
            'location_id' => 1
        ));

        $result = $this->testAction('/admin/pages/delete/1');
        $this->assertEqual($this->Pages->Session->read('Message.flash.element'), 'flash_success');
        $this->assertEqual($this->Pages->redirectUrl, array('action' => 'index'));
        $this->assertFalse($this->Pages->Page->read(null, 1));
    }

    function testAdminDeleteInvalidRecord() {
        $this->Pages->admin_delete(100);
        $this->assertEqual($this->Pages->redirectUrl, array('action' => 'index'));

        $flashMessage = $this->Pages->Session->read('Message.flash');
        $expectedFlashMessage = array(
            'message' => 'Page was not deleted',
            'element' => 'flash_failure',
            'params' => array()
        );

        $this->assertEqual($flashMessage, $expectedFlashMessage);
    }

    function testAdminDeleteWithNoSpecifiedRecord() {
        $this->Pages->params['action'] = 'admin_delete';
        $this->Pages->Component->initialize($this->Pages);
        $this->Pages->Session->write('Auth.User', array(
            'id' => 1,
            'role_id' => 2,
            'username' => 'bcordell',
            'location_id' => 1
        ));

        $result = $this->testAction('/admin/pages/delete');

        $this->assertEqual($this->Pages->Session->read('Message.flash.element'), 'flash_failure');
        $this->assertEqual($this->Pages->redirectUrl, array('action' => 'index'));
   }

    function testAdminDeleteLockedPage() {
        $this->Pages->params['action'] = 'admin_delete';
        $this->Pages->Component->initialize($this->Pages);
        $this->Pages->Session->write('Auth.User', array(
            'id' => 1,
            'role_id' => 2,
            'username' => 'bcordell',
            'location_id' => 1
        ));

        $result = $this->testAction('/admin/pages/delete/2');

        $this->assertEqual($this->Pages->Session->read('Message.flash.element'), 'flash_failure');
        $this->assertEqual($this->Pages->redirectUrl, array('action' => 'index'));
    }

    // this function gets a short list of pages
    // for the navigation module
    function testAdminGetShortList() {
        $this->Pages->params['action'] = 'admin_get_short_list';
        $this->Pages->Component->initialize($this->Pages);
        $this->Pages->Session->write('Auth.User', array(
            'id' => 1,
            'role_id' => 2,
            'username' => 'bcordell',
            'location_id' => 1
        ));

        $result = $this->testAction('/admin/pages/get_short_list', array('return' => 'view'));
        $expected = array(
            'data' => array(
                'pages' => array(
                    array(
                        'title' => 'Test Page',
                        'slug' => 'test_page'
                    ),
                    array(
                        'title' => 'About Us',
                        'slug' => 'about_us'
                    )
                )
            )
        );
        $this->assertEqual($result, $expected);
    }

    function testPageThatRequiresAuthenticationAsGuest() {
        // $this->testController = null;
        $this->Pages->params['action'] = 'dynamicDisplay';
        $this->Pages->params['url']['url'] = 'pages/about_us';
        $this->Pages->Component->initialize($this->Pages);
        $result = $this->testAction('pages/about_us', array('session' => false));
        $this->assertEqual($this->Pages->Session->read('Auth.redirect'), '/pages/about_us');
        $this->assertEqual($this->Pages->redirectUrl, array('controller' => 'users', 'action' => 'login'));
    }
}
?>
