<?php $this->Html->scriptStart(array('inline' => false)); ?>
	var ecourseResponseId = <?php echo $this->params['pass'][0]; ?>;
	var ecourseName = '<?php echo $ecourseName; ?>';
	var ecourseId = '<?php echo $ecourseId; ?>';
	var userName = '<?php echo $userName; ?>';
<?php $this->Html->scriptEnd() ?>
<?php echo $this->Html->script('ecourse_responses/admin_view', array('inline' => FALSE));?>

<div id="crumbWrapper">
    <span><?php __('You are here') ?> > </span>
    <?php echo $crumb->getHtml(__('Ecourse Response', true), null, 'unique');?>
</div>
<div id="EcourseResponsePanel"></div>
