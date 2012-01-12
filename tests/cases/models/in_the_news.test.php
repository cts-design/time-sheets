<?php
/* InTheNews Test cases generated on: 2011-03-07 19:52:03 : 1299527523*/
App::import('Model', 'InTheNews');
App::import('Lib', 'AtlasTestCase');
class InTheNewsTestCase extends AtlasTestCase {
	function startTest() {
		$this->InTheNews =& ClassRegistry::init('InTheNews');
	}

	function endTest() {
		unset($this->InTheNews);
		ClassRegistry::flush();
	}
	
	function testValidation() {
		$this->InTheNews->create();
		
		$invalidRecordNoTitle = array(
			'InTheNews' => array(
				'title' => '',
				'reporter' => 'Sally Beard - WSJ.com',
				'summary' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
				'link' => 'http://www.wsj.com/sally.beard/a-helpful-article',
				'posted_date' => '2011-03-07',
			)
		);
		
		$invalidRecordNoReporter = array(
			'InTheNews' => array(
				'title' => 'A Helpful Article From Some Publication',
				'reporter' => '',
				'summary' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
				'link' => 'http://www.wsj.com/sally.beard/a-helpful-article',
				'posted_date' => '2011-03-07',
			)
		);
		
		$invalidRecordNoSummary = array(
			'InTheNews' => array(
				'title' => 'A Helpful Article From Some Publication',
				'reporter' => 'Sally Beard - WSJ.com',
				'summary' => '',
				'link' => 'http://www.wsj.com/sally.beard/a-helpful-article',
				'posted_date' => '2011-03-07',
			)
		);
		
		$invalidRecordNoLink = array(
			'InTheNews' => array(
				'title' => 'A Helpful Article From Some Publication',
				'reporter' => 'Sally Beard - WSJ.com',
				'summary' => 'awesome summaryyyyyyyyyyyyyy',
				'link' => '',
				'posted_date' => '2011-03-07',
			)
		);
		
		$invalidRecordNoPostedDate = array(
			'InTheNews' => array(
				'title' => 'A Helpful Article From Some Publication',
				'reporter' => 'Sally Beard - WSJ.com',
				'summary' => 'awesome summaryyyyyyyyyyyyyy',
				'link' => 'http://www.wsj.com/sally.beard/a-helpful-article',
				'posted_date' => '',
			)
		);
		
		$this->assertFalse($this->InTheNews->save($invalidRecordNoTitle));
		$this->assertFalse($this->InTheNews->save($invalidRecordNoReporter));
		$this->assertFalse($this->InTheNews->save($invalidRecordNoSummary));
		$this->assertFalse($this->InTheNews->save($invalidRecordNoLink));
		$this->assertFalse($this->InTheNews->save($invalidRecordNoPostedDate));
	}

}
?>