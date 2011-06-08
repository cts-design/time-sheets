<?php

/**
 * @author Brandon Cordell
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
class ArosAcoFixture extends CakeTestFixture {
	var $name = 'ArosAco';

	var $fields = array(
        'id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
        'aro_id' => array('type'=>'integer', 'null' => false, 'length' => 10, 'key' => 'index'),
        'aco_id' => array('type'=>'integer', 'null' => false, 'length' => 10),
        '_create' => array('type'=>'string', 'null' => false, 'default' => '0', 'length' => 2),
        '_read' => array('type'=>'string', 'null' => false, 'default' => '0', 'length' => 2),
        '_update' => array('type'=>'string', 'null' => false, 'default' => '0', 'length' => 2),
        '_delete' => array('type'=>'string', 'null' => false, 'default' => '0', 'length' => 2),
        'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'ARO_ACO_KEY' => array('column' => array('aro_id', 'aco_id'), 'unique' => 1))
	);

	var $records = array(
        array(
            'id' => 4,
            'aro_id' => 67,
            'aco_id' => 1,
            '_create' => 1,
            '_read' => 1,
            '_update' => 1,
            '_delete' => 1
        ),
        array(
            'id' => 5,
            'aro_id' => 68,
            'aco_id' => 1,
            '_create' => 1,
            '_read' => 1,
            '_update' => 1,
            '_delete' => 1
        ),
        array(
            'id' => 6,
            'aro_id' => 69,
            'aco_id' => 1,
            '_create' => 1,
            '_read' => 1,
            '_update' => 1,
            '_delete' => 1
        ),
	);
}

?>