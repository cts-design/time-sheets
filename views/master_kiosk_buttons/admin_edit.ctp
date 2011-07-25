<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
?>
<div id="crumbWrapper">
    <span><?php __('You are here') ?> > </span>
    <?php echo $crumb->getHtml(__('Edit Master Kiosk Button', true), null, 'unique'); ?>
</div>
<div class="admin">
    <div id ="masterKioskButtons" class="masterKioskButtons mini-form ">
	<?php echo $this->Form->create('MasterKioskButton'); ?>
	<fieldset>
	    <legend><?php __('Edit Kiosk Button'); ?></legend>
	    <?php
	    echo $this->Form->input('id');
	    echo $this->Form->input('name');
	    echo '<br />';
	    if($count > 0 ) {
		echo $this->Form->input('tag', array('class' => 'tag'));
	    }
	    echo $this->Form->hidden('parent_id');
	    echo $this->Form->hidden('kiosk_id');
	    ?>
	</fieldset>
	<?php echo $this->Form->end(__('Submit', true)); ?>
    </div>
</div>
