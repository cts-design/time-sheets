<?php echo $this->Html->script('bar_code_definitions/admin_index', array('inline' => false)) ?>
<div id="crumbWrapper">
    <span>You are here > </span>
    <?php echo $crumb->getHtml('Bar Code Definitions', null, 'unique'); ?>
</div>

<div id="barCodeDefinitions">
</div>
