<?php
class EcourseModuleQuestion extends AppModel {
	public $name = 'EcourseModuleQuestion';
	public $displayField = 'name';

	public $belongsTo = array('EcourseModule');
	public $hasMany = array('EcourseModuleQuestionAnswer');
}
