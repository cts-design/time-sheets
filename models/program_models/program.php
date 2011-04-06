<?php
class Program extends AppModel {
	var $name = 'Program';
	var $displayField = 'name';
	
	var $hasMany = array('ProgramField', 'ProgramResponse', 'ProgramEmail', 'WatchedFilingCat');
}