<?php

App::import('Model', 'Audit');
App::import('Lib', 'AtlasTestCase');

class AuditTestCase extends AtlasTestCase {
    public function startTest() {
        $this->Audit =& ClassRegistry::init('Audit');
    }

    public function endTest() {
        unset($this->Audit);
        ClassRegistry::flush();
    }

    public function testValidation() {
        $this->Audit->create();
        $invalidData = array(
            'Audit' => array(
                'name' => '',
                'start_date' => '',
                'end_date' => ''
            )
        );

        $this->assertFalse($this->Audit->save($invalidData));
        $invalidFields = $this->Audit->invalidFields();
        $this->assertEqual($invalidFields['name'], 'An audit name is required');
        $this->assertEqual($invalidFields['start_date'], 'A start date is required');
        $this->assertEqual($invalidFields['end_date'], 'An end date is required');
    }
}

