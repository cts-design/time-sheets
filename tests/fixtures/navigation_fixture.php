<?php
/* Navigation Fixture generated on: 2011-02-04 19:51:40 : 1296849100 */
class NavigationFixture extends CakeTestFixture {
	var $name = 'Navigation';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'title' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'link' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'parent_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10, 'key' => 'index'),
		'lft' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10, 'key' => 'index'),
		'rght' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10, 'key' => 'index'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'parent_id' => array('column' => 'parent_id', 'unique' => 0), 'lft' => array('column' => 'lft', 'unique' => 0), 'rght' => array('column' => 'rght', 'unique' => 0)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'id' => 19,
			'title' => 'Top',
			'link' => '',
                        'parent_id' => NULL,
                        'lft' => 1,
                        'rght' => 16,
			'created' => '2011-02-04 19:51:40',
			'modified' => '2011-02-04 19:51:40'
		),
                array(
			'id' => 20,
			'title' => 'Home',
			'link' => '/',
                        'parent_id' => 19,
                        'lft' => 2,
                        'rght' => 3,
			'created' => '2011-02-04 19:51:40',
			'modified' => '2011-02-04 19:51:40'
		),
                array(
			'id' => 21,
			'title' => 'Career Fairs',
			'link' => '/career_fairs',
                        'parent_id' => 19,
                        'lft' => 4,
                        'rght' => 5,
			'created' => '2011-02-04 19:51:40',
			'modified' => '2011-02-04 19:51:40'
		),
                array(
			'id' => 22,
			'title' => 'Networking',
			'link' => '/networking',
                        'parent_id' => 19,
                        'lft' => 6,
                        'rght' => 7,
			'created' => '2011-02-04 19:51:40',
			'modified' => '2011-02-04 19:51:40'
		),
                array(
			'id' => 23,
			'title' => 'Featured Employers',
			'link' => '/featured_employers',
                        'parent_id' => 19,
                        'lft' => 8,
                        'rght' => 9,
			'created' => '2011-02-04 19:51:40',
			'modified' => '2011-02-04 19:51:40'
		),
                array(
			'id' => 24,
			'title' => 'Locations',
			'link' => '/locations',
                        'parent_id' => 19,
                        'lft' => 10,
                        'rght' => 11,
			'created' => '2011-02-04 19:51:40',
			'modified' => '2011-02-04 19:51:40'
		),
                array(
			'id' => 25,
			'title' => 'Calendar of Events',
			'link' => '/calendar_of_events',
                        'parent_id' => 19,
                        'lft' => 12,
                        'rght' => 13,
			'created' => '2011-02-04 19:51:40',
			'modified' => '2011-02-04 19:51:40'
		),
                array(
			'id' => 26,
			'title' => 'Left',
			'link' => '',
                        'parent_id' => NULL,
                        'lft' => 17,
                        'rght' => 32,
			'created' => '2011-02-04 19:51:40',
			'modified' => '2011-02-04 19:51:40'
		),
                array(
			'id' => 28,
			'title' => 'Employers',
			'link' => '/employers',
                        'parent_id' => 26,
                        'lft' => 18,
                        'rght' => 19,
			'created' => '2011-02-04 19:51:40',
			'modified' => '2011-02-04 19:51:40'
		),
                array(
			'id' => 29,
			'title' => 'Career Seekers',
			'link' => '/career_seekers',
                        'parent_id' => 26,
                        'lft' => 20,
                        'rght' => 21,
			'created' => '2011-02-04 19:51:40',
			'modified' => '2011-02-04 19:51:40'
		),
                array(
			'id' => 30,
			'title' => 'Specialty Programs',
			'link' => '/specialty_programs',
                        'parent_id' => 26,
                        'lft' => 22,
                        'rght' => 23,
			'created' => '2011-02-04 19:51:40',
			'modified' => '2011-02-04 19:51:40'
		),
                array(
			'id' => 31,
			'title' => 'Youth Programs',
			'link' => '/youth_programs',
                        'parent_id' => 26,
                        'lft' => 24,
                        'rght' => 25,
			'created' => '2011-02-04 19:51:40',
			'modified' => '2011-02-04 19:51:40'
		),
                array(
			'id' => 32,
			'title' => 'In the News',
			'link' => '/news',
                        'parent_id' => 26,
                        'lft' => 26,
                        'rght' => 27,
			'created' => '2011-02-04 19:51:40',
			'modified' => '2011-02-04 19:51:40'
		),
                array(
			'id' => 33,
			'title' => 'Unemployment Claims',
			'link' => '/unemployment_claims',
                        'parent_id' => 26,
                        'lft' => 28,
                        'rght' => 29,
			'created' => '2011-02-04 19:51:40',
			'modified' => '2011-02-04 19:51:40'
		),
                array(
			'id' => 34,
			'title' => 'Employers Survey',
			'link' => '/employers_survey',
                        'parent_id' => 26,
                        'lft' => 30,
                        'rght' => 31,
			'created' => '2011-02-04 19:51:40',
			'modified' => '2011-02-04 19:51:40'
		),
                array(
 			'id' => 35,
			'title' => 'Career Seekers Survey',
			'link' => '/career_seekers_survey',
                        'parent_id' => 36,
                        'lft' => 36,
                        'rght' => 37,
			'created' => '2011-02-04 19:51:40',
			'modified' => '2011-02-04 19:51:40'
                ),
                array(
 			'id' => 36,
			'title' => 'Bottom',
			'link' => '',
                        'parent_id' => NULL,
                        'lft' => 33,
                        'rght' => 40,
			'created' => '2011-02-04 19:51:40',
			'modified' => '2011-02-04 19:51:40'
                ),
                array(
 			'id' => 37,
			'title' => 'Contact Us',
			'link' => '/contact_us',
                        'parent_id' => 36,
                        'lft' => 38,
                        'rght' => 39,
			'created' => '2011-02-04 19:51:40',
			'modified' => '2011-02-04 19:51:40'
                ),
                array(
 			'id' => 38,
			'title' => 'Terms of Use',
			'link' => '/terms_of_use',
                        'parent_id' => 36,
                        'lft' => 34,
                        'rght' => 35,
			'created' => '2011-02-04 19:51:40',
			'modified' => '2011-02-04 19:51:40'
                ),
	);
}
?>