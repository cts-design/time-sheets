<?php
/* FeaturedEmployers Test cases generated on: 2011-03-01 20:14:27 : 1299010467*/
App::import('Controller', 'FeaturedEmployers');
App::import('Lib', 'AtlasTestCase');
class TestFeaturedEmployersController extends FeaturedEmployersController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class FeaturedEmployersControllerTestCase extends AtlasTestCase {
	var $fixtures = array('app.featured_employer');

	function startTest() {
		$this->FeaturedEmployers =& new TestFeaturedEmployersController();
		$this->FeaturedEmployers->constructClasses();
	}

	function endTest() {
		unset($this->FeaturedEmployers);
		ClassRegistry::flush();
	}

	function testIndex() {

	}

	function testAdd() {

	}

	function testEdit() {

	}

	function testDelete() {

	}

	function testAdminIndex() {

	}

	function testAdminAdd() {

	}

	function testAdminEdit() {

	}

	function testAdminDelete() {

	}

}
?>