<?php
	$this->Html->css('ext/calendar/calendar', null, array('inline' => FALSE));
	$this->Html->script('ext/ux/RowEditor', array('inline' => false));
	$this->Html->script('ext/ux/calendar/calendar-all-debug', array('inline' => FALSE));
	$this->Html->script('events/calendar', array('inline' => FALSE));
?>
<div id="crumbWrapper">
    <span>You are here > </span>
    <?php echo $crumb->getHtml('Events', null, 'unique'); ?></div>
<div class="events">
	<div class="actions ui-widget-header">
		<ul></ul>
	</div>
	<span id="app-msg" class="x-hidden"></span>
	<div id="calendar-div"></div>
</div>