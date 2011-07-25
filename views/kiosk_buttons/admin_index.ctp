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
    <span><?php __('You are here') ?> > </span>
    <?php echo $crumb->getHtml(__('Kiosk Buttons', true), null, 'unique'); ?>
</div>
<div id="manageButtons" class="admin">
    
    <div class="actions ui-widget-header">
	    <ul>
		<li><?php echo $this->Html->link(__('Enable Button', true), '', array('id' => 'enableButton')) ?></li>
		<li><?php echo $this->Html->link(__('Disable Button', true), '', array('id' => 'disableButton')) ?></li>
		<li><?php echo $this->Html->link(__('Edit Button Logout Message', true), '', array('id' => 'EditMessageButton')) ?></li>
		<li><?php echo $this->Html->link(__('Done Managing Buttons', true),
			array('controller' => 'kiosks', 'action' => 'index', 'admin' => true)) ?></li>
		
	    </ul>
    </div>
    <div class="tree-wrapper">
	<h2><?php echo __('Master Button List')?></h2>
	<p class="expand-wrap"><?php echo $this->Html->link(__('Expand All', true), '', array('class' => 'expand-master')) ?></p>
	<?php if (!empty($masterButtons)) { ?>
	    <?php echo '<div id="masterKioskButtonTree">' . $tree->generate($masterButtons, array('element' => 'master_kiosk_buttons/master_kiosk_button_tree')) . '</div>'; ?>
	<?php } else
	    echo '<p>' . __('There are no buttons, please add some', true) . '</p>'; ?>
    </div>
    <div class="tree-wrapper">
	<?php if (!empty($locationButtons)) { ?>
	<h2><?php echo __($locations[$locationButtons[0]['Kiosk']['location_id']]. '-' . $locationButtons[0]['Kiosk']['location_recognition_name'] .' Button List') ?></h2>
	 <p class="expand-wrap"><?php echo $this->Html->link('Expand All', '', array('class' => 'expand')) ?></p>
	<?php echo '<div id="kioskButtonTree">' . $tree->generate($locationButtons, array('element' => 'kiosk_buttons/kiosk_button_tree')) . '</div>'; ?>
	<?php } else
	    echo '<p>' . __('There are no buttons, please add some', true) . '</p>'; ?>
    </div>
    <br />
    <?php echo $form->create(null, 
    	array('url' => '/kiosk_button/edit_logout_message', 
    	'admin' => true,
		'id' => 'LogoutMessageForm'))
    ?> 
	<?php echo $form->input('logout_message', array('type' => 'textarea')) ?>
	<?php echo $form->input('id', array('type' => 'hidden')) ?>
	</form>
	
	<div id="Notify" ><p></p></div>
</div>
