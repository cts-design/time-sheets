<?php
class Program extends AppModel {
	var $name = 'Program';
	var $displayField = 'name';
    var $actsAs = array('Containable');
	
	var $hasMany = array(
		'ProgramStep',
		'ProgramResponse',
		'ProgramEmail',
		'ProgramPaperForm',
		'WatchedFilingCat',
		'ProgramInstruction'
		);
}
