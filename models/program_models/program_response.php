<?php

class ProgramResponse extends AppModel {
	var $name = 'ProgramResponse';
	
	var $belongsTo = array('Program');
	
	var $validate = array();
}
