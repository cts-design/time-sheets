<?php if(!empty($formFields)): ?>
        <?php

            foreach($formFields as $k => $v)
            {
                $options = json_decode($v['options'], true);
                $attributes = array(
                    'label' => $v['label'],
                    'type' => $v['type'],
                    'after' => '<br />',
                    'options' => $options
                );

                if($v['attributes'] != '' || $v['attributes'] != NULL)
                {
                    $attributes['between'] = '<p class="field-instructions">' . $v['instructions'] . '</p>';
                }

                if(!empty($v['attributes']))
                {
                    $attributes = Set::merge($attributes, json_decode($v['attributes']));
                }

                echo $this->Form->input('ProgramResponseActivity.0.' . $v['name'], $attributes);

                if($this->params['action'] === 'edit_form')
                {
                    echo $this->Form->input('ProgramResponseActivity.id', array('type' => 'hidden'));
                }

                echo '<br />';
            }

        ?>
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
    <?php endif ?>
<?php echo $this->Form->end(__('Submit', true)); ?>
