<?php

class ProgramRegistration extends AppModel {
	var $name = 'ProgramRegistration';
	
	var $belongsTo = array('Program');
	
	var $validate = array();
}
