<?php
/** 
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2011
 * @link http://ctsfla.com
 */

if(isset($blacklist)) {
	$excel->blacklist = $blacklist;
}
$excel->generate($data, $title);
?>