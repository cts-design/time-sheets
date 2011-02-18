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
		$this->ChairmanReports =& new TestChairmanReportsController();
		$this->ChairmanReports->constructClasses();
	}

	function endTest() {
		unset($this->ChairmanReports);
		ClassRegistry::flush();
	}

		function testIndex() {
            $result = $this->testAction('/chairman_reports/index', array('return' => 'view'));
            //debug(htmlentities($result));
        }

	function testAdminIndex() {
            $result = $this->testAction('/admin/chairman_reports/index', array('return' => 'view'));
//            debug(htmlentities($result));
	}

	function testAdminAddPdf() {
            $this->ChairmanReports->data = array(
                'ChairmanReport' => array(
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

           $this->ChairmanReports->admin_add();

	}

        function testAdminAddDoc() {
            $this->ChairmanReports->data = array(
                'ChairmanReport' => array(
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

            ///debug($this->ChairmanReports->admin_add());

	}

        function testAdminAddDocx() {
            $this->ChairmanReports->data = array(
                'ChairmanReport' => array(
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

            //debug($this->ChairmanReports->admin_add());

	}

	function testAdminEditWithValidData() {
            $this->ChairmanReports->data = array(
                'ChairmanReport' => array(
                    'title' => 'Valid Title'
                )
            );

            $this->ChairmanReports->admin_edit(1);
            $this->assertEqual($this->ChairmanReports->redirectUrl, array('action' => 'index'));

            $flashMessage = $this->ChairmanReports->Session->read('Message.flash');
            $expectedFlashMessage = array(
                'message' => 'The chairman report has been saved',
                'element' => 'flash_success',
                'params' => array()
            );

            $this->assertEqual($flashMessage, $expectedFlashMessage);
	}

        function testAdminEditWithInvalidData() {
            $this->ChairmanReports->data = array(
                'ChairmanReport' => array(
                    'title' => 'Invalid Title!!'
                )
            );

            $this->ChairmanReports->admin_edit(1);

            $flashMessage = $this->ChairmanReports->Session->read('Message.flash');
            $expectedFlashMessage = array(
                'message' => 'The chairman report could not be saved. Please, try again.',
                'element' => 'flash_failure',
                'params' => array()
            );

            $this->assertEqual($flashMessage, $expectedFlashMessage);
        }

        function testAdminEditInvalidRecord() {
            $this->ChairmanReports->admin_edit();
            $this->assertEqual($this->ChairmanReports->redirectUrl, array('action' => 'index'));

            $flashMessage = $this->ChairmanReports->Session->read('Message.flash');
            $expectedFlashMessage = array(
                'message' => 'Invalid chairman report',
                'element' => 'flash_failure',
                'params' => array()
            );

            $this->assertEqual($flashMessage, $expectedFlashMessage);
        }

	function testAdminDeleteValidRecord() {
            $this->ChairmanReports->admin_delete(1);
            $this->assertEqual($this->ChairmanReports->redirectUrl, array('action' => 'index'));
            $this->assertFalse($this->ChairmanReports->ChairmanReport->read(null, 1));
	}

        function testAdminDeleteInvalidRecord() {
            $this->ChairmanReports->admin_delete(100);
            $this->assertEqual($this->ChairmanReports->redirectUrl, array('action' => 'index'));

            $flashMessage = $this->ChairmanReports->Session->read('Message.flash');
            $expectedFlashMessage = array(
                'message' => 'Chairman report was not deleted',
                'element' => 'flash_failure',
                'params' => array()
            );

            $this->assertEqual($flashMessage, $expectedFlashMessage);
        }

        function testAdminDeleteWithNoSpecifiedRecord() {
            $this->ChairmanReports->admin_delete();

                        $flashMessage = $this->ChairmanReports->Session->read('Message.flash');
            $expectedFlashMessage = array(
                'message' => 'Chairman report was not deleted',
                'element' => 'flash_failure',
                'params' => array()
            );

            $this->assertEqual($flashMessage, $expectedFlashMessage);

            $this->assertEqual($this->ChairmanReports->redirectUrl, array('action' => 'index'));


       }
}
?>