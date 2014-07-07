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

if($KioskButton['action'] == NULL || $KioskButton['action'] == '' || $KioskButton['action_meta'] == NULL || $KioskButton['action_meta'] == '')
{
	echo $html->link(
		$MasterKioskButton['name'],
		'',
		array(
			'rel' => $KioskButton['parent_id'],
			'id' => $KioskButton['button_id'],
			'style' => 'color:red'
		)
	);
}
else
{
	echo $html->link(
		$MasterKioskButton['name'],
		'',
		array(
			'rel' => $KioskButton['parent_id'],
			'id' => $KioskButton['button_id'],
		)
	);
}
?>
