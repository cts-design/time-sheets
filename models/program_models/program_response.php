<?php

class ProgramResponse extends AppModel {
	
	var $name = 'ProgramResponse';
	
	var $hasMany = array('ProgramResponseDoc');
	
	var $belongsTo = array('Program', 'User');
	
	var $validate = array();
	
	
}