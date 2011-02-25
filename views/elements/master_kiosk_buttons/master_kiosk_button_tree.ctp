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
$tree->addItemAttribute('id',  $MasterKioskButton['id']);
echo $html->link($MasterKioskButton['name'],'', array( 'rel' => $MasterKioskButton['parent_id'], 'id' => $MasterKioskButton['id']));
?>
