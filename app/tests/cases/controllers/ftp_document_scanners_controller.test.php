<?php
/* FtpDocumentScanners Test cases generated on: 2010-11-05 19:11:47 : 1288983647*/
App::import('Controller', 'FtpDocumentScanners');

class TestFtpDocumentScannersController extends FtpDocumentScannersController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class FtpDocumentScannersControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.ftp_document_scanner', 'app.location');

	function startTest() {
		$this->FtpDocumentScanners =& new TestFtpDocumentScannersController();
		$this->FtpDocumentScanners->constructClasses();
	}

	function endTest() {
		unset($this->FtpDocumentScanners);
		ClassRegistry::flush();
	}

	function testAdminIndex() {

	}

	function testAdminView() {

	}

	function testAdminAdd() {

	}

	function testAdminEdit() {

	}

	function testAdminDelete() {

	}

}
?>