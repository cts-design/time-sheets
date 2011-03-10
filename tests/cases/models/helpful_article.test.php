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
	
	function testValidation() {
		$this->HelpfulArticle->create();
		
		$invalidRecordNoTitle = array(
			'HelpfulArticle' => array(
				'title' => '',
				'reporter' => 'Sally Beard - WSJ.com',
				'summary' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
				'link' => 'http://www.wsj.com/sally.beard/a-helpful-article',
				'posted_date' => '2011-03-07',
			)
		);
		
		$invalidRecordNoReporter = array(
			'HelpfulArticle' => array(
				'title' => 'A Helpful Article From Some Publication',
				'reporter' => '',
				'summary' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
				'link' => 'http://www.wsj.com/sally.beard/a-helpful-article',
				'posted_date' => '2011-03-07',
			)
		);
		
		$invalidRecordNoSummary = array(
			'HelpfulArticle' => array(
				'title' => 'A Helpful Article From Some Publication',
				'reporter' => 'Sally Beard - WSJ.com',
				'summary' => '',
				'link' => 'http://www.wsj.com/sally.beard/a-helpful-article',
				'posted_date' => '2011-03-07',
			)
		);
		
		$invalidRecordNoLink = array(
			'HelpfulArticle' => array(
				'title' => 'A Helpful Article From Some Publication',
				'reporter' => 'Sally Beard - WSJ.com',
				'summary' => 'awesome summaryyyyyyyyyyyyyy',
				'link' => '',
				'posted_date' => '2011-03-07',
			)
		);
		
		$invalidRecordNoPostedDate = array(
			'HelpfulArticle' => array(
				'title' => 'A Helpful Article From Some Publication',
				'reporter' => 'Sally Beard - WSJ.com',
				'summary' => 'awesome summaryyyyyyyyyyyyyy',
				'link' => 'http://www.wsj.com/sally.beard/a-helpful-article',
				'posted_date' => '',
			)
		);
		
		$this->assertFalse($this->HelpfulArticle->save($invalidRecordNoTitle));
		$this->assertFalse($this->HelpfulArticle->save($invalidRecordNoReporter));
		$this->assertFalse($this->HelpfulArticle->save($invalidRecordNoSummary));
		$this->assertFalse($this->HelpfulArticle->save($invalidRecordNoLink));
		$this->assertFalse($this->HelpfulArticle->save($invalidRecordNoPostedDate));
	}

}
?>