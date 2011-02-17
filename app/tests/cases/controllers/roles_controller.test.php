<?php
/* Roles Test cases generated on: 2010-11-10 16:11:22 : 1289404882*/
App::import('Controller', 'Roles');
App::import('Component', 'DebugKit.Toolbar');
class TestRolesController extends RolesController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class RolesControllerTestCase extends CakeTestCase {
	var $fixtures = array(
            'app.aco',
            'app.aro',
            'app.aros_aco',
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
		$this->Roles =& new TestRolesController();
		$this->Roles->constructClasses();
        Configure::write('Acl.database', 'test');
    }

	function endTest() {
		unset($this->Roles);
		ClassRegistry::flush();
	}

	function testAdminAddWithValidData() {
            $this->Roles->data = array(
                'Role' => array(
                    'name' => 'new custom role',
                    'created' => '0000-00-00 00:00:00',
                    'modified' => '0000-00-00 00:00:00'
                )
            );

            $this->Roles->admin_add();

            $flashMessage = $this->Roles->Session->read('Message.flash');
            $expectedFlashMessage = array(
                'message' => 'The role has been saved',
                'element' => 'flash_success',
                'params' => array()
            );

            $this->assertEqual($flashMessage, $expectedFlashMessage);
	}

	function testAdminAddWithInvalidData() {
            $this->Roles->data = array(
                'Role' => array(
                    'name' => '',
                    'created' => '0000-00-00 00:00:00',
                    'modified' => '0000-00-00 00:00:00'
                )
            );

            $this->Roles->admin_add();

            $flashMessage = $this->Roles->Session->read('Message.flash');
            $expectedFlashMessage = array(
                'message' => 'The role could not be saved. Please, try again.',
                'element' => 'flash_failure',
                'params' => array()
            );

            $this->assertEqual($flashMessage, $expectedFlashMessage);
	}

        function testAdminEditWithInvalidId() {
            $this->Roles->admin_edit();

            $flashMessage = $this->Roles->Session->read('Message.flash');
            $expectedFlashMessage = array(
                'message' => 'Invalid role',
                'element' => 'flash_failure',
                'params' => array()
            );
            $this->assertEqual($flashMessage, $expectedFlashMessage);

            $this->Roles->admin_edit(2);

            $flashMessage = $this->Roles->Session->read('Message.flash');
            $expectedFlashMessage = array(
                'message' => 'Invalid role',
                'element' => 'flash_failure',
                'params' => array()
            );
            $this->assertEqual($flashMessage, $expectedFlashMessage);
        }

        function testAdminEditWithValidData() {
            $this->Roles->data = array(
                'Role' => array(
                    'name' => 'New Valid Role Name'
                )
            );
            $this->Roles->admin_edit(4);

            $flashMessage = $this->Roles->Session->read('Message.flash');
            $expectedFlashMessage = array(
                'message' => 'The role has been saved',
                'element' => 'flash_success',
                'params' => array()
            );
            $this->assertEqual($flashMessage, $expectedFlashMessage);
        }

        function testAdminEditWithInvalidData() {
            $this->Roles->data = array(
                'Role' => array(
                    'name' => ''
                )
            );
            $this->Roles->admin_edit(4);

            $flashMessage = $this->Roles->Session->read('Message.flash');
            $expectedFlashMessage = array(
                'message' => 'The role could not be saved. Please, try again.',
                'element' => 'flash_failure',
                'params' => array()
            );
            $this->assertEqual($flashMessage, $expectedFlashMessage);
        }

        function testAdminDeleteWithInvalidId() {
            $this->Roles->admin_delete();

            $flashMessage = $this->Roles->Session->read('Message.flash');
            $expectedFlashMessage = array(
                'message' => 'Role was not deleted',
                'element' => 'flash_failure',
                'params' => array()
            );
            $this->assertEqual($flashMessage, $expectedFlashMessage);
        }

        function testAdminDelete() {
            $this->Roles->admin_delete(1);

            $flashMessage = $this->Roles->Session->read('Message.flash');
            $expectedFlashMessage = array(
                'message' => 'Role was not deleted',
                'element' => 'flash_failure',
                'params' => array()
            );
            $this->assertEqual($flashMessage, $expectedFlashMessage);
        }
}
?>