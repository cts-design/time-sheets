<?php
/* Users Test cases generated on: 2010-09-22 15:09:30 : 1285168950*/
App::import('Controller', 'Users');
App::import('Lib', 'AtlasTestCase');
class TestUsersController extends UsersController {
    var $autoRender = false;

    function redirect($url, $status = null, $exit = true) {
        $this->redirectUrl = $url;
    }
}

class UsersControllerTestCase extends AtlasTestCase {
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

    function startCase() {
        Configure::write('Acl.database', 'test');
    }

    function endCase() {
        Configure::write('Acl.database', 'default');
    }

    function startTest() {
        $this->Users =& new TestUsersController();
        $this->Users->constructClasses();
    }

    function endTest() {
        unset($this->Users);
        ClassRegistry::flush();
    }

    function testAdminAddWithValidData() {
        $this->Users->data = array(
            'User' => array(
                'role_id' => 2,
                'firstname' => 'brandon',
                'lastname' => 'cordell',
                'middle_initial' => 'D',
                'ssn' => '123456789',
                'ssn_confirm' => '123456789',
                'username' => 'validuser',
                'password' => 'asd123',
                'address_1' => '123 main st',
                'address_2' => '',
                'city' => 'spring hill',
                'state' => 'fl',
                'zip' => '34609',
                'phone' => '',
                'alt_phone' => '',
                'gender' => 'Male',
                'dob' => '01/10/1986',
                'email' => 'brandonc@gmail.com',
                'location_id' => '1',
                'signature_created' => '2010-09-22 15:02:21',
                'signature_modified' => '2010-09-22 15:02:21',
                'created' => '2010-09-22 15:02:21',
                'modified' => '2010-09-22 15:02:21'
            )
        );

        $this->Users->admin_add();
        $this->assertFlashMessage($this->Users, 'The customer has been saved', 'flash_success');
    }

    function testAdminAddWithInvalidData() {
        $this->Users->data = array(
            'User' => array(
                'name' => '',
                'created' => '0000-00-00 00:00:00',
                'modified' => '0000-00-00 00:00:00'
            )
        );

        $this->Users->admin_add();
        $this->assertFlashMessage($this->Users, 'The customer could not be saved. Please, try again.', 'flash_failure');
    }

    function testAdminEditWithInvalidId() {
        $this->Users->admin_edit();
        $this->assertFlashMessage($this->Users, 'Invalid customer', 'flash_failure');

        $this->Users->admin_edit(2);
        $this->assertFlashMessage($this->Users, 'Invalid customer', 'flash_failure');
    }

    function testAdminEditWithValidData() {
        $this->Users->data = array(
            'User' => array(
                'firstname' => 'Valid'
            )
        );
        $this->Users->admin_edit(1);
        $this->assertFlashMessage($this->Users, 'The customer has been saved', 'flash_success');
    }

    function testAdminEditWithInvalidData() {
        $this->Users->data = array(
            'User' => array(
                'name' => ''
            )
        );
        $this->Users->admin_edit(4);
        $this->assertFlashMessage($this->Users, 'The customer could not be saved. Please, try again.', 'flash_failure');
    }

    function testAdminDeleteWithInvalidId() {
        $this->Users->admin_delete();
        $this->assertFlashMessage($this->Users, 'Customer was not deleted', 'flash_failure');
    }

    function testAdminDelete() {
        $this->Users->admin_delete(1);
        $this->assertFlashMessage($this->Users, 'Customer was not deleted', 'flash_failure');
    }
}
?>