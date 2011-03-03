<?php
/* FeaturedEmployer Test cases generated on: 2011-03-01 20:13:43 : 1299010423*/
App::import('Model', 'FeaturedEmployer');
App::import('Lib', 'AtlasTestCase');
class FeaturedEmployerTestCase extends AtlasTestCase {
	var $fixtures = array('app.featured_employer');

	function startTest() {
		$this->FeaturedEmployer =& ClassRegistry::init('FeaturedEmployer');
	}

	function endTest() {
		unset($this->FeaturedEmployer);
		ClassRegistry::flush();
	}

}
?>