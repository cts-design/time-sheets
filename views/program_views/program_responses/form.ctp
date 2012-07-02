<?php echo $html->script('program_responses/toggle_instructions', array('inline' => false)) ?>
  <div class="show-instructions">
    <a href="#" ><?php __('Show instructions') ?></a>
  </div>
  <div id="instructions">
    <?php echo $instructions ?>
    <div class="hide-instructions">
      <a href="#"><?php __('Hide these instructions') ?></a>
    </div>
  </div>
	<noscript>
		<div id="instructions"><?php echo $instructions ?></div>
	</noscript>

<br />

<div class="required bot-mar-10"><label></label> <?php __('indicates required fields.') ?></div>
<div id="ProgramForm">
    <?php if(!empty($formFields)) : ?>
        <?php echo $form->create('ProgramResponse', array('action' => 'form/' . $this->params['pass'][0] . '/' . $this->params['pass'][1])); ?>
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
            <?php echo $form->input('ProgramResponseActivity.0.' . $v['name'], $attributes); ?>
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

        <?php echo $form->end(__('Submit', true)); ?>
    <?php endif; ?>
</div>
