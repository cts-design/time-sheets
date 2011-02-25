<?php
/**
 * @author Brandon Cordell
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
class CompleteWebTestCase extends CakeWebTestCase {

    function CompleteWebTestCase() {
        $this->baseurl = current(split("webroot", $_SERVER['PHP_SELF']));
        debug($_SERVER['PHP_SELF']);
    }

    function testDynamicPage() {
        $test = $this->get("http://atlas.dev/pages/about_us");
        $this->assertText('About Us', 'About us page has proper heading');
    }

    function testUnpublishedPage() {
//        $test = $this->get("http://atlas.dev/pages/");
    }

    function testPageNotFound() {
        $result = $this->get("http://atlas.dev/pages/page_doesnt_exist");
        $this->assertText('The page you requested could not be found');
    }
}