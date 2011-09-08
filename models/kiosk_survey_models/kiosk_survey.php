<?php
class KioskSurvey extends AppModel {
	var $name = 'KioskSurvey';
    var $displayField = 'name';
    var $hasMany = array(
        'KioskSurveyQuestion' => array(
            'dependent' => true
    ),
        'KioskSurveyResponse' => array(
            'dependent' => true
        )
    );

    var $hasAndBelongsToMany = array(
        'Kiosk' => array(
            'className' => 'Kiosk',
            'joinTable' => 'kiosks_kiosk_surveys',
            'foreign_key' => 'kiosk_survey_id',
            'associationForeignKey' => 'kiosk_id',
            'unique' => false
        )
    );

	var $validate = array(
		'name' => array(
			'rule' => 'notEmpty',
			'message' => 'You must provide a survey name'
		)
	);


}
?>
