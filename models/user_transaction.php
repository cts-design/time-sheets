<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
class UserTransaction extends AppModel {
	var $name = 'UserTransaction';
	var $belongsTo = array(
	    'User' => array(
		'className' => 'User',
		'foreginKey' => 'user_id'
	    ));


}