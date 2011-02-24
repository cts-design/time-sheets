<?php
/* Role Fixture generated on: 2010-11-10 16:11:26 : 1289404826 */
class RoleFixture extends CakeTestFixture {
	var $name = 'Role';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
            array(
                    'id' => 1,
                    'name' => 'Customers',
                    'created' => '2010-11-10 16:00:26',
                    'modified' => '2010-11-10 16:00:26'
            ),
            array(
                    'id' => 2,
                    'name' => 'Super Admin',
                    'created' => '2010-11-10 16:00:26',
                    'modified' => '2010-11-10 16:00:26'
            ),
            array(
                    'id' => 3,
                    'name' => 'Admin',
                    'created' => '2010-11-10 16:00:26',
                    'modified' => '2010-11-10 16:00:26'
            ),
            array(
                    'id' => 4,
                    'name' => 'Custom 1',
                    'created' => '2010-11-10 16:00:26',
                    'modified' => '2010-11-10 16:00:26'
            ),
            array(
                    'id' => 5,
                    'name' => 'Custom 2',
                    'created' => '2010-11-10 16:00:26',
                    'modified' => '2010-11-10 16:00:26'
            ),
            array(
                    'id' => 6,
                    'name' => 'Custom 3',
                    'created' => '2010-11-10 16:00:26',
                    'modified' => '2010-11-10 16:00:26'
            )
	);
}
?>