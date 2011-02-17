<?php
/* DocumentFilingCategory Test cases generated on: 2010-10-19 15:10:41 : 1287503861*/
App::import('Model', 'DocumentFilingCategory');

class DocumentFilingCategoryTestCase extends CakeTestCase {
	var $fixtures = array('app.document_filing_category');

	function startTest() {
		$this->DocumentFilingCategory =& ClassRegistry::init('DocumentFilingCategory');
        $this->DocumentFilingCategory->create();
	}

	function endTest() {
		unset($this->DocumentFilingCategory);
		ClassRegistry::flush();
	}

        function testValidation() {
            $invalidRecordNoName = array(
                'DocumentFilingCategory' => array(
                    'name' => ''
                )
            );

            $validRecord = array(
                'DocumentFilingCategory' => array(
                    'name' => 'validname',
                    'created' => '0000-00-00 00:00:00',
                    'modified' => '0000-00-00 00:00:00'
                )
            );

            $expected = array(
                'DocumentFilingCategory' => array(
                    'name' => 'validname',
                    'created' => '0000-00-00 00:00:00',
                    'modified' => '0000-00-00 00:00:00'
                )
            );

            $this->assertFalse($this->DocumentFilingCategory->save($invalidRecordNoName));
            $this->assertEqual($this->DocumentFilingCategory->save($validRecord), $expected);
        }
}
?>