<?php
/** 
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 */
class AppModel extends Model {

	public $actsAs = array('Containable');

    function formatDateAfterFind($date) {
		return date('m/d/Y', strtotime($date));
    }

    function formatDateTimeAfterFind($date) {
		return date('m/d/Y - h:i a', strtotime($date));
    }
	
	function modifyValidate($rules) {
		if(!empty($rules)) {
			$this->validate = Set::merge($this->validate, $rules);
		}		
	}
}
