<?php
class KioskSurveyResponse extends AppModel {
	var $name = 'KioskSurveyResponse';
	var $belongsTo = array(
	  'KioskSurvey' => array(
	    'dependent' => true
	  )
  );
    var $hasMany = array(
        'KioskSurveyResponseAnswer' => array(
            'dependent' => true
        )
    );
}
?>
