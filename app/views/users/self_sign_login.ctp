<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
?>
<?php echo $this->Html->script('users/self.sign.login', array('inline' => false)) ?>

<div class="self-sign-wrapper">
    <h1>Welcome to <?php echo Configure::read('Company.name').'.'; ?> Please sign in here.</h1>
    <div id="errorMessage"></div>
    <?php echo $this->Session->flash(); ?>
    <?php
    echo $this->Session->flash('auth');
    echo $form->create('User', array('action' => 'self_sign_login'));
    echo $form->input('User.username', array(
	'label' => __('Last Name', true),
	'before' => '<p class="left">',
	'between' => '</p><p class="left">',
	'after' => '</p><br class="clear"/>'));
    echo $this->Form->input('User.dob', array(
	'type' => 'text',
	'maxlength' => 10,
	'label' => __('Birth Date <span class="small gray">(mm/dd/yyyy)</span>', true),
	'before' => '<p class="left">',
	'between' => '</p><p class="left">',
	'after' => '</p><br class="clear"/>'));
    echo $form->input('User.password', array(
	'label' => __('Last 4 Digits of Your SSN', true),
	'before' => '<p class="left">',
	'between' => '</p><p class="left">',
	'after' => '</p><br class="clear"/>'));
    echo $form->hidden('User.self_sign', array('value' => 'self'));
    echo $form->end(array('label' => 'Login', 'class' => 'self-sign-kiosk-button'));
    ?>
</div>