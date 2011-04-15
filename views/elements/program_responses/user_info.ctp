<style>
	p {margin-bottom: 5px}
	.label {width: 100px; text-align: right; margin-right: 10px;}
</style>

<div style="padding: 10px; width: 500px;">
<?php if(isset($user)) :?>
	<p class="left label"><strong>First Name:</strong></p><p class="left"><?php echo $user['firstname']?></p>
	<br class="clear" />
	<p class="left label"><strong>Middle Initial:</strong></p><p class="left"><?php echo $user['middle_initial']?></p>
	<br class="clear" />
	<p class="left label"><strong>Last Name:</strong></p><p class="left"><?php echo $user['lastname']?></p>
	<br class="clear" />
	<p class="left label"><strong>SSN:</strong></p><p class="left"><?php echo $user['ssn']?></p>
	<br class="clear" />
	<p class="left label"><strong>Address:</strong></p><p class="left"><?php echo $user['address_1']?></p>
	<br class="clear" />
	<p class="left label"><strong>Address 2:</strong></p><p class="left"><?php echo $user['address_2']?></p>
	<br class="clear" />
	<p class="left label"><strong>City:</strong></p><p class="left"><?php echo $user['city']?></p>
	<br class="clear" />
	<p class="left label"><strong>State:</strong></p><p class="left"><?php echo $user['state']?></p>
	<br class="clear" />
	<p class="left label"><strong>Zip Code:</strong></p><p class="left"><?php echo $user['zip']?></p>
	<br class="clear" />
	<p class="left label"><strong>Phone:</strong></p><p class="left"><?php echo $user['phone']?></p>
	<br class="clear" />
	<p class="left label"><strong>Alt Phone:</strong></p><p class="left"><?php echo $user['alt_phone']?></p>
	<br class="clear" />
	<p class="left label"><strong>Gender:</strong></p><p class="left"><?php echo $user['gender']?></p>
	<br class="clear" />
	<p class="left label"><strong>Date of Birth:</strong></p><p class="left"><?php echo $user['dob']?></p>
<?php endif ?>
<div>	