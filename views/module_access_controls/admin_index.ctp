<?php $this->Html->script('module_access_controls/tree.js', array('inline' => false)); ?>
<div id="crumbWrapper">
    <span><?php __('You are here') ?> > </span>
    <?php echo $crumb->getHtml(__('Module Access Controls', true), null, 'unique'); ?>
</div>

<div id="moduleAccessControlPanel"></div>
