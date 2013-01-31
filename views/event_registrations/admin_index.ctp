<?php $this->Html->scriptStart(array('inline' => FALSE)) ?>
	var eventId = <?php echo $this->params['pass'][0] ?>;
<?php $this->Html->scriptEnd() ?>
<?php echo $this->Html->css('ext/statusbar', null, array('inline' => false)); ?>
<?php echo $this->Html->script('ext/ux/StatusBar', array('inline' => false)) ?>
<?php echo $this->Html->script('event_registrations/admin_index', array('inline' => FALSE));?>
	<div id="crumbWrapper">
		<span><?php __('You are here') ?> > </span>
		<?php echo $crumb->getHtml(__('Event Registrations', true), null, 'unique') ; ?>
	</div>
<div id="event"></div>
