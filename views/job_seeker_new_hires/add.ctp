<div id="crumbWrapper">
    <span>You are here > </span>
<?php echo $crumb->getHtml('Add Job Seeker New Hire', null, 'unique'); ?></div>
<div class="job-order-form">
    <div class="actions ui-widget-header">
	<ul></ul>
</div>
<?php echo $this->Form->create('JobSeekerNewHire');?>

	<?php
	echo $this->Form->input('name_of_employee', array(
						'before' => '<p class="left label">',
						'between' => '</p><p class="left">',
						'after' => '</p>'));
	echo '<br class="clear" />';
	echo $this->Form->input('last_four_ssn_employee', array(
						'before' => '<p class="left label">',
						'between' => '</p><p class="left">',
						'after' => '</p>'));
	echo '<br class="clear" />';
		echo $this->Form->input('employer_name', array(
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
		echo $this->Form->input('street_address1', array(
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
		echo $this->Form->input('street_address2', array(
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
		echo $this->Form->input('city', array(
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
		echo $this->Form->input('state', array(
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
		echo $this->Form->input('zip', array(
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
		echo $this->Form->input('phone_number', array(
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
		echo $this->Form->input('start_date', array(
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
		echo $this->Form->input('job_title', array(
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
		echo $this->Form->input('salary', array(
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
		echo $this->Form->input('hours_per_week', array(
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
		?>
		<div class="input radio required">
			<p class="left label"><label for="JobSeekerNewHireBenefits">Benefits</label></p>
			<p class="left">
				<?php 
					if (isset($this->data['JobSeekerNewHire']) && $this->data['JobSeekerNewHire']['benefits'] == 1) {
						$nochecked = null;
						$yeschecked = ' checked="checked"';
					} else if (isset($this->data['JobSeekerNewHire']) && $this->data['JobSeekerNewHire']['benefits'] == 0) {
						$yeschecked = null;
						$nochecked = ' checked="checked"';	
					} else {
						$nochecked = $yeschecked = null;
					}
				?>
				<input type="hidden" name="data[JobSeekerNewHire][benefits]" value="" id="JobSeekerNewHireBenefits" />
				<input type="radio" name="data[JobSeekerNewHire][benefits]" value="0" id="JobSeekerNewHireBenefits0" <?php echo $nochecked ?>/>
				<label for="JobSeekerNewHireBenefits0" class="normal">No</label>
				<input type="radio" name="data[JobSeekerNewHire][benefits]" value="1" id="JobSeekerNewHireBenefits1" <?php echo $yeschecked ?>/>
				<label for="JobSeekerNewHireBenefits1" class="normal">Yes</label>
			</p>
		</div>
		<?php
		echo '<br class="clear" />';
	?>
<?php $options = array(
	'label' => 'Submit',
	'name' => 'submit',
	'div' => array(
		'class' => 'submit-button'
	)
) ?>
<?php echo $this->Form->end($options);?>
</div>
