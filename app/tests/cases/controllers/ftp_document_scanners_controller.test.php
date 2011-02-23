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