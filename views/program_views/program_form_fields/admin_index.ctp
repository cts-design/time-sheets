<?php echo $this->Html->css('ext/statusbar', null, array('inline' => false)); ?>
<?php echo $this->Html->script('ext/ux/StatusBar', array('inline' => false)) ?>
<?php echo $this->Html->script('program_form_fields/admin_index', array('inline' => FALSE));?>
<?php echo $this->Html->scriptStart(array('inline' => FALSE)) ?>
	programId = '<?= $this->params['pass'][0] ?>';
	programStepId = '<?= $programStepId ?>';
<?php echo $this->Html->scriptEnd() ?>
<div id="crumbWrapper">
    <span><?php __('You are here') ?> > </span>
    <?php echo $crumb->getHtml(__('Program Form Fields', true), null, 'unique') ; ?>
</div>

<div id="programFormFieldPanel"></div>
