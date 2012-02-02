<?php echo $this->Html->script('job_seeker_new_hires/admin_index', array('inline' => false)) ?>
<div id="crumbWrapper">
    <span><?php __('You are here') ?> > </span>
    <?php echo $crumb->getHtml(__('Job Seeker New Hires', true), null, 'unique'); ?>
</div>
<div class="jobOrderForms">

	<div id="jobOrderFormsGrid"></div>
</div>

