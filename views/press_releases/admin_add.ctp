<div id="crumbWrapper">
    <span>You are here > </span>
    <?php echo $crumb->getHtml('Add Press Release', null, 'unique'); ?>
</div>
<div class="pressReleases form admin">
    <div class="actions ui-widget-header">
	<ul></ul>
</div>
<?php echo $this->Form->create('PressRelease', array('enctype' => 'multipart/form-data'));?>
	<fieldset>
 		<legend><?php __('Admin Add Press Release'); ?></legend>
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
