<div id="ProgramResponseUserInfo">
<?php if(isset($user)) :?>
	<p class="left label"><strong><?php __('First Name:') ?></strong></p><p class="left"><?php echo ucwords($user['firstname'])?></p>
	<br class="clear" />
	<p class="left label"><strong><?php __('Middle Initial:') ?></strong></p><p class="left"><?php echo ucwords($user['middle_initial'])?></p>
	<br class="clear" />
	<p class="left label"><strong><?php __('Last Name:') ?></strong></p><p class="left"><?php echo ucwords($user['lastname'])?></p>
	<p class="left label"><strong><?php __('Surname:') ?></strong></p><p class="left"><?php echo ucwords($user['surname'])?></p>
	<br class="clear" />
	<p class="left label"><strong><?php __('SurName:') ?></strong></p><p class="left"><?php echo ucwords($user['surname'])?></p>
	<br class="clear" />	
	<p class="left label"><strong><?php __('SSN:') ?></strong></p><p class="left"><?php echo $user['ssn']?></p>
	<br class="clear" />
	<p class="left label"><strong><?php __('Address:') ?></strong></p><p class="left"><?php echo ucwords($user['address_1'])?></p>
	<br class="clear" />
	<p class="left label"><strong><?php __('Address 2:') ?></strong></p><p class="left"><?php echo ucwords($user['address_2'])?></p>
	<br class="clear" />
	<p class="left label"><strong><?php __('City:') ?></strong></p><p class="left"><?php echo ucwords($user['city'])?></p>
	<br class="clear" />
	<p class="left label"><strong><?php __('State:') ?></strong></p><p class="left"><?php echo strtoupper($user['state'])?></p>
	<br class="clear" />
	<p class="left label"><strong><?php __('Zip Code:') ?></strong></p><p class="left"><?php echo $user['zip']?></p>
	<br class="clear" />
	<p class="left label"><strong><?php __('Phone:') ?></strong></p><p class="left"><?php echo $user['phone']?></p>
	<br class="clear" />
	<p class="left label"><strong><?php __('Email:') ?></strong></p><p class="left"><?php echo $user['email']?></p>
	<br class="clear" />
	<p class="left label"><strong><?php __('Gender:') ?></strong></p><p class="left"><?php echo ucfirst($user['gender'])?></p>
	<br class="clear" />
	<p class="left label"><strong><?php __('Date of Birth:') ?></strong></p><p class="left"><?php echo $user['dob']?></p>
<?php endif ?>
<div>	
