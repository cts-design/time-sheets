<?php
/* HelpfulArticle Test cases generated on: 2011-03-07 20:29:21 : 1299529761*/
App::import('Model', 'HelpfulArticle');
App::import('Lib', 'AtlasTestCase');
class HelpfulArticleTestCase extends AtlasTestCase {
	var $fixtures = array('app.helpful_article');

	function startTest() {
		$this->HelpfulArticle =& ClassRegistry::init('HelpfulArticle');
	}

	function endTest() {
		unset($this->HelpfulArticle);
		ClassRegistry::flush();
	}

}
?>