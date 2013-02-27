<?php $this->Html->scriptStart(array('inline' => false)); ?>
	var ecourseId = <?php echo $this->params['pass'][0]?>;
<?php $this->Html->scriptEnd() ?>
<?php echo $this->Html->css('ext/statusbar', null, array('inline' => false)); ?>
<?php echo $this->Html->script('ext/ux/StatusBar', array('inline' => false)) ?>
<?php echo $this->Html->script('ecourse_users/admin_index', array('inline' => FALSE));?>

<div id="crumbWrapper">
    <span><?php __('You are here') ?> > </span>
    <?php echo $crumb->getHtml(__('Manage Assignments', true), null, 'unique');?>
</div>
<div id="grid"></div>
