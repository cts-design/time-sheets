<?php
/* ProgramResponseDoc Test cases generated on: 2011-04-06 18:32:37 : 1302114757*/
App::import('Model', 'ProgramResponseDoc');
App::import('Lib', 'AtlasTestCase');
class ProgramResponseDocTestCase extends AtlasTestCase {
	
	function startTest() {
		$this->ProgramResponseDoc =& ClassRegistry::init('ProgramResponseDoc');
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
		
		$result = $this->ProgramResponseDoc->processResponseDoc($data, $user);
		$this->assertEqual($result['cat_id'], 252);
		$this->assertEqual($result['program_id'], 1);
	}

	function testProcessResponseDocRejected(){
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
		$data['FiledDocument']['cat_3'] = 254;
		$data['FiledDocument']['user_id'] = $user['User']['id'];
		$data['FiledDocument']['last_activity_admin_id'] = 2;
		$data['FiledDocument']['filed_location_id'] = 1;
		
		$result = $this->ProgramResponseDoc->processResponseDoc($data, $user);
		$this->assertEqual($result['cat_id'], 254);
		$this->assertEqual($result['program_id'], 1);
	}

	function testProcessResponseDocComplete(){
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
		$data['FiledDocument']['cat_3'] = 253;
		$data['FiledDocument']['user_id'] = $user['User']['id'];
		$data['FiledDocument']['last_activity_admin_id'] = 2;
		$data['FiledDocument']['filed_location_id'] = 1;
		
		$result = $this->ProgramResponseDoc->processResponseDoc($data, $user);
		$this->assertEqual($result['cat_id'], 253);
		$this->assertEqual($result['program_id'], 1);
	}

	function testGetFiledResponseDocCats() {
		$expectedResult = array(1 => 253, 2 => 252);
		$result = $this->ProgramResponseDoc->getFiledResponseDocCats(1, 1);
		$this->assertEqual($result, $expectedResult);
	}	

	function endTest() {
		unset($this->ProgramResponseDoc);
		ClassRegistry::flush();
	}

}
?>