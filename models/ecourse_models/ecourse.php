<?php
class Ecourse extends AppModel {
	public $name = 'Ecourse';
	public $displayField = 'name';

	public $hasMany = array('EcourseModule');
}
?>
