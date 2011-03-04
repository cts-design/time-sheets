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
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$this->Rfps->params = Router::parse('/admin/rfps/create');
		$this->Rfps->params['form'] =  array('rfps' => '{"title":"test","byline":"test","description":"asdf","deadline":"03/20/2011","expires":"03/20/2011","contact_email":"sdd@sdfsd.com"}');
		
		$this->Rfps->Component->initialize($this->Rfps);
		$this->Rfps->beforeFilter();
		$this->Rfps->Component->startup($this->Rfps);
		$result = json_decode($this->Rfps->admin_create(), true);
		
		$this->assertEqual($this->Rfps->layout, $this->RequestHandler->ajaxLayout);
		$this->assertTrue(array_key_exists('success', $result));
		$this->assertEqual($result['success'], true);
	}

	function testAdminCreateWithInvalidData() {
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$this->Rfps->params = Router::parse('/admin/rfps/create');
		$this->Rfps->params['form'] =  array('rfps' => '{"title":""}');
		
		$this->Rfps->Component->initialize($this->Rfps);
		$this->Rfps->beforeFilter();
		$this->Rfps->Component->startup($this->Rfps);
		$result = json_decode($this->Rfps->admin_create(), true);
		
		$this->assertEqual($this->Rfps->layout, $this->RequestHandler->ajaxLayout);
		$this->assertTrue(array_key_exists('success', $result));
		$this->assertEqual($result['success'], false);		
	}
	
	function testAdminRead() {
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$this->Rfps->params = Router::parse('/admin/rfps/read');
		
		$this->Rfps->Component->initialize($this->Rfps);
		$this->Rfps->beforeFilter();
		$this->Rfps->Component->startup($this->Rfps);
		$result = json_decode($this->Rfps->admin_read(), true);

		$this->assertNull($result);
	}

	function testAdminUpdate() {
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$this->Rfps->params = Router::parse('/admin/rfps/update');
		$this->Rfps->params['form'] =  array('rfps' => '{"title":"Lorem ipsum dolor sit amet","byline":"This RFP has changed","id":"1"}');
		
		$this->Rfps->Component->initialize($this->Rfps);
		$this->Rfps->beforeFilter();
		$this->Rfps->Component->startup($this->Rfps);
		
		// verify the record first
		$beforeUpdate = $this->Rfps->Rfp->findById(1);
		$expectedBefore = array(
			'Rfp' => array(
				'id' => 1,
				'title' => 'Lorem ipsum dolor sit amet',
				'byline' => 'Lorem ipsum dolor sit amet',
				'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
				'deadline' => '2011-02-28 17:47:47',
				'expires' => '2011-02-28 17:47:47',
				'contact_email' => 'Lorem ipsum dolor sit amet',
				'file' => 'Lorem ipsum dolor sit amet'	
			)
		);
		$this->assertEqual($beforeUpdate, $expectedBefore);
		
		$result = json_decode($this->Rfps->admin_update(), true);
		$this->assertTrue(array_key_exists('success', $result));
		$this->assertEqual($result['success'], true);
		$this->assertEqual($result['rfps']['byline'], 'This RFP has changed');
	}

	function testAdminDestroy() {
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$this->Rfps->params = Router::parse('/admin/rfps/destroy');
		$this->Rfps->params['form'] =  array('rfps' => '{"id":"1"}');
		
		$this->Rfps->Component->initialize($this->Rfps);
		$this->Rfps->beforeFilter();
		$this->Rfps->Component->startup($this->Rfps);
		
		// verify the record first
		$beforeUpdate = $this->Rfps->Rfp->findById(1);
		$expectedBefore = array(
			'Rfp' => array(
				'id' => 1,
				'title' => 'Lorem ipsum dolor sit amet',
				'byline' => 'Lorem ipsum dolor sit amet',
				'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
				'deadline' => '2011-02-28 17:47:47',
				'expires' => '2011-02-28 17:47:47',
				'contact_email' => 'Lorem ipsum dolor sit amet',
				'file' => 'Lorem ipsum dolor sit amet'	
			)
		);
		$this->assertEqual($beforeUpdate, $expectedBefore);
		
		$result = json_decode($this->Rfps->admin_destroy(), true);
		$this->assertTrue(array_key_exists('success', $result));
		$this->assertEqual($result['success'], true);
	}

}
?>