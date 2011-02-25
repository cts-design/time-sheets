<?php
/** 
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 */


$excel->blacklist = array('id','modified');
$excel->generate($data, $title);

?>
