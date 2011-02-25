<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
?>
<?php echo $this->Html->script('jquery.jstree', array('inline' => false)) ?>
<?php echo $this->Html->script('jquery.cookie', array('inline' => false)) ?>
<?php echo $this->Html->script('master_kiosk_buttons/buttons.tree', array('inline' => false))?>
<div id="crumbWrapper">
    <span>You are here > </span>
    <?php echo $crumb->getHtml('Master Kiosk Buttons', null, 'unique'); ?>
</div>
<div id="manageButtons" class="admin">
    
    <div class="actions ui-widget-header">
	    <ul>
		<li><?php echo $this->Html->link('Add Root Button', 'javascript:void(0)', array('id' => 'addButton')) ?></li>
		<li><?php echo $this->Html->link('Edit Button', '', array('id' => 'editButton'),
			__('This will edit this button system wide, are you sure you want to edit?', true)) ?></li>
		<li><?php echo $this->Html->link('Delete Button', '',array('id' => 'deleteButton'),
			__('This will delete this button system wide, are you sure you want to delete?', true)) ?></li>
		<li><?php echo $this->Html->link('Done Managing Buttons',
			array('controller' => 'users', 'action' => 'dashboard', 'admin' => true)) ?></li>
	    </ul>
    </div>
    <h2><?php echo __('Master Kiosk Button List') ?></h2>
    <p class="expand-wrap"><?php echo $this->Html->link('Expand All', '', array('class' => 'expand')) ?></p>
    <?php if (!empty($data)) { ?>
	<?php echo '<div id="masterKioskButtonTree">' . $tree->generate($data, array('element' => 'master_kiosk_buttons/master_kiosk_button_tree')) . '</div>'; ?>
    <?php } else
	echo '<p>There are no buttons, please add some</p>'; ?>
    <br />
    <div class="mini-form">
	<fieldset>
	    <legend>Add New Master Kiosk Button</legend>
	    <?php echo $this->Form->create('MasterKioskButton', array('action' => 'add')) ?>
	    <?php echo $this->Form->hidden('parent_id') ?>
	    <?php  echo $this->Form->input('name'); ?>
	</fieldset>
	<?php echo $this->Form->end('Submit') ?>
    </div>
</div>