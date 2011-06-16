<?php
/* Page Fixture generated on: 2011-02-04 14:50:04 : 1296831004 */
class PageFixture extends CakeTestFixture {
	var $name = 'Page';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'title' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'slug' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'key' => 'index', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'content' => array('type' => 'text', 'null' => false, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'published' => array('type' => 'boolean', 'null' => false, 'default' => '1'),
        'authentication_required' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'locked' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'slug' => array('column' => 'slug', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
                // valid published page
		array(
			'id' => 1,
			'title' => 'Test Page',
			'slug' => 'test_page',
			'content' => 'This is the test page.',
            'published' => 1,
            'locked' => 0,
            'authentication_required' => 0,
			'created' => '2011-02-04 14:50:04',
			'modified' => '2011-02-04 14:50:04'
		),
                // valid unpublished page
                array(
                        'id' => 2,
			'title' => 'About Us',
			'slug' => 'about_us',
			'content' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat
                                      dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus.
                                      Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim,
                                      rhoncus duis vestibulum nunc mattis convallis.',
			'published' => 0,
            'locked' => 1,
            'authentication_required' => 0,
			'created' => '2011-02-04 14:50:04',
			'modified' => '2011-02-04 14:50:04'
                ),
	);
}
?>
