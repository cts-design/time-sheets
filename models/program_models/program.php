<?php
class Program extends AppModel {
	var $name = 'Program';
	var $displayField = 'name';
	
	var $hasMany = array(
		'ProgramModule',
		'ProgramResponse',
		'ProgramEmail',
		'ProgramPaperForm',
		'WatchedFilingCat',
		'ProgramInstruction'
		);
}
