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
<?php echo $this->Html->script('kiosk_buttons/buttons.tree', array('inline' => false))?>
<div id="crumbWrapper">
    <span>You are here > </span>
    <?php echo $crumb->getHtml('Kiosk Buttons', null, 'unique'); ?>
</div>
<div id="manageButtons" class="admin">
    
    <div class="actions ui-widget-header">
	    <ul>
		<li><?php echo $this->Html->link('Enable Button', '', array('id' => 'enableButton')) ?></li>
		<li><?php echo $this->Html->link('Disable Button', '', array('id' => 'disableButton')) ?></li>
		<li><?php echo $this->Html->link('Done Managing Buttons',
			array('controller' => 'kiosks', 'action' => 'index', 'admin' => true)) ?></li>
	    </ul>
    </div>
    <div class="tree-wrapper">
	<h2><?php echo __('Master Button List')?></h2>
	<p class="expand-wrap"><?php echo $this->Html->link('Expand All', '', array('class' => 'expand-master')) ?></p>
	<?php if (!empty($masterButtons)) { ?>
	    <?php echo '<div id="masterKioskButtonTree">' . $tree->generate($masterButtons, array('element' => 'master_kiosk_buttons/master_kiosk_button_tree')) . '</div>'; ?>
	<?php } else
	    echo '<p>There are no buttons, please add some</p>'; ?>
    </div>
    <div class="tree-wrapper">
	<?php if (!empty($locationButtons)) { ?>
	<h2><?php echo __($locations[$locationButtons[0]['Kiosk']['location_id']]. '-' . $locationButtons[0]['Kiosk']['location_recognition_name'] .' Button List') ?></h2>
	 <p class="expand-wrap"><?php echo $this->Html->link('Expand All', '', array('class' => 'expand')) ?></p>
	<?php echo '<div id="kioskButtonTree">' . $tree->generate($locationButtons, array('element' => 'kiosk_buttons/kiosk_button_tree')) . '</div>'; ?>
	<?php } else
	    echo '<p>There are no buttons, please add some</p>'; ?>
    </div>
    <br />
</div>