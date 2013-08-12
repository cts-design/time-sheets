<div class="resetPassword">
	<p>Please choose a new password for your account.</p>
<br />
	<?php echo $this->Form->create('User', array('url' => array('action' => 'reset_password', $this->params['pass'][0], $this->params['pass'][1], $this->params['pass'][2]))) ?>
	<?php echo $this->Form->input('pass', array('type' => 'password', 'label' => 'New Password', 'value' => '')) ?>
	<?php echo $this->Form->input('password_confirmation', array('type' => 'password', 'value' => '')) ?>
	<?php echo $this->Form->end('Reset Password') ?>
</div>
