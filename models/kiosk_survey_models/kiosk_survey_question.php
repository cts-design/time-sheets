<?php
class KioskSurveyQuestion extends AppModel {
	var $name = 'KioskSurveyQuestion';
	var $belongsTo = array(
	  'KioskSurvey' => array(
	    'dependent' => true
	  )
	);
}
?>