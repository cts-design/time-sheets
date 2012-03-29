<?php

class AuditFixture extends CakeTestFixture {
	var $name = 'Audit';
	var $import = array('model' => 'Audit');

	var $records = array(
		array(
            'id' => 1,
            'name' => 'Audit 1',
            'start_date' => '2012-12-01',
            'end_date' => '2012-12-31',
            'disabled' => 0,
            'created' => '2012-12-01 08:00:00',
            'modified' => '2012-12-01 08:00:00'
		),
		array(
            'id' => 2,
            'name' => 'Audit 2 Deleted',
            'start_date' => '2012-12-10',
            'end_date' => '2012-12-20',
            'disabled' => 1,
            'created' => '2012-12-01 08:00:00',
            'modified' => '2012-12-01 08:00:00'
		)
    );
}

