<?php echo $this->Html->script('program_responses/admin_view', array('inline' => FALSE));?>

<div id="crumbWrapper">
    <span>You are here > </span>
    <?php echo $crumb->getHtml('Program Response', null, 'unique');?>
</div>
<div id="programResponsePanel"></div>