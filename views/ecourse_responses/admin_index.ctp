<?php echo $this->Html->script('ecourse_responses/admin_index', array('inline' => FALSE));?>

<div id="crumbWrapper">
    <span><?php __('You are here') ?> > </span>
    <?php echo $crumb->getHtml(__('Ecourse Responses', true), null, 'unique');?>
</div>
<div id="ecourseResponseTabs"></div>
