<?php
/* DocumentFilingCategory Test cases generated on: 2010-10-19 15:10:41 : 1287503861*/
App::import('Model', 'DocumentFilingCategory');

class DocumentFilingCategoryTestCase extends CakeTestCase {
	var $fixtures = array('app.document_filing_category');

	function startTest() {
		$this->DocumentFilingCategory =& ClassRegistry::init('DocumentFilingCategory');
	}

	function endTest() {
		unset($this->DocumentFilingCategory);
		ClassRegistry::flush();
	}

}
?>