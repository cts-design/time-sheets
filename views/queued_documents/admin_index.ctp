<?php echo $this->Html->script('queued_documents/admin_index', array('inline' => false)); ?>
<div id="crumbWrapper">
    <span><?php __('You are here') ?> > </span>
    <?php echo $crumb->getHtml(__('Queued Documents', true), null, 'unique'); ?>
</div>