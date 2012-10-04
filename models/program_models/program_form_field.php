<?php

class ProgramFormField extends AppModel {
	
	var $name = 'ProgramFormField';
	var $displayField = 'name';
	
	var $belongsTo = array('ProgramStep');	
}
