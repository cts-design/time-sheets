<?php
/* ProgramResponse Test cases generated on: 2011-03-28 21:10:45 : 1301346645*/
App::import('Model', 'ProgramResponse');
App::import('Lib', 'AtlasTestCase');
class ProgramResponseTestCase extends AtlasTestCase {
	
	function startTest() {
		$this->ProgramResponse =& ClassRegistry::init('ProgramResponse');
	}
	
	function testProcessResponseDoc(){
		$user = array('User' => array(		
				'id' => 9,
				'role_id' => 1,
				'firstname' => 'Daniel',
				'lastname' => 'Smith',
				'middle_initial' => 'A',
				'ssn' => '123441234',
	            'username' => 'smith',
				'password' => '1234',
				'address_1' => '123 main st',
				'address_2' => '',
				'city' => 'spring hill',
				'state' => 'fl',
				'zip' => '34609',
				'phone' => '3525551234',
				'alt_phone' => '',
				'gender' => 'Male',
				'dob' => '2010-09-22',
				'email' => 'danieltest@teststuff.com',
				'status' => 1,
				'deleted' => 0,
				'signature' => 1,
				'location_id' => '1',
				'signature_created' => '2010-09-22 15:02:21',
				'signature_modified' => '2010-09-22 15:02:21',
				'created' => '2010-09-22 15:02:21',
				'modified' => '2010-09-22 15:02:21'
				));
		$data['FiledDocument']['id'] = 10;
		$data['FiledDocument']['cat_1'] = 250;
		$data['FiledDocument']['cat_2'] = 251;
		$data['FiledDocument']['cat_3'] = 252;
		$data['FiledDocument']['user_id'] = $user['User']['id'];
		$data['FiledDocument']['last_activity_admin_id'] = 2;
		$data['FiledDocument']['filed_location_id'] = 1;
		
		$result = $this->ProgramResponse->processResponseDoc($data, $user);
		$this->assertEqual($result['cat_id'], 252);
		$this->assertEqual($result['program_id'], 1);
	}

	function endTest() {
		unset($this->ProgramResponse);
		ClassRegistry::flush();
	}

}
?>