<?php
class EcourseModule extends AppModel {
	public $name = 'EcourseModule';
	public $displayField = 'name';

	public $actsAs = array('Containable');

	public $belongsTo = array('Ecourse');
	public $hasMany = array('EcourseModuleQuestion');
}
