<div id="crumbWrapper">
    <span>You are here > </span>
<?php echo $crumb->getHtml('Add Kiosk Survey Question', null, 'unique'); ?></div>
<div class="kioskSurveyQuestions form admin">
    <div class="actions ui-widget-header">
	<ul></ul>
</div>
<?php echo $this->Form->create('KioskSurveyQuestion');?>
	<fieldset>
 		<legend><?php __('Edit Kiosk Survey Question'); ?></legend>
	<?php
		echo $this->Form->input('id', array(
							'before' => '<p class="left">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
		echo $this->Form->input('kiosk_survey_id', array(
							'before' => '<p class="left">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
		echo $this->Form->input('question', array(
							'before' => '<p class="left">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
