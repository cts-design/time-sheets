<div id="crumbWrapper">
    <span><?php __('You are here') ?> > </span>
<?php echo $crumb->getHtml(__('Add In The News', true), null, 'unique'); ?></div>
<div class="inTheNews form admin">
    <div class="actions ui-widget-header">
	<ul></ul>
</div>
<?php echo $this->Form->create('InTheNews');?>
	<fieldset>
 		<legend><?php __('Admin Add In The News'); ?></legend>
	<?php
		echo $this->Form->input('title', array(
							'before' => '<p class="left">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
		echo $this->Form->input('reporter', array(
							'before' => '<p class="left">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
		echo $this->Form->input('summary', array(
							'before' => '<p class="left">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
		echo $this->Form->input('link', array(
							'before' => '<p class="left">',
							'between' => '</p><p class="left">',
							'after' => '</p>',
							'maxLength' => 255));
		echo '<br class="clear" />';
		echo $this->Form->input('posted_date', array(
							'maxYear' => date('Y'),
							'before' => '<p class="left">',
							'between' => '</p><p class="date left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
