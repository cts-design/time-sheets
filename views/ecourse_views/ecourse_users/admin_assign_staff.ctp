<?php $this->Html->scriptStart(array('inline' => false)); ?>
	var ecourseId = <?php echo $this->params['pass'][0]?>;
<?php $this->Html->scriptEnd() ?>
<?php echo $this->Html->script('ecourse_users/admin_assign_staff', array('inline' => FALSE));?>

<div id="crumbWrapper">
    <span><?php __('You are here') ?> > </span>
    <?php echo $crumb->getHtml(__('Assign Staff', true), null, 'unique');?>
</div>
<div id="search"></div>
<div id="grid"></div>
