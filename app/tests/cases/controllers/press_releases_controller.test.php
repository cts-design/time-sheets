<?php
/* PressReleases Test cases generated on: 2011-02-09 15:21:32 : 1297264892*/
App::import('Controller', 'PressReleases');

class TestPressReleasesController extends PressReleasesController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class PressReleasesControllerTestCase extends CakeTestCase {
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
		$this->PressReleases =& new TestPressReleasesController();
		$this->PressReleases->constructClasses();
	}

	function endTest() {
		unset($this->PressReleases);
		ClassRegistry::flush();
	}

	function testIndex() {
            $result = $this->testAction('/press_releases/index', array('return' => 'view'));
            //debug(htmlentities($result));
        }

	function testAdminIndex() {
            $result = $this->testAction('/admin/press_releases/index', array('return' => 'view'));
//            debug(htmlentities($result));
	}

	function testAdminAddPdf() {
            $this->PressReleases->data = array(
                'PressRelease' => array(
                    'title' => 'Valid Title',
                    'file'  => array(
                        'name' => 'filename.pdf',
                        'type' => 'application/pdf',
                        'size' => 3600,
                        'tmp_name' => 'C:\tmp\tmp-file.pdf',
                        'error' => 0
                    )
                )
            );

           $this->PressReleases->admin_add();
	}

        function testAdminAddDoc() {
            $this->PressReleases->data = array(
                'PressRelease' => array(
                    'title' => 'Valid Title',
                    'file'  => array(
                        'name' => 'filename.doc',
                        'type' => 'application/msword',
                        'size' => 3600,
                        'tmp_name' => 'C:\tmp\tmp-file.pdf',
                        'error' => 0
                    )
                )
            );

            ///debug($this->PressReleases->admin_add());
	}

        function testAdminAddDocx() {
            $this->PressReleases->data = array(
                'PressRelease' => array(
                    'title' => 'Valid Title',
                    'file'  => array(
                        'name' => 'filename.docx',
                        'type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        'size' => 3600,
                        'tmp_name' => 'C:\tmp\tmp-file.pdf',
                        'error' => 0
                    )
                )
            );

            //debug($this->PressReleases->admin_add());
	}

	function testAdminEditWithValidData() {
            $this->PressReleases->data = array(
                'PressRelease' => array(
                    'title' => 'Valid Title'
                )
            );

            $this->PressReleases->admin_edit(1);
            $this->assertEqual($this->PressReleases->redirectUrl, array('action' => 'index'));

            $flashMessage = $this->PressReleases->Session->read('Message.flash');
            $expectedFlashMessage = array(
                'message' => 'The press release has been saved',
                'element' => 'flash_success',
                'params' => array()
            );

            $this->assertEqual($flashMessage, $expectedFlashMessage);
	}

        function testAdminEditWithInvalidData() {
            $this->PressReleases->data = array(
                'PressRelease' => array(
                    'title' => 'Invalid Title!!'
                )
            );

            $this->PressReleases->admin_edit(1);

            $flashMessage = $this->PressReleases->Session->read('Message.flash');
            $expectedFlashMessage = array(
                'message' => 'The press release could not be saved. Please, try again.',
                'element' => 'flash_failure',
                'params' => array()
            );

            $this->assertEqual($flashMessage, $expectedFlashMessage);
        }

        function testAdminEditInvalidRecord() {
            $this->PressReleases->admin_edit();
            $this->assertEqual($this->PressReleases->redirectUrl, array('action' => 'index'));

            $flashMessage = $this->PressReleases->Session->read('Message.flash');
            $expectedFlashMessage = array(
                'message' => 'Invalid press release',
                'element' => 'flash_failure',
                'params' => array()
            );

            $this->assertEqual($flashMessage, $expectedFlashMessage);
        }

	function testAdminDeleteValidRecord() {
            $this->PressReleases->admin_delete(1);
            $this->assertEqual($this->PressReleases->redirectUrl, array('action' => 'index'));
            $this->assertFalse($this->PressReleases->PressRelease->read(null, 1));
	}

        function testAdminDeleteInvalidRecord() {
            $this->PressReleases->admin_delete(100);
            $this->assertEqual($this->PressReleases->redirectUrl, array('action' => 'index'));

            $flashMessage = $this->PressReleases->Session->read('Message.flash');
            $expectedFlashMessage = array(
                'message' => 'Press release was not deleted',
                'element' => 'flash_failure',
                'params' => array()
            );

            $this->assertEqual($flashMessage, $expectedFlashMessage);
        }

        function testAdminDeleteWithNoSpecifiedRecord() {
            $this->PressReleases->admin_delete();

                        $flashMessage = $this->PressReleases->Session->read('Message.flash');
            $expectedFlashMessage = array(
                'message' => 'Press release was not deleted',
                'element' => 'flash_failure',
                'params' => array()
            );

            $this->assertEqual($flashMessage, $expectedFlashMessage);

            $this->assertEqual($this->PressReleases->redirectUrl, array('action' => 'index'));
        }
}
?>