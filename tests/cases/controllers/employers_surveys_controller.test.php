<?php
/* EmployersSurveys Test cases generated on: 2011-03-23 15:46:23 : 1300909583*/
App::import('Controller', 'EmployersSurveys');
App::import('Lib', 'AtlasTestCase');
class TestEmployersSurveysController extends EmployersSurveysController {
        var $autoRender = false;

        function redirect($url, $status = null, $exit = true) {
                $this->redirectUrl = $url;
        }
}

class EmployersSurveysControllerTestCase extends AtlasTestCase {
    var $fixtures = array('app.employers_survey');

    function startTest() {
            $this->EmployersSurveys =& new TestEmployersSurveysController();
            $this->EmployersSurveys->constructClasses();
    }

    function endTest() {
            unset($this->EmployersSurveys);
            ClassRegistry::flush();
    }

    function testIndex() {

    }

    function testAdd() {

    }

    function testEdit() {

    }

    function testDelete() {

    }

    function testAdminIndex() {

    }

    function testAdminAdd() {

    }

    function testAdminEdit() {

    }

    function testAdminDelete() {

    }

}
?>