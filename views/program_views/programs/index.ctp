<?php echo (!empty($instructions) ? '<div id="Instructions">' . $instructions . '</div>' : '' ) ?>
<br />
<?php echo $form->create('Program', array('action' => 'get_started')); ?>
<?php echo $form->input('redirect', array('type' => 'hidden', 'value' => $redirect)); ?>
<?php echo $form->input('ProgramResponse.program_id', array('type' => 'hidden', 'value' => $this->params['pass'][0])); ?>
<?php echo $form->end(__('Get Started', true)) ?>
