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
		<?php asort($program['ProgramField'])?>
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
			<?php $esignInstructions = Set::extract('/ProgramInstruction[type=esign]/text', $program); ?>
			<fieldset>
				<legend>E-Sign</legend>
				<p class="bot-mar-10"><?php echo $esignInstructions[0] ?></p>
				<p class="bot-mar-10">Please put your first and last name in the box to agree.</p>
				<?php echo $form->input('form_esignature', array('label' => 'I agree', 'after' => '<br />')) ?>	
			</fieldset>
			<br />
		<?php endif ?>		
		<?php echo $form->end('Submit'); ?>										
	<?php endif; ?>
</div>