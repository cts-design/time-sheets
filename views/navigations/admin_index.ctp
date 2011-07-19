<?php
    $this->Html->script('navigations/tree', array('inline' => FALSE))
?>

<div id="crumbWrapper">
    <span>You are here > </span>
    <?php echo $crumb->getHtml('Navigation', null, 'unique'); ?>
</div>

<div class="navigations">
    <div id="window-div"></div>
    <div id="tree-div"></div>
</div>
