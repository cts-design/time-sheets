<?php
class KioskSurvey extends AppModel {
	var $name = 'KioskSurvey';
    var $displayField = 'name';
    var $hasMany = array(
      'KioskSurveyQuestion' => array(
        'dependent' => true
      )
    );
}
?>
