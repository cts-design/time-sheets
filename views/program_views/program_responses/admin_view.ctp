<?php $this->Html->scriptStart(array('inline' => false)); ?>
	var programResponseId = <?php echo $this->params['pass'][0]; ?>;
	var requiresApproval = <?php echo $approval; ?>;
<?php $this->Html->scriptEnd() ?>
<?php echo $this->Html->script('program_responses/admin_view', array('inline' => FALSE));?>

<div id="crumbWrapper">
    <span>You are here > </span>
    <?php echo $crumb->getHtml('Program Response', null, 'unique');?>
</div>
<div id="ProgramResponsePanel"></div>