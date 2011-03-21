<div id="crumbWrapper">
    <span>You are here > </span>
<?php echo $crumb->getHtml('Add Career Seekers Survey', null, 'unique'); ?></div>
<div class="careerSeekersSurveys form admin">
    <div class="actions ui-widget-header">
	<ul></ul>
</div>
<?php echo $this->Form->create('CareerSeekersSurvey');?>
	<fieldset>
 		<legend><?php __('Add Career Seekers Survey'); ?></legend>
	<?php
		echo $this->Form->input('answers', array(
							'before' => '<p class="left">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
