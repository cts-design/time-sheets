<?php echo $html->script('program_responses/toggle_instructions', array('inline' => false)) ?>
<div><a id="Toggle" class="small" style="display: none">Hide Instructions</a></div>
<div id="Instructions"><?php echo $instructions ?></div>
<noscript>
	<div id="Instructions"><?php echo $instructions ?></div>
</noscript>
<br />
<?php echo $this->element($element) ?>

<div id="Acknowledge">
	<?php echo $form->create('Program', array('action' => 'view_media/' . $this->params['pass'][0])) ?>
	<br />
	<br />
	<?php echo $form->input('ProgramResponse.viewed_media', array(
		'type' => 'checkbox', 
		'label' => 'I acknowledge that I have viewed the presentation and completely understand its content. 
		I futher understand that it is my responsibility to abide by the rules and regulations. 
		I also understand clearly that my failure to comply with the conditions may result in the loss of
		services.')) ?>
	<br class="clear" />
	<?php echo $form->input('ProgramResponse.program_id',  array('type' => 'hidden', 'value' => $this->params['pass'][0]));?>
	<?php echo $form->end('Submit') ?>
</div>