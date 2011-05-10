<?php echo $html->script('program_responses/toggle_instructions', array('inline' => false)) ?>
<a id="Toggle" class="small" style="display: none">Hide Instructions</a>
<p id="Instructions"><?php echo $instructions ?></p>
<noscript>
	<p id="Instructions"><?php echo $instructions ?></p>
</noscript>

<br />
<div id="ProgramForm">
	<?php if(!empty($program['ProgramField'])) : ?>
		<?php echo $form->create('ProgramResponse', array('action' => 'index/' . $program['Program']['id'])); ?>	
		<?php foreach($program['ProgramField'] as $k => $v) : ?>
			<?php $options = json_decode($v['options'], true); ?>
			<?php $attributes = array(
								    'label' => $v['label'],
								    'type' => $v['type'],
								    'between' => '<p class="field-instructions">' . $v['instructions'] . '</p>',
								    'after' => '<br />',
								    'options' => $options); ?>
			<?php if(!empty($v['attributes'])) : ?>
				<?php $attributes = Set::merge($attributes, json_decode($v['attributes'])); ?>
			<?php endif; ?>						    
			<?php echo $form->input($v['name'], $attributes); ?>
			<?php echo '<br />'; ?>																					
		<?php endforeach; ?>
		<?php if($program['Program']['form_esign_required']) : ?>
			<fieldset>
				<legend>E-Sign</legend>
				<span>Please put your last name in the box to agree.</span>
				<?php echo $form->input('form_esignature', array('label' => 'I agree')) ?>	
			</fieldset>
			<br />
		<?php endif ?>		
		<?php echo $form->end('Submit'); ?>										
	<?php endif; ?>
</div>