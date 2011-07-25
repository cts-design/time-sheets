<div id="crumbWrapper">
    <span><?php __('You are here') ?> > </span>
    <?php echo $crumb->getHtml(__('Add Chairman Report', true), null, 'unique'); ?>
</div>
<div class="chairmanReports form admin">
    <div class="actions ui-widget-header">
	<ul></ul>
</div>
<?php echo $this->Form->create('ChairmanReport', array('enctype' => 'multipart/form-data'));?>
	<fieldset>
 		<legend><?php __('Admin Add Chairman Report'); ?></legend>
	<?php
		echo $this->Form->input('title', array(
							'before' => '<p class="left">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
		echo $this->Form->input('file', array(
							'before' => '<p class="left">',
							'between' => '</p><p class="left">',
							'after' => '</p>',
                                                        'type' => 'file'));
		echo '<br class="clear" />';
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
