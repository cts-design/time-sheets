<?php
/* Role Test cases generated on: 2010-11-10 16:11:26 : 1289404826*/
App::import('Model', 'Role');

class RoleTestCase extends CakeTestCase {
	var $fixtures = array(
            'app.acos',
            'app.aros',
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
		$this->Role =& ClassRegistry::init('Role');
	}

	function endTest() {
		unset($this->Role);
		ClassRegistry::flush();
	}

        function testValidation() {
            $this->Role->create();

            $invalidRecordNoName = array(
                'Role' => array(
                    'name' => ''
                )
            );

            $this->assertFalse($this->Role->save($invalidRecordNoName));
        }

        function testFormatDateTimeAfterFind() {
            $result = $this->Role->find('first');
            $exptected = array(
                'id' => 1,
                'name' => 'Customers',
                'created' => '11/10/2010 - 04:00 pm',
                'modified' => '11/10/2010 - 04:00 pm'
            );
            $this->assertEqual($result['Role'], $exptected);
        }

}
?>