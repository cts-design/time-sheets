<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2011
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
?>
<?php echo $html->script('programs/admin_edit_instructions', array('inline' => FALSE));?>
<div id="crumbWrapper">
    <span>You are here > </span>
    <?php echo $crumb->getHtml('Edit Program Instructions', null, 'unique') ; ?>
</div>
<div id="editForm">
	<?php echo $form->create('Program', array('action' => 'edit_instructions')) ?>
	<?php echo $form->input('ProgramInstruction.text', array(
		'type' => 'textarea', 
		'label' => false,
		'class' => 'x-hide-visibility')) ?>
	<?php echo $form->input('ProgramInstruction.id', array('type' => 'hidden')); ?>
	<?php echo $form->end('Submit') ?>
	
</div>