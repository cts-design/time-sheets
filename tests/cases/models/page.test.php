<?php
/* Page Test cases generated on: 2011-02-04 14:50:04 : 1296831004*/
App::import('Model', 'Page');
App::import('Vendor', 'DebugKit.FireCake');
class PageTestCase extends CakeTestCase {
	var $fixtures = array('app.page');

	function startTest() {
		$this->Page =& ClassRegistry::init('Page');
	}

	function endTest() {
		unset($this->Page);
		ClassRegistry::flush();
	}

        function testPublished() {
            // testing published pages
            $result = $this->Page->findPublishedBySlug('test_page');
            $expected = array('Page' => array('id' => 1,
                                              'title' => 'Test Page',
                                              'slug' => 'test_page',
                                              'content' => 'This is the test page.',
                                              'published' => 1,
                                                'authentication_required' => 0,
                                              'created' => '2011-02-04 14:50:04',
                                              'modified' => '2011-02-04 14:50:04'));
            $this->assertEqual($result, $expected);

            // testing unpublished pages
            $result = $this->Page->findPublishedBySlug('about_us');
            $this->assertFalse($result);
        }

        function testValidation() {
            $this->Page->create();
            
            $noTitle = array(
                'Page' => array(
                    'title' => '',
                    'slug' => 'asd',
                    'content' => 'Valid Content'
                )
            );
            $noSlug = array(
                'Page' => array(
                    'title' => 'asd',
                    'slug' => '',
                    'content' => 'Valid Content'
                )
            );
            $noContent = array(
                'Page' => array(
                    'title' => 'asd',
                    'slug' => 'slug',
                    'content' => ''
                )
            );
            $nonUniqueTitle = array(
                'Page' => array(
                    'title' => 'Test Page',
                    'slug' => 'slug',
                    'content' => 'ASDF'
                )
            );
            $nonUniqueSlug = array(
                'Page' => array(
                    'title' => 'Valid Title',
                    'slug' => 'test_page',
                    'content' => 'Valid content'
                )
            );
            $longTitle = array(
                'Page' => array(
                    'title' => 'asdfasdfasasdfasdfasasdfasdfasasdfasdfasasdfasdfasasdfasdfasasdfasdfasasdfasdfasasdfasdfasasdfasdfasasdfasdfas',
                    'slug' => 'valid_slug',
                    'content' => 'valid content'
                )
            );
            $longSlug = array(
                    'title' => 'Valid Title',
                    'slug' => 'asdfasdfasasdfasdfasasdfasdfasasdfasdfasasdfasdfasasdfasdfasasdfasdfasasdfasdfasasdfasdfasasdfasdfasasdfasdfas',
                    'content' => 'valid content'
            );
            $invalidCharacterTitle = array(
                    'title' => 'Invalid Title!',
                    'slug' => 'valid_slug',
                    'content' => 'valid content'
            );
            $invalidCharacterSlug = array(
                    'title' => 'Valid Title',
                    'slug' => 'in-valid_slug',
                    'content' => 'valid content'
            );

            $this->assertFalse($this->Page->save($noTitle));
            $this->assertFalse($this->Page->save($noSlug));
            $this->assertFalse($this->Page->save($noContent));
            $this->assertFalse($this->Page->save($nonUniqueTitle));
            $this->assertFalse($this->Page->save($nonUniqueSlug));
            $this->assertFalse($this->Page->save($longTitle));
            $this->assertFalse($this->Page->save($longSlug));
            $this->assertFalse($this->Page->save($invalidCharacterTitle));
            $this->assertFalse($this->Page->save($invalidCharacterSlug));
        }

}
?>
