<?php
/* HelpfulArticles Test cases generated on: 2011-03-07 20:29:43 : 1299529783*/
App::import('Controller', 'HelpfulArticles');
App::import('Lib', 'AtlasTestCase');
class TestHelpfulArticlesController extends HelpfulArticlesController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class HelpfulArticlesControllerTestCase extends AtlasTestCase {
	var $fixtures = array('app.helpful_article');

	function startTest() {
		$this->HelpfulArticles =& new TestHelpfulArticlesController();
		$this->HelpfulArticles->constructClasses();
	}

	function endTest() {
		unset($this->HelpfulArticles);
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