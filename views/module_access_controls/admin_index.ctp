<?php $this->Html->script('module_access_controls/tree.js', array('inline' => false)); ?>
<div id="crumbWrapper">
    <span>You are here > </span>
    <?php echo $crumb->getHtml('Module Access Controls', null, 'unique'); ?>
</div>

<div id="moduleAccessControlPanel"></div>
