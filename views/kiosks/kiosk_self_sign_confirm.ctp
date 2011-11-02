<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
?>

<div id="selfSignConfirm" class="self-sign-wrapper">
	<h1><?php __('Please verify that the information we have in our records is correct.') ?> </h1>
    <br />
    <?php echo $this->Session->flash(); ?>
    <p class="left align-right">
	<strong><?php __('First Name:') ?> </strong>
    </p>
    <p class="left">
	<?php echo $session->read('Auth.User.firstname'); ?>
    </p>
    <br class="clear" />
    <p class="left align-right">
	<strong><?php __('Last Name:') ?> </strong>
    </p>
    <p class="left">
	<?php echo $session->read('Auth.User.lastname'); ?>
    </p>
    <br class="clear" />
    <?php if(in_array('middle_initial', $fields)) : ?>
	    <p class="left align-right">
		<strong><?php __('Middle Initial:') ?> </strong>
	    </p>
	    <p class="left">
		<?php echo $session->read('Auth.User.middle_initial'); ?>
	    </p>
	    <br class="clear"/>
    <?php endif ?>
    <?php if(in_array('surname', $fields)) : ?>
	    <p class="left align-right">
		<strong><?php __('Surname:') ?> </strong>
	    </p>
	    <p class="left">
		<?php echo $session->read('Auth.User.surname'); ?>
	    </p>
	    <br class="clear"/>
    <?php endif ?>   
    <?php if(in_array('gender', $fields)) : ?>
	    <p class="left align-right">
		<strong><?php __('Gender:') ?> </strong>
	    </p>
	    <p class="left">
		<?php echo $session->read('Auth.User.gender'); ?>
	    </p>
	    <br class="clear"/>
    <?php endif ?>
    <?php if(in_array('dob', $fields)) : ?>
	    <p class="left align-right">
		<strong><?php __('Birth Date:') ?> </strong>
	    </p>
	    <p class="left">
		<?php echo $session->read('Auth.User.dob'); ?>
	    </p>
	    <br class="clear"/>
    <?php endif ?>  
    <?php if(in_array('address_1', $fields)) : ?>
	    <p class="left align-right">
		<strong><?php __('Address:') ?> </strong>
	    </p>
	    <p class="left">
		<?php echo $session->read('Auth.User.address_1'); ?>
	    </p>
	    <br class="clear"/>
    <?php endif ?>
    <?php if(in_array('city', $fields)) : ?>
	    <p class="left align-right">
		<strong><?php __('City:') ?> </strong>
	    </p>
	    <p class="left">
		<?php echo $session->read('Auth.User.city'); ?>
	    </p>
	    <br class="clear"/>
    <?php endif ?>
    <?php if(in_array('county', $fields)) : ?>
	    <p class="left align-right">
		<strong><?php __('County:') ?> </strong>
	    </p>
	    <p class="left">
		<?php echo $session->read('Auth.User.county'); ?>
	    </p>
	    <br class="clear"/>
    <?php endif ?> 
    <?php if(in_array('state', $fields)) : ?>
	    <p class="left align-right">
		<strong><?php __('State:') ?> </strong>
	    </p>
	    <p class="left">
		<?php echo $session->read('Auth.User.state'); ?>
	    </p>
	    <br class="clear"/>
    <?php endif ?>
    <?php if(in_array('zip', $fields)) : ?>
	    <p class="left align-right">
		<strong><?php __('Zip Code:') ?> </strong>
	    </p>
	    <p class="left">
		<?php echo $session->read('Auth.User.zip'); ?>
	    </p>
	    <br class="clear"/>
    <?php endif ?>          
    <?php if(in_array('phone', $fields)) : ?>
	    <p class="left align-right">
		<strong><?php __('Phone:') ?> </strong>
	    </p>
	    <p class="left">
		<?php echo $session->read('Auth.User.phone'); ?>
	    </p>
	    <br class="clear"/>
    <?php endif ?>
    <?php if(in_array('email', $fields)) : ?>
	    <p class="left align-right">
		<strong><?php __('Email Address:') ?> </strong>
	    </p>
	    <p class="left">
		<?php echo $session->read('Auth.User.email'); ?>
	    </p>
	    <br class="clear"/>
    <?php endif ?>  
    <?php if(in_array('language', $fields)) : ?>
	    <p class="left align-right">
		<strong><?php __('Primary Spoken Language:') ?> </strong>
	    </p>
	    <p class="left">
		<?php echo $session->read('Auth.User.language'); ?>
	    </p>
	    <br class="clear"/>
    <?php endif ?> 
    <?php if(in_array('ethnicity', $fields)) : ?>
	    <p class="left align-right">
		<strong><?php __('Ethnicity:') ?> </strong>
	    </p>
	    <p class="left">
		<?php echo $session->read('Auth.User.ethnicity'); ?>
	    </p>
	    <br class="clear"/>
    <?php endif ?> 
    <?php if(in_array('race', $fields)) : ?>
	    <p class="left align-right">
		<strong><?php __('Race:') ?> </strong>
	    </p>
	    <p class="left">
		<?php echo $session->read('Auth.User.race'); ?>
	    </p>
	    <br class="clear"/>
    <?php endif ?>                    
    <div class="actions">
	<?php
	echo $html->link(__('Correct', true), 'self_sign_service_selection', array('class' => 'self-sign-kiosk-link'));
	echo '&nbsp;';
	echo $html->link(__('Incorrect', true), 'self_sign_edit/' . $session->read('Auth.User.id'), array('class' => 'self-sign-kiosk-link'));
	?>
    </div>
    <br class="clear" />
</div>






