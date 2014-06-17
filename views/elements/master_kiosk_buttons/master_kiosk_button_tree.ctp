<?php
	extract($data);
	$tree->addItemAttribute('id',  $MasterKioskButton['id']);
	echo $html->link($MasterKioskButton['name'],'', array(
		'rel' => $MasterKioskButton['parent_id'], 
		'id' => $MasterKioskButton['id']
	));
?>
