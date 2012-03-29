<?php echo $this->Html->script('audits/admin_index.js', array('inline' => FALSE));?>

<div id="crumbWrapper">
    <span><?php __('You are here') ?> > </span>
    <?php echo $crumb->getHtml(__('Audits', true), null, 'unique') ; ?>
</div>

<div id="audits"></div>
<div id="audit-form"></div>
