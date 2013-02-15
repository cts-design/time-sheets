<?php $this->Html->scriptStart(array('inline' => false)); ?>
	var ecourseId = <?php echo $this->params['pass'][0]?>;
<?php $this->Html->scriptEnd() ?>
<?php echo $this->Html->script('ecourses/admin_assign_users', array('inline' => FALSE));?>

<div id="crumbWrapper">
    <span><?php __('You are here') ?> > </span>
    <?php echo $crumb->getHtml(__('Assign Users', true), null, 'unique');?>
</div>
<div id="users"></div>
