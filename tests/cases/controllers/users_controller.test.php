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
    function startCase() {
        Configure::write('Acl.database', 'test');
    }

    function endCase() {
        Configure::write('Acl.database', 'default');
    }

    function startTest() {
        $this->Users =& new TestUsersController();
        $this->Users->constructClasses();
        $this->Users->params['controller'] = 'users';
    }

    function endTest() {
        unset($this->Users);
        ClassRegistry::flush();
    }

    // function testAdminIndexWithoutSearch() {
    //     $this->Users->Session->write('Auth.User', array(
    //         'id' => 1,
    //         'role_id' => 2,
    //         'username' => 'bcordell',
    //         'location_id' => 1
    //     ));
    //     debug($this->testAction('/admin/users'));
    // }

    function testAdminAddWithValidData() {
        $this->Users->Session->write('Auth.User', array(
            'id' => 1,
            'role_id' => 2,
            'username' => 'bcordell',
            'location_id' => 1
        ));

        $this->Users->data = array(
            'User' => array(
                'role_id' => 2,
                'firstname' => 'valid',
                'lastname' => 'user',
                'middle_initial' => 'D',
                'ssn' => '999119999',
                'ssn_confirm' => '999119999',
                'username' => 'vuser',
                'password' => 'asd123',
                'address_1' => '123 main st',
                'address_2' => '',
                'city' => 'spring hill',
                'state' => 'fl',
                'zip' => '34609',
                'phone' => '3521231234',
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
        $this->Users->Session->write('Auth.User', array(
            'id' => 1,
            'role_id' => 2,
            'username' => 'bcordell',
            'location_id' => 1
        ));

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
        $this->Users->Session->write('Auth.User', array(
            'id' => 1,
            'role_id' => 2,
            'username' => 'bcordell',
            'location_id' => 1
        ));

        $this->Users->admin_edit();
        $this->assertFlashMessage($this->Users, 'Invalid customer', 'flash_failure');

        $this->Users->admin_edit(2);
        $this->assertFlashMessage($this->Users, 'Invalid customer', 'flash_failure');
    }

    function testAdminEditWithValidData() {
        $this->Users->Session->write('Auth.User', array(
            'id' => 1,
            'role_id' => 2,
            'username' => 'bcordell',
            'location_id' => 1
        ));

        $this->Users->data = array(
            'User' => array(
                'firstname' => 'Valid',
                'lastname' => 'Name',
                'ssn' => '999999999'
            )
        );
        $this->Users->admin_edit(1);
        $this->assertFlashMessage($this->Users, 'The customer has been saved', 'flash_success');
    }

    function testAdminEditWithInvalidData() {
        $this->Users->Session->write('Auth.User', array(
            'id' => 1,
            'role_id' => 2,
            'username' => 'bcordell',
            'location_id' => 1
        ));

        $this->Users->data = array(
            'User' => array(
                'firstname' => ''
            )
        );
        $this->Users->admin_edit(4);
        $this->assertFlashMessage($this->Users, 'The customer could not be saved. Please, try again.', 'flash_failure');
    }

}
?>
