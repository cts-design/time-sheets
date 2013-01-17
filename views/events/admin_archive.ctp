<?php echo $this->Html->script('events/admin_archive', array('inline' => FALSE));?>
	<div id="crumbWrapper">
		<span><?php __('You are here') ?> > </span>
		<?php echo $crumb->getHtml(__('Archive', true), null, 'unique') ; ?>
	</div>
<div id="events"></div>
