<div id="crumbWrapper">
    <span><?php __('You are here') ?> > </span>
    <?php echo $crumb->getHtml(__('Help Desk Tickets', true), null, 'unique') ; ?>
</div>
<div id="helpDeskForm">
	<?php echo $this->Form->create(null, array('url' => '/admin/help_desk_tickets/index', 'enctype' => 'multipart/form-data')) ?>
	<?php echo $this->Form->input('HelpDeskTicket.first_name', array('before' => '<p>', 'between' => '</p><p>', 'after' => '</p>')) ?>
	<?php echo $this->Form->input('HelpDeskTicket.last_name', array('before' => '<p>', 'between' => '</p><p>', 'after' => '</p>')) ?>
	<?php echo $this->Form->input('HelpDeskTicket.email', array('before' => '<p>', 'between' => '</p><p>', 'after' => '</p>')) ?>
	<?php $operatingSystems = array(
		'xp32' => 'Windows XP 32 Bit',
		'xp64' => 'Windows XP 64 Bit',
		'vista32' => 'Windows Vista 32 Bit',
		'vista64' => 'Windows Vista 64 Bit',
		'win732' => 'Windows 7 32 Bit',
		'win764' => 'Windows 7 64 Bit'); 
	?> 
	<?php echo $this->Form->input('HelpDeskTicket.operating_system', array(
		'type' => 'select',
		'options' => $operatingSystems,
		'empty' => 'Please Select',
		'before' => '<p>',
		'between' => '</p><p>',
		'after' => '</p>')) ?>
	<?php $browsers = array(
		'ie8' => 'Internet Explorer 8',
		'ie9' => 'Internet Explorer 9',
		'chrome' => 'Google Chrome',
		'firefox' => 'Firefox');
	?>
	<?php echo $this->Form->input('HelpDeskTicket.browser', array(
		'type' => 'select', 
		'options' => $browsers, 
		'empty' => 'Please Select',
		'before' => '<p>',
		'between' => '</p><p>',
		'after' => '</p>')) ?>
	<?php echo $this->Form->input('HelpDeskTicket.url', array(
		'before' => '<p>', 
		'between' => '</p><p>', 
		'after' => '</p><span>Please copy and paste the url of the page you are having a problem with from the browser address bar.</span>')) 
	?>
	<?php echo $this->Form->input('HelpDeskTicket.title', array(
		'label' => 'Brief Description',
		'before' => '<p>',
		'between' => '</p><p>',
		'after' => '</p>')) ?>
	<?php echo $this->Form->input('HelpDeskTicket.issue', array(
		'type' => 'textarea', 
		'label' => 'Description',
		'before' => '<p>',
		'between' => '</p><p>',
		'after' => '</p>')) ?>
	<?php echo $this->Form->input('HelpDeskTicket.screen_shot', array(
		'type' => 'file',
		'before' => '<p>',
		'between' => '</p><p>',
		'after' => '</p>')) ?>
	<span>Please include a screen shot of the issue you are experiencing if possible. </span>
	<?php echo $this->Form->end('Send') ?>
</div>
