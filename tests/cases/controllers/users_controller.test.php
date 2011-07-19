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

    function testAdminIndexWithoutSearch() {
        $this->Users->Session->write('Auth.User', array(
            'id' => 1,
            'role_id' => 2,
            'username' => 'bcordell',
            'location_id' => 1
        ));

        $this->Users->params = Router::parse('/admin/users');
        $this->Users->params['url']['url'] = 'admin/users';
        $this->Users->params['form'] = array();
        $this->Users->admin_index();

        $users = Set::extract('/users/User', $this->Users->viewVars);
        // debug($users);

        // make sure there are no users with roles higher than 1
        $this->assertEqual(Set::extract('/User[role_id>1]', $users), array());

        $this->assertEqual(Set::extract('/User[1]/firstname', $users), array('George'));
        $this->assertEqual(Set::extract('/User[2]/firstname', $users), array('John'));
        $this->assertEqual(Set::extract('/User[3]/firstname', $users), array('Daffy'));
        $this->assertEqual(Set::extract('/User[4]/firstname', $users), array('Slim'));
        $this->assertEqual(Set::extract('/User[5]/firstname', $users), array('Bob'));
        $this->assertEqual(Set::extract('/User[6]/firstname', $users), array('Roger'));
        $this->assertEqual(Set::extract('/User[7]/firstname', $users), array('Daniel'));
        $this->assertEqual(Set::extract('/User[8]/firstname', $users), array('Daniel'));
    }

    function testAdminIndexWithSearch() {
        $this->Users->Session->write('Auth.User', array(
            'id' => 1,
            'role_id' => 2,
            'username' => 'bcordell',
            'location_id' => 1
        ));

        $this->Users->params = Router::parse('/admin/users');
        $this->Users->params['url']['url'] = 'admin/users';
        $this->Users->params['form'] = array(
            'search_by1' => 'firstname',
            'search_scope1' => 'containing',
            'search_term1' => 'dan',
            'search_by2' => '',
            'search_scope2' => '',
            'search_term2' => ''
        );
        $this->Users->admin_index();

        $users = Set::extract('/users/User', $this->Users->viewVars);
        // debug($users);

        $expected = array(
            array(
                'User' => array(
                    'id' => 9,
                    'role_id' => 1,
                    'firstname' => 'Daniel',
                    'lastname' => 'Smith',
                    'middle_initial' => 'A',
                    'surname' => '',
                    'ssn' => '123441234',
                    'username' => 'smith',
                    'password' => 1234,
                    'address_1' => '123 main st',
                    'city' => 'spring hill',
                    'county' => '',
                    'state' => 'fl',
                    'zip' => 34609,
                    'phone' => 3525551234,
                    'alt_phone' => '',
                    'gender' => 'Male',
                    'dob' => '09/22/2010',
                    'email' => 'danieltest@teststuff.com',
                    'language' => '',
                    'ethnicity' => '',
                    'race' => '',
                    'organization' => '',
                    'disabled' => 0,
                    'signature' => 1,
                    'location_id' => 1,
                    'signature_created' => '2010-09-22 15:02:21',
                    'signature_modified' => '2010-09-22 15:02:21',
                    'created' => '09/22/2010 - 03:02 pm',
                    'modified' => '09/22/2010 - 03:02 pm',
                )
            ),
            array(
                'User' => array(
                    'id' => 10,
                    'role_id' => 1,
                    'firstname' => 'Daniel',
                    'lastname' => 'Test',
                    'middle_initial' => 'A',
                    'surname' => '',
                    'ssn' => '123441234',
                    'username' => 'test',
                    'password' => 1234,
                    'address_1' => '123 main st',
                    'city' => 'spring hill',
                    'county' => '',
                    'state' => 'fl',
                    'zip' => 34609,
                    'phone' => 3525551234,
                    'alt_phone' => '',
                    'gender' => 'Male',
                    'dob' => '09/22/2010',
                    'email' => 'danieltest@teststuff.com',
                    'language' => '',
                    'ethnicity' => '',
                    'race' => '',
                    'organization' => '',
                    'disabled' => 0,
                    'signature' => 1,
                    'location_id' => 1,
                    'signature_created' => '2010-09-22 15:02:21',
                    'signature_modified' => '2010-09-22 15:02:21',
                    'created' => '09/22/2010 - 03:02 pm',
                    'modified' => '09/22/2010 - 03:02 pm'
                )
            )
        );

        $this->assertEqual($users, $expected);

        // run a second search, this time with a last name
        // it should only return the one daniel this time
        $this->Users->params['form'] = array(
            'search_by1' => 'firstname',
            'search_scope1' => 'containing',
            'search_term1' => 'da',
            'search_by2' => 'lastname',
            'search_scope2' => 'matching exactly',
            'search_term2' => 'smith'
        );
        $this->Users->admin_index();

        $users = Set::extract('/users/User', $this->Users->viewVars);
        $this->assertEqual($users[0], $expected[0]);
    }

    function testAdminIndexWithSearchSsn() {
        $this->Users->Session->write('Auth.User', array(
            'id' => 1,
            'role_id' => 2,
            'username' => 'bcordell',
            'location_id' => 1
        ));

        $this->Users->params = Router::parse('/admin/users');
        $this->Users->params['url']['url'] = 'admin/users';
        $this->Users->params['form'] = array(
            'search_by1' => 'fullssn',
            'search_scope1' => 'containing',
            'search_term1' => '1234',
            'search_by2' => '',
            'search_scope2' => '',
            'search_term2' => ''
        );
        $this->Users->admin_index();

        $users = Set::extract('/users/User', $this->Users->viewVars);
        $this->assertEqual(count($users), 8);

        $this->Users->params['form'] = array(
            'search_by1' => 'fullssn',
            'search_scope1' => 'matching exactly',
            'search_term1' => '123441244',
            'search_by2' => '',
            'search_scope2' => '',
            'search_term2' => ''
        );
        $this->Users->admin_index();

        $users = Set::extract('/users/User', $this->Users->viewVars);
        $this->assertEqual(count($users), 1);
        $this->assertEqual($users[0]['User']['firstname'], 'Slim');

        $this->Users->params['form'] = array(
            'search_by1' => 'last4',
            'search_scope1' => 'containing',
            'search_term1' => '12',
            'search_by2' => '',
            'search_scope2' => '',
            'search_term2' => ''
        );
        $this->Users->admin_index();

        $users = Set::extract('/users/User', $this->Users->viewVars);
        $this->assertEqual(count($users), 8);

        $this->Users->params['form'] = array(
            'search_by1' => 'last4',
            'search_scope1' => 'matching exactly',
            'search_term1' => '1244',
            'search_by2' => '',
            'search_scope2' => '',
            'search_term2' => ''
        );
        $this->Users->admin_index();

        $users = Set::extract('/users/User', $this->Users->viewVars);
        $this->assertEqual(count($users), 1);
        $this->assertEqual($users[0]['User']['firstname'], 'Slim');
    }

    function testAdminIndexSsnObscurity() {
        $this->Users->params = Router::parse('/admin/users');
        $this->Users->params['url']['url'] = 'admin/users';
        $this->Users->Session->write('Auth.User', array(
            'id' => 1,
            'role_id' => 2,
            'username' => 'bcordell',
            'location_id' => 1
        ));
        $this->Users->admin_index();
        $this->assertTrue($this->Users->viewVars['canViewFullSsn']);

        $this->Users->Session->destroy();
        $this->Users->Session->write('Auth.User', array(
            'id' => 1,
            'role_id' => 6,
            'username' => 'bcordell',
            'location_id' => 1
        ));
        $this->Users->admin_index();
        $this->assertFalse($this->Users->viewVars['canViewFullSsn']);

        debug($this->Users->output);
    }

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
