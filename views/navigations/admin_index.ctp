<?php
    $this->Html->script('navigations/tree', array('inline' => FALSE))
?>

<div id="crumbWrapper">
<span><?php __('You are here') ?> > </span>
    <?php echo $crumb->getHtml(__('Navigation', true), null, 'unique'); ?>
</div>

<div id="tree-div"></div>
