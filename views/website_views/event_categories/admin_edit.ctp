<div id="crumbWrapper">
    <span><?php __('You are here') ?> > </span>
<?php echo $crumb->getHtml(__('Add Event Category', true), null, 'unique'); ?></div>
<div class="eventCategories form admin">
    <div class="actions ui-widget-header">
	<ul></ul>
</div>
<?php echo $this->Form->create('EventCategory');?>
	<fieldset>
 		<legend><?php __('Admin Edit Event Category'); ?></legend>
	<?php
		echo $this->Form->input('id', array(
							'before' => '<p class="left">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
		echo $this->Form->input('name', array(
							'before' => '<p class="left">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
