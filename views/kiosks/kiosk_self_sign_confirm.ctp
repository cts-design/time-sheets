<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
?>
<?php echo $this->Html->scriptStart() ?>
	$(document).ready(function(){	
		$('a').click(function(e){
			e.preventDefault();
			var link = $(this).attr('href');
			$('.self-sign-kiosk-link').button("disable");
			window.location.href = link;
		});
	});
<?php echo $this->Html->scriptEnd() ?>
<div id="selfSignConfirm" class="self-sign-wrapper">
	<h1><?php __('Please verify that the information we have in our records is correct.') ?> </h1>
    <br />
    <?php echo $this->Session->flash(); ?>
    <p class="left align-right">
	<strong><?php __('First Name:') ?> </strong>
    </p>
    <p class="left">
	<?= $user['User']['firstname'] ?>
    </p>
    <br class="clear" />
    <p class="left align-right">
	<strong><?php __('Last Name:') ?> </strong>
    </p>
    <p class="left">
	<?= $user['User']['lastname'] ?>
    </p>
    <br class="clear" />
    <?php if(in_array('middle_initial', $fields)) : ?>
	    <p class="left align-right">
		<strong><?php __('Middle Initial:') ?> </strong>
	    </p>
	    <p class="left">
		<?= $user['User']['middle_initial'] ?>
	    </p>
	    <br class="clear"/>
    <?php endif ?>
    <?php if(in_array('surname', $fields)) : ?>
	    <p class="left align-right">
		<strong><?php __('Surname:') ?> </strong>
	    </p>
	    <p class="left">
		<?= $user['User']['surname'] ?>
	    </p>
	    <br class="clear"/>
    <?php endif ?>   
    <?php if(in_array('gender', $fields)) : ?>
	    <p class="left align-right">
		<strong><?php __('Gender:') ?> </strong>
	    </p>
	    <p class="left">
		<?= $user['User']['gender'] ?>
	    </p>
	    <br class="clear"/>
    <?php endif ?>
    <?php if(in_array('dob', $fields)) : ?>
	    <p class="left align-right">
		<strong><?php __('Birth Date:') ?> </strong>
	    </p>
	    <p class="left">
		<?= $user['User']['dob'] ?>
	    </p>
	    <br class="clear"/>
    <?php endif ?>  
    <?php if(in_array('address_1', $fields)) : ?>
	    <p class="left align-right">
		<strong><?php __('Address:') ?> </strong>
	    </p>
	    <p class="left">
		<?= $user['User']['address_1'] ?>
	    </p>
	    <br class="clear"/>
    <?php endif ?>
    <?php if(in_array('city', $fields)) : ?>
	    <p class="left align-right">
		<strong><?php __('City:') ?> </strong>
	    </p>
	    <p class="left">
		<?= $user['User']['city'] ?>
	    </p>
	    <br class="clear"/>
    <?php endif ?>
    <?php if(in_array('county', $fields)) : ?>
	    <p class="left align-right">
		<strong><?php __('County:') ?> </strong>
	    </p>
	    <p class="left">
		<?= $user['User']['county'] ?>
	    </p>
	    <br class="clear"/>
    <?php endif ?> 
    <?php if(in_array('state', $fields)) : ?>
	    <p class="left align-right">
		<strong><?php __('State:') ?> </strong>
	    </p>
	    <p class="left">
		<?= $user['User']['state'] ?>
	    </p>
	    <br class="clear"/>
    <?php endif ?>
    <?php if(in_array('zip', $fields)) : ?>
	    <p class="left align-right">
		<strong><?php __('Zip Code:') ?> </strong>
	    </p>
	    <p class="left">
		<?= $user['User']['zip'] ?>
	    </p>
	    <br class="clear"/>
    <?php endif ?>          
    <?php if(in_array('phone', $fields)) : ?>
	    <p class="left align-right">
		<strong><?php __('Phone:') ?> </strong>
	    </p>
	    <p class="left">
		<?= $user['User']['phone'] ?>
	    </p>
	    <br class="clear"/>
    <?php endif ?>
    <?php if(in_array('email', $fields)) : ?>
	    <p class="left align-right">
		<strong><?php __('Email Address:') ?> </strong>
	    </p>
	    <p class="left">
		<?= $user['User']['email'] ?>
	    </p>
	    <br class="clear"/>
    <?php endif ?>  
    <?php if(in_array('language', $fields)) : ?>
	    <p class="left align-right">
		<strong><?php __('Primary Spoken Language:') ?> </strong>
	    </p>
	    <p class="left">
		<?= $user['User']['language'] ?>
	    </p>
	    <br class="clear"/>
    <?php endif ?> 
    <?php if(in_array('ethnicity', $fields)) : ?>
	    <p class="left align-right">
		<strong><?php __('Ethnicity:') ?> </strong>
	    </p>
	    <p class="left">
		<?= $user['User']['ethnicity'] ?>
	    </p>
	    <br class="clear"/>
    <?php endif ?> 
    <?php if(in_array('race', $fields)) : ?>
	    <p class="left align-right">
		<strong><?php __('Race:') ?> </strong>
	    </p>
	    <p class="left">
		<?= $user['User']['race'] ?>
	    </p>
	    <br class="clear"/>
    <?php endif ?> 
    <?php if(in_array('veteran', $fields)) : ?>
	    <p class="left align-right">
		<strong><?php __('US veteran:') ?> </strong>
	    </p>
	    <p class="left">
		<?php echo ($user['User']['veteran'] ? 'Yes' : 'No'); ?>
	    </p>
	    <br class="clear"/>
    <?php endif ?>                       
    <?php if(in_array('disability', $fields)) : ?>
	    <p class="left align-right">
		<strong><?php __('Do you have a substantial disability:') ?> </strong>
	    </p>
	    <p class="left">
		<?php echo ($user['User']['disability'] ? 'Yes' : 'No'); ?>
	    </p>
	    <br class="clear"/>
	<?php endif ?>     
	<div class="actions">
	<a href="/kiosk/kiosks/self_sign_service_selection" class="self-sign-kiosk-link">Correct</a>

	<?php
	echo '&nbsp;';
	echo $html->link(__('Incorrect', true), 'self_sign_edit/' . $session->read('Auth.User.id'), array('class' => 'self-sign-kiosk-link'));
	?>
    </div>
    <br class="clear" />
</div>
