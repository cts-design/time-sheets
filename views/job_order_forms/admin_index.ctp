<?php echo $this->Html->script('job_order_forms/admin_index', array('inline' => false)) ?>
<div id="crumbWrapper">
    <span><?php __('You are here') ?> > </span>
    <?php echo $crumb->getHtml(__('Job Order Forms', true), null, 'unique'); ?>
</div>
<div class="jobOrderForms">

	<div id="jobOrderFormsGrid"></div>
</div>

