<?php
/* HelpfulArticle Test cases generated on: 2011-03-07 20:29:21 : 1299529761*/
App::import('Model', 'HelpfulArticle');
App::import('Lib', 'AtlasTestCase');
class FtpDocumentScannerTestCase extends AtlasTestCase {
	function startTest() {
		$this->FtpDocumentScanner =& ClassRegistry::init('FtpDocumentScanner');
	}

	function endTest() {
		unset($this->FtpDocumentScanner);
		ClassRegistry::flush();
	}
	
	function testValidation() {
		$this->FtpDocumentScanner->create();
		
		$invalidRecordInvalidIp = array(
			'FtpDocumentScanner' => array(
				'device_ip' => 'asdf',
				'device_name' => 'Awesome-o 3000',
				'location_id' => 1,
				'deleted' => 0
			)		
		);
		
		$invalidRecordNoIp = array(
			'FtpDocumentScanner' => array(
				'device_ip' => '',
				'device_name' => 'Awesome-o 3000',
				'location_id' => 1,
				'deleted' => 0
			)
		);
		
		$invalidRecordNoName = array(
			'FtpDocumentScanner' => array(
				'device_ip' => '192.168.1.2',
				'device_name' => '',
				'location_id' => 1,
				'deleted' => 0
			)
		);
		
		$invalidRecordNoLocation = array(
			'FtpDocumentScanner' => array(
				'device_ip' => '192.168.1.2',
				'device_name' => 'Awesome-o 3000',
				'location_id' => '',
				'deleted' => 0
			)
		);
		
		$this->assertFalse($this->FtpDocumentScanner->save($invalidRecordInvalidIp));
		$this->assertFalse($this->FtpDocumentScanner->save($invalidRecordNoIp));
		$this->assertFalse($this->FtpDocumentScanner->save($invalidRecordNoName));
		$this->assertFalse($this->FtpDocumentScanner->save($invalidRecordNoLocation));
	}

}
?>