<?php
/* Pages Test cases generated on: 2011-02-04 14:51:59 : 1296831119*/
App::import('Controller', 'Pages');

class TestPagesController extends PagesController {
	var $autoRender = false;
        var $autoLayout = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class PagesControllerTestCase extends CakeTestCase {
	var $fixtures = array(
            'app.aco',
            'app.aro',
            'app.aro_aco',
            'chairman_report',
            'deleted_document',
            'document_filing_category',
            'document_queue_category',
            'document_transaction',
            'filed_document',
            'ftp_document_scanner',
            'kiosk',
            'kiosk_button',
            'location',
            'master_kiosk_button',
            'navigation',
            'page',
            'press_release',
            'queued_document',
            'role',
            'self_scan_category',
            'self_sign_log',
            'self_sign_log_archive',
            'user',
            'user_transaction'
        );

	function startTest() {
		$this->Pages =& new TestPagesController();
		$this->Pages->constructClasses();
                $this->Pages->params['pass'] = array();
                $this->Pages->params['named'] = array();
	}

	function endTest() {
		unset($this->Pages);
		ClassRegistry::flush();
	}

        function testAdminIndex() {

        }

        function testAdminAddWithValidData() {
            $this->Pages->data = array(
                'Page' => array(
                    'title' => 'Valid Title',
                    'slug'  => 'valid_slug',
                    'content' => 'valid content yo!'
                )
            );
            $this->Pages->admin_add();
            $this->assertEqual($this->Pages->redirectUrl, array('action' => 'index'));

            $flashMessage = $this->Pages->Session->read('Message.flash');
            $expectedFlashMessage = array(
                'message' => 'The page has been saved',
                'element' => 'flash_success',
                'params' => array()
            );

            $this->assertEqual($flashMessage, $expectedFlashMessage);
        }

        function testAdminAddWithInvalidData() {
            $this->Pages->data = array(
                'Page' => array(
                    'title' => '',
                    'slug'  => 'valid_slug',
                    'content' => 'valid content yo!'
                )
            );
            $this->Pages->admin_add();

            $flashMessage = $this->Pages->Session->read('Message.flash');
            $expectedFlashMessage = array(
                'message' => 'The page could not be saved. Please, try again.',
                'element' => 'flash_failure',
                'params' => array()
            );

            $this->assertEqual($flashMessage, $expectedFlashMessage);
        }

        function testAdminEditWithValidData() {
            $this->Pages->data = array(
                'Page' => array(
                    'title' => 'Valid Title'
                )
            );

            $this->Pages->admin_edit(1);
            $this->assertEqual($this->Pages->redirectUrl, array('action' => 'index'));

            $flashMessage = $this->Pages->Session->read('Message.flash');
            $expectedFlashMessage = array(
                'message' => 'The page has been saved',
                'element' => 'flash_success',
                'params' => array()
            );

            $this->assertEqual($flashMessage, $expectedFlashMessage);
	}

        function testAdminEditWithInvalidData() {
            $this->Pages->data = array(
                'Page' => array(
                    'title' => 'Invalid Title!!'
                )
            );

            $this->Pages->admin_edit(1);

            $flashMessage = $this->Pages->Session->read('Message.flash');
            $expectedFlashMessage = array(
                'message' => 'The page could not be saved. Please, try again.',
                'element' => 'flash_failure',
                'params' => array()
            );

            $this->assertEqual($flashMessage, $expectedFlashMessage);
        }

        function testAdminEditInvalidRecord() {
            $this->Pages->admin_edit();
            $this->assertEqual($this->Pages->redirectUrl, array('action' => 'index'));

            $flashMessage = $this->Pages->Session->read('Message.flash');
            $expectedFlashMessage = array(
                'message' => 'Invalid page',
                'element' => 'flash_failure',
                'params' => array()
            );

            $this->assertEqual($flashMessage, $expectedFlashMessage);
        }

	function testAdminDeleteValidRecord() {
            $this->Pages->admin_delete(1);
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
            $this->Pages->admin_delete();

                        $flashMessage = $this->Pages->Session->read('Message.flash');
            $expectedFlashMessage = array(
                'message' => 'Page was not deleted',
                'element' => 'flash_failure',
                'params' => array()
            );

            $this->assertEqual($flashMessage, $expectedFlashMessage);

            $this->assertEqual($this->Pages->redirectUrl, array('action' => 'index'));


       }

}
?>