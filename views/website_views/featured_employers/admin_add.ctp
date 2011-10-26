<div id="crumbWrapper">
    <span><?php __('You are here') ?> > </span>
<?php echo $crumb->getHtml(__('Add Featured Employer', true), null, 'unique'); ?></div>
<div class="featuredEmployers form admin">
    <div class="actions ui-widget-header">
	<ul></ul>
</div>
<?php echo $this->Form->create('FeaturedEmployer', array('type' => 'file'));?>
	<fieldset>
 		<legend><?php __('Admin Add Featured Employer'); ?></legend>
	<?php
		echo $this->Form->input('name', array(
							'before' => '<p class="left">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
		echo $this->Form->input('description', array(
							'before' => '<p class="left">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
		echo $this->Form->input('image', array(
							'type' => 'file',
							'before' => '<p class="left">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
		echo $this->Form->input('url', array(
							'before' => '<p class="left">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
