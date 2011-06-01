<?php

/**
 * @author Brandon Cordell
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
class AroFixture extends CakeTestFixture {
	var $name = 'Aro';

	var $fields = array(
            'id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
            'parent_id' => array('type'=>'integer', 'null' => true, 'default' => NULL, 'length' => 10),
            'model' => array('type'=>'string', 'null' => true),
            'foreign_key' => array('type'=>'integer', 'null' => true, 'default' => NULL, 'length' => 10),
            'alias' => array('type'=>'string', 'null' => true),
            'lft' => array('type'=>'integer', 'null' => true, 'default' => NULL, 'length' => 10),
            'rght' => array('type'=>'integer', 'null' => true, 'default' => NULL, 'length' => 10),
            'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
	);

	var $records = array(
            array(
                'id' => 67,
                'parent_id' => NULL,
                'model' => 'Role',
                'foreign_key' => 1,
                'alias' => NULL,
                'lft' => 1,
                'rght' => 7
            ),
            array(
                'id' => 68,
                'parent_id' => NULL,
                'model' => 'Role',
                'foreign_key' => 2,
                'alias' => NULL,
                'lft' => 8,
                'rght' => 9
            ),
            array(
                'id' => 69,
                'parent_id' => NULL,
                'model' => 'Role',
                'foreign_key' => 3,
                'alias' => NULL,
                'lft' => 10,
                'rght' => 11
            ),
            array(
                'id' => 70,
                'parent_id' => 68,
                'model' => 'User',
                'foreign_key' => 2,
                'alias' => NULL,
                'lft' => 3,
                'rght' => 4
            ),
            array(
                'id' => 71,
                'parent_id' => 68,
                'model' => 'User',
                'foreign_key' => 2,
                'alias' => NULL,
                'lft' => 5,
                'rght' => 6
            ),            
	);
}

?>
