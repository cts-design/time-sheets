<p>Enter your username or your email address and we'll send you password reset instructions to the email you used to register the account.</p>
<br />

<div class="forgotPassword">
	<?= $this->Form->create('User', array('action' => 'forgot_password')) ?>
	<?= $this->Form->input('email', array('label' => 'Email Address')) ?>
	<?= $this->Form->end('Send Password Reset Instructions') ?>
</div>
