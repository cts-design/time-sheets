<?php $this->Html->scriptStart(array('inline' => false)); ?>
	var ecourseId = <?php echo $this->params['pass'][0]?>;
	var ecourseName = '<?php echo $ecourseName ?>';
<?php $this->Html->scriptEnd() ?>
<?php echo $this->Html->script('ecourse_responses/admin_index', array('inline' => FALSE));?>

<div id="crumbWrapper">
    <span><?php __('You are here') ?> > </span>
    <?php echo $crumb->getHtml(__('Ecourse Responses', true), null, 'unique');?>
</div>
<div id="ecourseResponseTabs"></div>
