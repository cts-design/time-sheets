<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
?>

<?php echo $this->Html->script('self_sign_logs/index', array('inline' => false)); ?>
<div id="crumbWrapper">
    <span><?php __('You are here') ?> > </span>
    <?php echo $crumb->getHtml(__('Self Sign Queue', true), null, 'unique'); ?>
</div>
<div class="admin">
    <div id="selfSignSearch" class="form">
	    <ul>
		<li><?php echo $this->Form->create('SelfSignLog', array('action' => 'get_services_ajax')) ?>
		    <?php echo $this->Form->input(__('Locations', true), array('type' => 'select', 'options' => $locations, 'empty' => "All Locations")) ?>
		</li>
		<li><span><strong><?php echo __('Services', true)?></strong></span></li>
		<li><div class="scrollingCheckboxes"><?php echo $this->Form->input(__('Services', true), array('label' => false, 'type' => 'select', 'multiple' => 'checkbox', 'options' => $buttons));?></div></li>
	    </ul>
	<br class="clear" />
    </div>
    <div id="selfSignLogs"></div>
</div>
