<?php
/* EmployersSurvey Test cases generated on: 2011-03-23 15:46:08 : 1300909568*/
App::import('Model', 'EmployersSurvey');
App::import('Lib', 'AtlasTestCase');
class EmployersSurveyTestCase extends AtlasTestCase {
    var $fixtures = array('app.employers_survey');

    function startTest() {
            $this->EmployersSurvey =& ClassRegistry::init('EmployersSurvey');
    }

    function endTest() {
            unset($this->EmployersSurvey);
            ClassRegistry::flush();
    }

}
?>