<?php
/* Navigations Test cases generated on: 2011-02-04 19:52:09 : 1296849129*/
App::import('Controller', 'Navigations');
App::import('Lib', 'AtlasTestCase');
App::import('Vendor', 'DebugKit.FireCake');
class TestNavigationsController extends NavigationsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class NavigationsControllerTestCase extends AtlasTestCase {
	function startTest() {
		$this->Navigations =& new TestNavigationsController();
		$this->Navigations->constructClasses();
        $this->testController = $this->Navigations;
	}

	function endTest() {
		unset($this->Navigations);
		ClassRegistry::flush();
	}

	function testAdminIndex() {

	}

	function testAdminView() {

	}

	function testAdminCreateInternalUrl() {
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
        $this->Navigations->params = Router::parse('/admin/navigations/create');
        $this->Navigations->params['form'] = array(
            'name' => 'My New Link',
            'link' => 'my_new_link',
            'parentId' => 19
        );

        $this->Navigations->Component->initialize($this->Navigations);
        $this->Navigations->beforeFilter();
        $this->Navigations->Component->startup($this->Navigations);

        $result = json_decode($this->Navigations->admin_create(), true);
        $this->assertTrue($result['success']);
        $this->assertEqual($result['navigation']['title'], 'My New Link');
        $this->assertEqual($result['navigation']['link'], '/my_new_link');
	}

	function testAdminCreateExternalUrl() {
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
        $this->Navigations->params = Router::parse('/admin/navigations/create');
        $this->Navigations->params['form'] = array(
            'name' => 'Google',
            'link' => 'http://google.com',
            'parentId' => 19
        );

        $this->Navigations->Component->initialize($this->Navigations);
        $this->Navigations->beforeFilter();
        $this->Navigations->Component->startup($this->Navigations);

        $result = json_decode($this->Navigations->admin_create(), true);
        $this->assertTrue($result['success']);
        $this->assertEqual($result['navigation']['title'], 'Google');
        $this->assertEqual($result['navigation']['link'], 'http://google.com');
	}

	function testAdminCreateSecureExternalUrl() {
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
        $this->Navigations->params = Router::parse('/admin/navigations/create');
        $this->Navigations->params['form'] = array(
            'name' => 'Google',
            'link' => 'https://google.com',
            'parentId' => 19
        );

        $this->Navigations->Component->initialize($this->Navigations);
        $this->Navigations->beforeFilter();
        $this->Navigations->Component->startup($this->Navigations);

        $result = json_decode($this->Navigations->admin_create(), true);
        $this->assertTrue($result['success']);
        $this->assertEqual($result['navigation']['title'], 'Google');
        $this->assertEqual($result['navigation']['link'], 'https://google.com');
	}

	function testAdminCreateInternalModuleUrl() {
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
        $this->Navigations->params = Router::parse('/admin/navigations/create');
        $this->Navigations->params['form'] = array(
            'name' => 'Helpful Articles',
            'link' => '/pages/helpful_articles',
            'parentId' => 19
        );

        $this->Navigations->Component->initialize($this->Navigations);
        $this->Navigations->beforeFilter();
        $this->Navigations->Component->startup($this->Navigations);

        $result = json_decode($this->Navigations->admin_create(), true);
        $this->assertTrue($result['success']);
        $this->assertEqual($result['navigation']['title'], 'Helpful Articles');
        $this->assertEqual($result['navigation']['link'], '/pages/helpful_articles');
	}


	function testAdminUpdateInternalUrl() {
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
        $this->Navigations->params = Router::parse('/admin/navigations/update');
        $this->Navigations->params['form'] = array(
            'name' => 'My New Link',
            'link' => 'my_new_link',
            'parentId' => 19
        );

        $this->Navigations->Component->initialize($this->Navigations);
        $this->Navigations->beforeFilter();
        $this->Navigations->Component->startup($this->Navigations);

        $result = json_decode($this->Navigations->admin_update(), true);
        $this->assertTrue($result['success']);
        $this->assertEqual($result['navigation']['title'], 'My New Link');
        $this->assertEqual($result['navigation']['link'], '/my_new_link');
	}

	function testAdminUpdateExternalUrl() {
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
        $this->Navigations->params = Router::parse('/admin/navigations/update');
        $this->Navigations->params['form'] = array(
            'name' => 'Google',
            'link' => 'http://google.com',
            'parentId' => 19
        );

        $this->Navigations->Component->initialize($this->Navigations);
        $this->Navigations->beforeFilter();
        $this->Navigations->Component->startup($this->Navigations);

        $result = json_decode($this->Navigations->admin_update(), true);
        $this->assertTrue($result['success']);
        $this->assertEqual($result['navigation']['title'], 'Google');
        $this->assertEqual($result['navigation']['link'], 'http://google.com');
	}

	function testAdminUpdateSecureExternalUrl() {
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
        $this->Navigations->params = Router::parse('/admin/navigations/update');
        $this->Navigations->params['form'] = array(
            'name' => 'Google',
            'link' => 'https://google.com',
            'parentId' => 19
        );

        $this->Navigations->Component->initialize($this->Navigations);
        $this->Navigations->beforeFilter();
        $this->Navigations->Component->startup($this->Navigations);

        $result = json_decode($this->Navigations->admin_update(), true);
        $this->assertTrue($result['success']);
        $this->assertEqual($result['navigation']['title'], 'Google');
        $this->assertEqual($result['navigation']['link'], 'https://google.com');
	}

	function testAdminUpdateInternalModuleUrl() {
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
        $this->Navigations->params = Router::parse('/admin/navigations/update');
        $this->Navigations->params['form'] = array(
            'name' => 'Helpful Articles',
            'link' => '/pages/helpful_articles',
            'parentId' => 19
        );

        $this->Navigations->Component->initialize($this->Navigations);
        $this->Navigations->beforeFilter();
        $this->Navigations->Component->startup($this->Navigations);

        $result = json_decode($this->Navigations->admin_update(), true);
        $this->assertTrue($result['success']);
        $this->assertEqual($result['navigation']['title'], 'Helpful Articles');
        $this->assertEqual($result['navigation']['link'], '/pages/helpful_articles');
	}

	function testAdminDelete() {

	}

}
?>
