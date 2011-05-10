<?php echo (!empty($instructions) ? '<p>' . $instructions . '</p>' : '' ) ?>
<br />
<?php echo $form->create('Program', array('action' => 'get_started')); ?>
<?php echo $form->input('redirect', array('type' => 'hidden', 'value' => $redirect)); ?>
<?php echo $form->input('ProgramResponse.program_id', array('type' => 'hidden', 'value' => $this->params['pass'][0])); ?>
<?php echo $form->end('Get Started') ?>
