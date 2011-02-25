<?php
/* User Test cases generated on: 2010-09-22 15:09:21 : 1285167741*/
App::import('Model', 'User');
App::import('Component', 'Security');
App::import('Lib', 'AtlasTestCase');
class UserTestCase extends AtlasTestCase {
	var $fixtures = array(
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

        var $valid_record = array(
            'User' => array(
                'role_id' => 1,
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

	function startTest() {
		$this->User =& ClassRegistry::init('User');
        $this->User->create();
	}

	function endTest() {
		unset($this->User);
		ClassRegistry::flush();
	}

        function testNameValidation() {
            $invalidRecordNoFirstName = $this->valid_record;
            $invalidRecordNoFirstName['User']['firstname'] = '';

            $invalidRecordNoLastName = $this->valid_record;
            $invalidRecordNoLastName['User']['lastname'] = '';

            $invalidRecordLongFirstName = $this->valid_record;
            $invalidRecordLongFirstName['User']['firstname'] = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';

            $invalidRecordLongLastName = $this->valid_record;
            $invalidRecordLongLastName['User']['lastname'] = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';

            $this->assertFalse($this->User->save($invalidRecordNoFirstName));
            $this->assertFalse($this->User->save($invalidRecordNoLastName));
            $this->assertFalse($this->User->save($invalidRecordLongFirstName));
            $this->assertFalse($this->User->save($invalidRecordLongLastName));
        }

        function testUsernameValidation() {
            $invalidRecordNoUsername = $this->valid_record;
            $invalidRecordNoUsername['User']['username'] = '';

            $invalidRecordShortUsername = $this->valid_record;
            $invalidRecordShortUsername['User']['username'] = 'test';

            $invalidRecordLongUsername = $this->valid_record;
            $invalidRecordLongUsername['User']['username'] = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';

            $this->assertFalse($this->User->save($invalidRecordNoUsername));
            $this->assertFalse($this->User->save($invalidRecordShortUsername));
            $this->assertFalse($this->User->save($invalidRecordLongUsername));
        }

        function testPasswordValidation() {
            $invalidRecordNoPassword = $this->valid_record;
            $invalidRecordNoPassword['User']['pass'] = '';

            $invalidRecordShortPassword = $this->valid_record;
            $invalidRecordShortPassword['User']['pass'] = 'test';

            $invalidRecordLongPassword = $this->valid_record;
            $invalidRecordLongPassword['User']['pass'] = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';

            $this->assertFalse($this->User->save($invalidRecordNoPassword));
            $this->assertFalse($this->User->save($invalidRecordShortPassword));
            $this->assertFalse($this->User->save($invalidRecordLongPassword));
        }

        function testSsnValidation() {
            $invalidRecordNoSsn = $this->valid_record;
            $invalidRecordNoSsn['User']['ssn'] = '';

            $invalidRecordLettersInSsn = $this->valid_record;
            $invalidRecordLettersInSsn['User']['ssn'] = 'aaaaaaaaa';

            $invalidRecordNonUnique = $this->valid_record;
            $invalidRecordNonUnique['User']['ssn'] = '222222222';

            $invalidRecordTooShort = $this->valid_record;
            $invalidRecordTooShort['User']['ssn'] = '1234';

            $this->assertFalse($this->User->save($invalidRecordNoSsn));
            $this->assertFalse($this->User->save($invalidRecordLettersInSsn));
            $this->assertFalse($this->User->save($invalidRecordNonUnique));
            $this->assertFalse($this->User->save($invalidRecordTooShort));
        }

        function testSsnConfirmValidation() {
            $invalidRecordNoSsn = $this->valid_record;
            $invalidRecordNoSsn['User']['ssn_confirm'] = '';

            $invalidRecordLettersInSsn = $this->valid_record;
            $invalidRecordLettersInSsn['User']['ssn_confirm'] = 'aaaaaaaaa';

            $invalidRecordNonUnique = $this->valid_record;
            $invalidRecordNonUnique['User']['ssn_confirm'] = '222222222';

            $invalidRecordTooShort = $this->valid_record;
            $invalidRecordTooShort['User']['ssn_confirm'] = '1234';

            $invalidRecordNonMatching = $this->valid_record;
            $invalidRecordNonMatching['User']['ssn_confirm'] = '123454321';

            $this->assertFalse($this->User->save($invalidRecordNoSsn));
            $this->assertFalse($this->User->save($invalidRecordLettersInSsn));
            $this->assertFalse($this->User->save($invalidRecordNonUnique));
            $this->assertFalse($this->User->save($invalidRecordTooShort));
            $this->assertFalse($this->User->save($invalidRecordNonMatching));
        }

        function testUserDataValidation() {
            $invalidRecordLettersInZip = $this->valid_record;
            $invalidRecordLettersInZip['User']['zip'] = 'aaaaaaaaa';

            $invalidRecordTooLong = $this->valid_record;
            $invalidRecordTooLong['User']['zip'] = '123456';

            $invalidRecordTooShort = $this->valid_record;
            $invalidRecordTooShort['User']['zip'] = '1234';

            $invalidRecordPhoneTooLong = $this->valid_record;
            $invalidRecordPhoneTooLong['User']['phone'] = '352555123435255512345';

            $invalidRecordAltPhoneTooLong = $this->valid_record;
            $invalidRecordAltPhoneTooLong['User']['alt_phone'] = '352555123435255512345';

            $invalidRecordNoGender = $this->valid_record;
            $invalidRecordNoGender['User']['gender'] = '';

            $invalidRecordNoDob = $this->valid_record;
            $invalidRecordNoDob['User']['dob'] = '';

            $invalidRecordDobWrongFormat = $this->valid_record;
            $invalidRecordDobWrongFormat['User']['dob'] = '2007-12-25';

            $invalidRecordNoLocationId = $this->valid_record;
            $invalidRecordNoLocationId['User']['location_id'] = '';

            $invalidRecordNoRoleId = $this->valid_record;
            $invalidRecordNoRoleId['User']['role_id'] = '';

            $this->assertFalse($this->User->save($invalidRecordLettersInZip));
            $this->assertFalse($this->User->save($invalidRecordTooLong));
            $this->assertFalse($this->User->save($invalidRecordTooShort));
            $this->assertFalse($this->User->save($invalidRecordPhoneTooLong));
            $this->assertFalse($this->User->save($invalidRecordAltPhoneTooLong));
            $this->assertFalse($this->User->save($invalidRecordNoGender));
            $this->assertFalse($this->User->save($invalidRecordNoDob));
            $this->assertFalse($this->User->save($invalidRecordDobWrongFormat));
            $this->assertFalse($this->User->save($invalidRecordNoLocationId));
            $this->assertFalse($this->User->save($invalidRecordNoRoleId));
        }

        function testEmailValidation() {
            $invalidRecordBadEmail = $this->valid_record;
            $invalidRecordBadEmail['User']['email'] = 'bob@bob';

            $invalidRecordNonUniqueEmail = $this->valid_record;
            $invalidRecordNonUniqueEmail['User']['email'] = 'brandonc@ctsfla.com';

            $this->assertFalse($this->User->save($invalidRecordBadEmail));
            $this->assertFalse($this->User->save($invalidRecordNonUniqueEmail));
        }
}
?>