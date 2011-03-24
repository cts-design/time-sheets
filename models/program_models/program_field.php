<?php

class ProgramField extends AppModel {
	
	var $name = 'ProgramField';
	var $displayField = 'name';
	
	var $belongsTo = array('Program');	
}
