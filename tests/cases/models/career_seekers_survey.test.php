<?php
/* CareerSeekersSurvey Test cases generated on: 2011-03-21 16:24:29 : 1300724669*/
App::import('Model', 'CareerSeekersSurvey');
App::import('Lib', 'AtlasTestCase');
class CareerSeekersSurveyTestCase extends AtlasTestCase {
    var $fixtures = array('app.career_seekers_survey');

    function startTest() {
            $this->CareerSeekersSurvey =& ClassRegistry::init('CareerSeekersSurvey');
    }

    function endTest() {
            unset($this->CareerSeekersSurvey);
            ClassRegistry::flush();
    }

}
?>