<?php
/* Events Test cases generated on: 2011-02-22 19:52:31 : 1298404351*/
App::import('Controller', 'Events');
App::import('Lib', 'AtlasTestCase');
class TestEventsController extends EventsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class EventsControllerTestCase extends AtlasTestCase {
	var $fixtures = array(
	    'app.aco',
	    'app.aro',
	    'app.aros_aco',
	    'chairman_report',
	    'deleted_document',
	    'document_filing_category',
	    'document_queue_category',
	    'document_transaction',
	    'event',
	    'event_category',
	    'featured_employer',
	    'filed_document',
	    'ftp_document_scanner',
	    'helpful_article',
	    'hot_job',
	    'kiosk',
	    'kiosk_button',
	    'in_the_news',
	    'location',
	    'master_kiosk_button',
	    'module_access_control',
	    'navigation',
	    'page',
	    'press_release',
	    'queued_document',
	    'rfp',
	    'role',
	    'self_scan_category',
	    'self_sign_log',
	    'self_sign_log_archive',
	    'survey',
	    'survey_question',
	    'user',
	    'user_transaction'
	);

	function startTest() {
		$this->Events =& new TestEventsController();
		$this->Events->constructClasses();
	}

	function endTest() {
		unset($this->Events);
		ClassRegistry::flush();
	}
	
	function testIndexNoParams() {
		$result = $this->testAction('/events/', array('return' => 'vars'));
		
		$this->assertTrue($result['title_for_layout'], 'Calendar of Events');
		
		$expectedCategories = array(
			'All Categories',
			'Board Meetings',
			'Business Seminars',
			'Job Fairs',
			'Networking Events',
			'Workshops'
		);
		
		$this->assertEqual($result['categories'], $expectedCategories);
		
		// going to the index page without passing in any parameters retrieves events for the current month, based on date('m');
		// we need to dynamically grab that in our tests as well, to keep the test passing over time.
		$currentMonth = date('m/d/Y');
		$this->assertEqual($result['curMonth'], date('F Y', strtotime($currentMonth)));
		$this->assertEqual($result['prevMonth'], date('m/Y', strtotime('-1 month', strtotime($currentMonth))));
		$this->assertEqual($result['nextMonth'], date('m/Y', strtotime('+1 month', strtotime($currentMonth))));
	}
	
	function testIndexWithMonth() {
		$result = $this->testAction('/events/index/04/2011', array('return' => 'vars'));
		$expectedEvents = array(
			array(
				'Event' => array(
	                'id' => 3,
	                'event_category_id' => 2,
	                'title' => 'Workforce Meeting',
	                'description' => 'Notes about the meeting',
	                'start' => '2011-04-01 12:30:00',
	                'end' => '2011-04-01 14:30:00',
	                'all_day' => 0,
	                'location' => 'Workforce Tampa Career Center',
	                'address' => '9215 N. Florida Ave.Tampa, FL 33612',
	                'event_url' => '',
	                'sponsor' => 'TBWA',
	                'sponsor_url' => 'http://www.workforcetampa.com/',
	                'created' => '2011-03-31 16:12:47',
	                'modified' => '2011-03-31 16:14:17'
				),
				'EventCategory' => array(
					'id' => 2,
					'name' => 'Business Seminars',
					'created' => '2011-02-23 20:04:22',
					'modified' => '2011-02-23 20:04:22'
				)
			),
			array(
				'Event' => array(
	                'id' => 5,
	                'event_category_id' => 2,
	                'title' => 'asdfasdf',
	                'description' => '',
	                'start' => '2011-04-07 00:00:00',
	                'end' => '2011-04-07 01:00:00',
	                'all_day' => 0,
	                'location' => '',
	                'address' => '',
	                'event_url' => '',
	                'sponsor' => '',
	                'sponsor_url' => '',
	                'created' => '2011-04-01 08:23:32',
	                'modified' => '2011-04-01 08:23:32'
				),
				'EventCategory' => array(
					'id' => 2,
					'name' => 'Business Seminars',
					'created' => '2011-02-23 20:04:22',
					'modified' => '2011-02-23 20:04:22'
				)			
			),
			array(
				'Event' => array(
	                'id' => 2,
	                'event_category_id' => 3,
	                'title' => 'TBWA Career Fair',
	                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse volutpat gravida sapien id accumsan. Donec ultricies est et enim consectetur cursus. Morbi metus leo, lobortis a aliquam vel, accumsan ut velit.',
	                'start' => '2011-04-07 12:00:00',
	                'end' => '2011-04-07 12:45:00',
	                'all_day' => 0,
	                'location' => 'Workforce Tampa Career Center',
	                'address' => '9215 N. Florida Ave.Tampa, FL 33612',
	                'event_url' => 'http://checkitout.com/tbwa',
	                'sponsor' => 'TBWA',
	                'sponsor_url' => 'http://www.workforcetampa.com/',
	                'created' => '2011-03-21 15:03:10',
	                'modified' => '2011-03-31 16:12:09'
				),
				'EventCategory' => array(
					'id' => 3,
					'name' => 'Job Fairs',
					'created' => '2011-02-23 20:05:28',
					'modified' => '2011-02-23 20:05:28'
				)
			),
			array(
				'Event' => array(
	                'id' => 4,
	                'event_category_id' => 4,
	                'title' => 'A Networking Event',
	                'description' => '',
	                'start' => '2011-04-08 00:00:00',
	                'end' => '2011-04-08 01:00:00',
	                'all_day' => 0,
	                'location' => '',
	                'address' => '',
	                'event_url' => '',
	                'sponsor' => '',
	                'sponsor_url' => '',
	                'created' => '2011-04-01 08:03:39',
	                'modified' => '2011-04-01 08:03:39'
				),
				'EventCategory' => array(
					'id' => 4,
					'name' => 'Networking Events',
					'created' => '2011-02-23 20:05:51',
					'modified' => '2011-02-23 20:06:41'
				)
			)
		);
		
		$this->assertEqual($expectedEvents, $result['events']);
		$this->assertEqual($result['curMonth'], 'April 2011');
		$this->assertEqual($result['prevMonth'], '03/2011');
		$this->assertEqual($result['nextMonth'], '05/2011');
	}
}
?>