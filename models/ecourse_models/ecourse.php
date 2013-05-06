<?php
class Ecourse extends AppModel {
	public $name = 'Ecourse';

	public $displayField = 'name';

	public $order = 'Ecourse.order ASC';

	public $actsAs = array('Containable');

	public $hasMany = array('EcourseModule', 'EcourseResponse', 'EcourseUser');
}
