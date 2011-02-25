<?php
    // @TODO add these to the head of the layout when we integrate ExtJS throughout the project
    $this->Html->script('ext/adapter/ext/ext-base-debug', array('inline' => FALSE));
    $this->Html->script('ext-all-debug', array('inline' => FALSE));
    $this->Html->script('navigations/tree', array('inline' => FALSE))
?>

<div id="crumbWrapper">
    <span>You are here > </span>
    <?php echo $crumb->getHtml('Navigation', null, 'unique'); ?>
</div>

<div class="navigations admin">
    <div id="add-button-div"></div>
    <div id="remove-button-div"></div>
    <div id="window-div"></div>
    <div id="tree-div"></div>
</div>