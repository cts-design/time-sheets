<?php
/* Rfps Test cases generated on: 2011-02-28 17:48:26 : 1298915306*/
App::import('Controller', 'Rfps');
App::import('Lib', 'AtlasTestCase');
class TestRfpsController extends RfpsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class RfpsControllerTestCase extends AtlasTestCase {
	function startTest() {
		$this->Rfps =& new TestRfpsController(array('components' => array('RequestHandler')));
		$this->Rfps->constructClasses();
		$this->RequestHandler =& $this->Rfps->RequestHandler;
	}

	function endTest() {
		unset($this->RequestHandler);
		unset($this->Rfps);
		ClassRegistry::flush();
	}

	function testIndex() {
		
	}

	function testAdminCreateWithValidData() {
		App::import('Vendor', 'DebugKit.FireCake');
		
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$this->Rfps->params = Router::parse('/admin/rfps/create');
		$this->Rfps->params['form'] =  array('rfps' => '{"title":"test","byline":"test","description":"asdf","deadline":"","expires":"","contact_email":"sdd@sdfsd.com"}');
		
		$this->Rfps->Component->initialize($this->Rfps);
		$this->Rfps->beforeFilter();
		$this->Rfps->Component->startup($this->Rfps);
		$result = json_decode($this->Rfps->admin_create(), true);
		
		$this->assertEqual($this->Rfps->layout, $this->RequestHandler->ajaxLayout);
		$this->assertTrue(array_key_exists('success', $result));
		$this->assertTrue($result['success'], 'true');
	}
	function testAdminCreateWithInvalidData() {
		
	}
	
	function testAdminRead() {
		
	}

	function testAdminUpdate() {

	}

	function testAdminDestroy() {

	}

}
?>