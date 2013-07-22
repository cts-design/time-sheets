<?php $this->Html->scriptStart(array('inline' => false)); ?>
	var programResponseId = <?php echo $this->params['pass'][0]; ?>;
	var requiresApproval = <?php echo $approval; ?>;
	var progName = '<?php echo $programName; ?>';
	var programId = '<?php echo $programId; ?>';
	var programStatus = '<?php echo $programStatus; ?>';
<?php $this->Html->scriptEnd() ?>
<?php echo $this->Html->css('/js/ext/resources/css/BoxSelect', 'stylesheet', array('inline' => false)); ?>
<?php echo $this->Html->script('ext/ux/BoxSelect', array('inline' => false)); ?>
<?php echo $this->Html->script('program_responses/admin_view', array('inline' => FALSE));?>

<div id="crumbWrapper">
    <span><?php __('You are here') ?> > </span>
    <?php echo $crumb->getHtml(__('Program Response', true), null, 'unique');?>
</div>
<div id="ProgramResponsePanel"></div>
