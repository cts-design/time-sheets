<?php
/** 
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 */
class AppModel extends Model {

    function formatDateAfterFind($date) {
	return date('m/d/Y', strtotime($date));
    }

    function formatDateTimeAfterFind($date) {
	return date('m/d/Y - h:i a', strtotime($date));
    }
}
