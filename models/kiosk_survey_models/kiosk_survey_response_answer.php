<?php
class KioskSurveyResponseAnswer extends AppModel {
	var $name = 'KioskSurveyResponseAnswer';
	var $belongsTo = array(
	  'KioskSurveyResponse' => array(
	    'dependent' => true
	  )
	);
}
?>
