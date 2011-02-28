<?php echo $this->Html->script('ext/adapter/ext/ext-base', array('inline' => FALSE)) ?>
<?php echo $this->Html->script('ext-all', array('inline' => FALSE)) ?>
<?php echo $this->Html->script('hot_jobs/not_specified', array('inline' => FALSE)) ?>
<div id="crumbWrapper">
    <span>You are here > </span>
<?php echo $crumb->getHtml('Add Hot Job', null, 'unique'); ?></div>
<div class="hotJobs form admin">
    <div class="actions ui-widget-header">
	<ul></ul>
</div>
<?php echo $this->Form->create('HotJob', array('type' => 'file'));?>
	<fieldset>
 		<legend><?php __('Admin Add Hot Job'); ?></legend>
	<?php
		echo $this->Form->input('employer', array(
							'before' => '<p class="left">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
	?>
	<p class="left" style="margin: 0 0 0 100px"><input id="not_specified_link" type="checkbox" />&nbsp;<label>Not Specified</label></p>
	<br class="clear" />
	<?php
		echo $this->Form->input('title', array(
							'before' => '<p class="left">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
		echo $this->Form->input('description', array(
							'before' => '<p class="left">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
		echo $this->Form->input('location', array(
							'before' => '<p class="left">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
		echo $this->Form->input('url', array(
							'before' => '<p class="left">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
		echo $this->Form->input('reference_number', array(
							'label' => 'Reference #',
							'before' => '<p class="left">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
		echo $this->Form->input('contact', array(
							'before' => '<p class="left">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
		echo $this->Form->input('file', array(
							'type' => 'file',
							'before' => '<p class="left">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
