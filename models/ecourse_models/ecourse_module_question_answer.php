<?php
class EcourseModuleQuestionAnswer extends AppModel {
	public $name = 'EcourseModuleQuestionAnswer';
	public $displayField = 'name';

	public $belongsTo = array('EcourseModuleQuestion');
}
