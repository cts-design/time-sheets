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
$tree->addItemAttribute('id',  $KioskButton['button_id']);
echo $html->link($MasterKioskButton['name'],'', array( 'rel' => $KioskButton['parent_id'], 'id' => $KioskButton['button_id']));
?>
