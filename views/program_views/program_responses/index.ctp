<?php echo $html->script('program_responses/toggle_instructions', array('inline' => false)) ?>
<a id="Toggle" class="small" style="display: none"><?php __('Hide Instructions') ?></a>
<div id="Instructions"><?php echo $instructions ?></div>
<noscript>
	<div id="Instructions"><?php echo $instructions ?></div>
</noscript>

<br />
<?php if($viewMediaAgainLink) : ?>
	<div> <a href="<?php echo $viewMediaAgainLink ?>">View Media Again </a></div>
	<br />
<?php endif ?>

<div class="required bot-mar-10"><label></label> <?php __('indicates required fields.') ?></div>
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
                <legend><?php __('Electronic Signature') ?></legend>
				<p class="bot-mar-10"><?php echo $esignInstructions[0] ?></p>
                <p class="bot-mar-10"><?php __('Please enter your first and last name in the box below.') ?></p>
				<?php echo $form->input('form_esignature', array('label' => __('I agree', true), 'after' => '<br />')) ?>	
			</fieldset>
			<br />
		<?php endif ?>		
		<?php echo $form->end(__('Submit', true)); ?>										
	<?php endif; ?>
</div>

