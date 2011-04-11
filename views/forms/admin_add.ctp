<div id="crumbWrapper">
    <span>You are here > </span>
<?php echo $crumb->getHtml('Add Form', null, 'unique'); ?></div>
<div class="forms form admin">
    <div class="actions ui-widget-header">
	<ul></ul>
</div>
<?php echo $this->Form->create('Form');?>
	<fieldset>
 		<legend><?php __('Admin Add Form'); ?></legend>
	<?php
		echo $this->Form->input('title', array(
							'before' => '<p class="left">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
