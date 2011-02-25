<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
?>
<?php
extract ($data);
$tree->addItemAttribute('id',  $DocumentFilingCategory['id']);
echo $html->link($DocumentFilingCategory['name'],'', array('id' => $DocumentFilingCategory['id']));
?>