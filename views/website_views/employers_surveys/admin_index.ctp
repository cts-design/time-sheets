<?php echo $this->Html->script('employers_surveys/grid', array('inline' => false)) ?>
<div id="crumbWrapper">
    <span><?php __('You are here') ?> > </span>
    <?php echo $crumb->getHtml(__('Employers Surveys', true), null, 'unique'); ?></div>
<div class="employersSurveys">
	<div id="surveys"></div>
</div>
