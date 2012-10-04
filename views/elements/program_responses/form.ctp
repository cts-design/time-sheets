<?php if(!empty($formFields)) : ?>
        <?php asort($formFields)?>
        <?php foreach($formFields as $k => $v) : ?>
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
            <?php echo $this->Form->input('ProgramResponseActivity.0.' . $v['name'], $attributes); ?>
			<?php if($this->params['action'] === 'edit_form') : ?>
				<?php echo $this->Form->input('ProgramResponseActivity.id', array('type' => 'hidden')); ?>
			<?php endif ?> 
            <?php echo '<br />'; ?>
        <?php endforeach; ?>
		 <?php if($acceptanceRequired) : ?>
            <fieldset>
                <legend><?php __('User Acceptance') ?></legend>
                <p class="bot-mar-10"><?php echo $acceptanceInstructions[0]; ?></p>
                <p class="bot-mar-10"><?php __('Please enter your first and last name in the box below.') ?></p>
                <?php echo $form->input('ProgramResponseActivity.0.user_acceptance', array('label' => __('I agree', true), 'after' => '<br />')) ?>
            </fieldset>
            <br />
        <?php endif ?>
       <?php if($esignRequired) : ?>
            <fieldset>
                <legend><?php __('Electronic Signature') ?></legend>
                <p class="bot-mar-10"><?php echo $esignInstructions[0]; ?></p>
                <p class="bot-mar-10"><?php __('Please enter your first and last name in the box below.') ?></p>
                <?php echo $form->input('ProgramResponseActivity.0.esign', array('label' => __('I agree', true), 'after' => '<br />')) ?>
            </fieldset>
            <br />
        <?php endif ?>

<?php endif; ?>
<?php echo $this->Form->end(__('Submit', true)); ?>
