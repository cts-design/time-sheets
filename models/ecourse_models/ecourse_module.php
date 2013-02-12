<?php
class EcourseModule extends AppModel {
	public $name = 'EcourseModule';
	public $displayField = 'name';

	public $belongsTo = array('Ecourse');
}
?>
