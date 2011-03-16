<?php
/* FtpDocumentScanners Test cases generated on: 2010-11-05 19:11:47 : 1288983647*/
App::import('Controller', 'FtpDocumentScanners');
App::import('Lib', 'AtlasTestCase');
class TestFtpDocumentScannersController extends FtpDocumentScannersController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class FtpDocumentScannersControllerTestCase extends AtlasTestCase {
	function startTest() {
		$this->FtpDocumentScanners =& new TestFtpDocumentScannersController();
		$this->FtpDocumentScanners->constructClasses();
		$this->FtpDocumentScanners->Component->initialize($this->FtpDocumentScanners);
		$this->FtpDocumentScanners->Session->write('Auth.User', array(
			'id' => 1,
			'username' => 'bcordell'
		));
	}

	function endTest() {
		unset($this->FtpDocumentScanners);
		ClassRegistry::flush();
	}

	function testAdminAddWithValidRecord() {
		$this->FtpDocumentScanners->data = array(
			'FtpDocumentScanner' =>	array(
				'device_ip' => '192.168.1.2',
				'device_name' => 'Awesome-o 6000',
				'location_id' => 1,
				'deleted' => 0,
			),
		);
		
		$this->FtpDocumentScanners->params = Router::parse('/admin/ftp_document_scanners/add');
		$this->FtpDocumentScanners->beforeFilter();
		$this->FtpDocumentScanners->Component->startup($this->FtpDocumentScanners);
		$this->FtpDocumentScanners->admin_add();
		
		$this->assertFlashMessage($this->FtpDocumentScanners, 'The ftp document scanner has been saved', 'flash_success');
	}
	
	function testAdminAddWithInvalidRecord() {
		$this->FtpDocumentScanners->data = array(
			'FtpDocumentScanner' =>	array(
				'device_ip' => '',
				'device_name' => '',
				'location_id' => 1,
				'deleted' => 0,
			),
		);
		
		$this->FtpDocumentScanners->params = Router::parse('/admin/ftp_document_scanners/add');
		$this->FtpDocumentScanners->beforeFilter();
		$this->FtpDocumentScanners->Component->startup($this->FtpDocumentScanners);
		$this->FtpDocumentScanners->admin_add();
		
		$this->assertFlashMessage($this->FtpDocumentScanners, 'The ftp document scanner could not be saved. Please, try again.', 'flash_failure');
	}

	function testAdminEditWithNoId() {
		$this->FtpDocumentScanners->params = Router::parse('/admin/ftp_document_scanners/edit/');
		$this->FtpDocumentScanners->beforeFilter();
		$this->FtpDocumentScanners->Component->startup($this->FtpDocumentScanners);
		$this->FtpDocumentScanners->admin_edit();
		
		$this->assertFlashMessage($this->FtpDocumentScanners, 'Invalid ftp document scanner', 'flash_failure');		
	}

	function testAdminEdit() {
		$this->FtpDocumentScanners->data = array(
			'FtpDocumentScanner' =>	array(
				'id' => 1,
				'device_ip' => '192.168.1.1',
				'device_name' => 'Awesome-o 9000',
				'location_id' => 1,
				'deleted' => 0,
			),
		);
		
		$this->FtpDocumentScanners->params = Router::parse('/admin/ftp_document_scanners/edit/1');
		$this->FtpDocumentScanners->beforeFilter();
		$this->FtpDocumentScanners->Component->startup($this->FtpDocumentScanners);
		$this->FtpDocumentScanners->admin_edit();
		
		// was the record changed?
		$result = $this->FtpDocumentScanners->FtpDocumentScanner->read(null, 1);
		$this->assertEqual($result['FtpDocumentScanner']['device_name'], 'Awesome-o 9000');
		$this->assertFlashMessage($this->FtpDocumentScanners, 'The ftp document scanner has been saved', 'flash_success');
	}

	function testAdminDelete() {
		$this->FtpDocumentScanners->params = Router::parse('/admin/ftp_document_scanners/delete/1');
		$this->FtpDocumentScanners->beforeFilter();
		$this->FtpDocumentScanners->FtpDocumentScanner->recursive = -1;
		$this->FtpDocumentScanners->Component->startup($this->FtpDocumentScanners);
		$this->FtpDocumentScanners->admin_delete(1);
		
		$result = $this->FtpDocumentScanners->FtpDocumentScanner->read(null, 1);
		
		// was the record changed?
		$this->assertEqual($result['FtpDocumentScanner']['deleted'], 1);	
	}

}
?>