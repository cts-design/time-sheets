<?php
/* FiledDocuments Test cases generated on: 2010-11-24 15:11:59 : 1290612419*/
App::import('Controller', 'FiledDocuments');
App::import('Lib', 'AtlasTestCase');
class TestFiledDocumentsController extends FiledDocumentsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class FiledDocumentsControllerTestCase extends AtlasTestCase {
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
            'module_access_control',
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
		$this->FiledDocuments =& new TestFiledDocumentsController();
		$this->FiledDocuments->constructClasses();
	}

	function endTest() {
		unset($this->FiledDocuments);
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

	function testAdminEditWithValidData() {
            $this->FiledDocuments->data = array(
                'FiledDocument' => array(
                    'title' => 'Valid Title'
                )
            );

            $this->FiledDocuments->admin_edit(1);
			$this->assertFlashMessage($this->FiledDocuments, 'The filed document has been saved', 'flash_success');
	}


	function testAdminDeleteValidRecord() {
        	$this->FiledDocuments->data = array(
				'FiledDocument' => array(
					'id' => 1,
					'reason' => 'Duplicate Scan'
				)
			);
            $this->FiledDocuments->admin_delete();
			$this->assertFlashMessage($this->FiledDocuments, 'Filed document deleted', 'flash_success');
	}

        function testAdminDeleteInvalidRecord() {
        	$this->FiledDocuments->data = array(
				'FiledDocument' => array(
					'id' => 1
				)
			);
            $this->FiledDocuments->admin_delete();
            $this->assertEqual($this->FiledDocuments->redirectUrl, array('action' => 'index'));

            $flashMessage = $this->FiledDocuments->Session->read('Message.flash');
            $expectedFlashMessage = array(
                'message' => 'Chairman report was not deleted',
                'element' => 'flash_failure',
                'params' => array()
            );

            $this->assertEqual($flashMessage, $expectedFlashMessage);
        }

        function testAdminDeleteWithNoSpecifiedRecord() {
            $this->FiledDocuments->admin_delete();

                        $flashMessage = $this->FiledDocuments->Session->read('Message.flash');
            $expectedFlashMessage = array(
                'message' => 'Chairman report was not deleted',
                'element' => 'flash_failure',
                'params' => array()
            );

            $this->assertEqual($flashMessage, $expectedFlashMessage);

            $this->assertEqual($this->FiledDocuments->redirectUrl, array('action' => 'index'));


       }
}
?>