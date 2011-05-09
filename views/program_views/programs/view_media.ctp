<?php echo $html->script('program_responses/toggle_instructions', array('inline' => false)) ?>
<div><a id="Toggle" class="small" style="display: none">Show Instructions</a></div>
<p id="Instructions" style="display: none"><?php echo $instructions ?></p>
<noscript>
	<p id="Instructions"><?php echo $instructions ?></p>
</noscript>
<br />
<?php echo $this->element($element) ?>

<div id="Aknowledge">
	<?php echo $form->create('Program', array('action' => 'view_media/' . $this->params['pass'][0])) ?>
	<br />
	<p>
		By checking the box below I agree that I have reviewed the instructions and/or media above.
	</p>
	<br />
	<?php echo $form->input('ProgramResponse.viewed_media', array('type' => 'checkbox', 'label' => 'I agree')) ?>
	<?php echo $form->input('ProgramResponse.program_id',  array('type' => 'hidden', 'value' => $this->params['pass'][0]));?>
	<?php echo $form->end('Submit') ?>
</div>