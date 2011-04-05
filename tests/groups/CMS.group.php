<?php
/**
 * @author Brandon Cordell
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
class CmsGroupTest extends TestSuite {

/**
 * label property
 *
 * @var string 'All cake/libs/controller/* (Not yet implemented)'
 * @access public
 */
	var $label = 'Atlas CMS test cases';
/**
 * LibControllerGroupTest method
 *
 * @access public
 * @return void
 */
	function CmsGroupTest() {
		TestManager::addTestFile($this, TESTS . 'cases' . DS . 'controllers' . DS . 'career_seekers_surveys_controller');
		TestManager::addTestFile($this, TESTS . 'cases' . DS . 'controllers' . DS . 'chairman_reports_controller');
		TestManager::addTestFile($this, TESTS . 'cases' . DS . 'controllers' . DS . 'employers_surveys_controller');
		TestManager::addTestFile($this, TESTS . 'cases' . DS . 'controllers' . DS . 'featured_employers_controller');
		TestManager::addTestFile($this, TESTS . 'cases' . DS . 'controllers' . DS . 'helpful_articles_controller');
		TestManager::addTestFile($this, TESTS . 'cases' . DS . 'controllers' . DS . 'in_the_news_controller');
		TestManager::addTestFile($this, TESTS . 'cases' . DS . 'controllers' . DS . 'press_releases_controller');
		
        TestManager::addTestFile($this, TESTS . 'cases' . DS . 'models' . DS . 'chairman_report');
        TestManager::addTestFile($this, TESTS . 'cases' . DS . 'models' . DS . 'press_release');
	}
}
