<div id="crumbWrapper">
    <span>You are here > </span>
<?php echo $crumb->getHtml('Add Job Order Form', null, 'unique'); ?></div>
<div class="job-order-form">
<?php // debug($this->data) ?>
<?php echo $this->Form->create('JobOrderForm', array('enctype' => 'multipart/form-data'));?>
	<?php
		echo $this->Form->input('federal_id', array(
							'label' => 'Federal ID (FEIN)',
							'type' => 'text',
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
	?>
	<div class="input radio">
		<p class="left label"><label for="JobOrderFormFederalContractor">Federal Contractor</label></p>
		<p class="left">
			<?php 
				if (isset($this->data['JobOrderForm']) && $this->data['JobOrderForm']['federal_contractor'] == 1) {
					$nochecked = null;
					$yeschecked = ' checked="checked"';
				} else if (isset($this->data['JobOrderForm']) && $this->data['JobOrderForm']['federal_contractor'] == 0) {
					$yeschecked = null;
					$nochecked = ' checked="checked"';	
				} else {
					$nochecked = $yeschecked = null;
				}
			?>
			<input type="hidden" name="data[JobOrderForm][federal_contractor]" value="" id="JobOrderFormFederalContractor" />
			<input type="radio" name="data[JobOrderForm][federal_contractor]" value="0" id="JobOrderFormFederalContractor0" <?php echo $nochecked ?>/>
			<label for="JobOrderFormFederalContractor0" class="normal">No</label>
			<input type="radio" name="data[JobOrderForm][federal_contractor]" value="1" id="JobOrderFormFederalContractor" <?php echo $yeschecked ?>/>
			<label for="JobOrderFormFederalContractor1" class="normal">Yes</label>
		</p>
	</div>
	<?php
		echo '<br class="clear" />';
		echo $this->Form->input('company_name', array(
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
		echo $this->Form->input('street_address1', array(
							'label' => 'Street Address 1',
							'before' => '<p class="left label street-address">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
		echo $this->Form->input('street_address2', array(
							'label' => 'Street Address 2',
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
							'options' => $states,
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
		echo $this->Form->input('zip', array(
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
		echo $this->Form->input('contact_person_and_title', array(
							'type' => 'text',
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
		echo $this->Form->input('phone_number', array(
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
		echo $this->Form->input('cell_or_alternate', array(
							'label' => 'Cell/Alternate',
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
		echo $this->Form->input('fax_number', array(
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
		echo $this->Form->input('email_address', array(
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
		echo $this->Form->input('company_website_address', array(
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
		echo $this->Form->input('worksite_address', array(
							'label' => 'Worksite Address <span>(If different from above)</span>',
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
		?>
		<div class="input radio">
			<p class="left label"><label for="JobOrderFormBusLine">Bus Line</label></p>
			<p class="left">
				<?php 
					if (isset($this->data['JobOrderForm']) && $this->data['JobOrderForm']['bus_line'] == 1) {
						$nochecked = null;
						$yeschecked = ' checked="checked"';
					} else if (isset($this->data['JobOrderForm']) && $this->data['JobOrderForm']['bus_line'] == 0) {
						$yeschecked = null;
						$nochecked = ' checked="checked"';	
					} else {
						$nochecked = $yeschecked = null;
					}
				?>
				<input type="hidden" name="data[JobOrderForm][bus_line]" value="" id="JobOrderFormBusLine" />
				<input type="radio" name="data[JobOrderForm][bus_line]" value="0" id="JobOrderFormBusLine0" <?php echo $nochecked ?>/>
				<label for="JobOrderFormBusLine0" class="normal">No</label>
				<input type="radio" name="data[JobOrderForm][bus_line]" value="1" id="JobOrderFormBusLine1" <?php echo $yeschecked ?>/>
				<label for="JobOrderFormBusLine1" class="normal">Yes</label>
			</p>
		</div>
		<?php
		echo '<br class="clear" />';
		echo $this->Form->input('route_number', array(
							'label' => 'Route #',
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
		echo $this->Form->input('position_title', array(
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
		echo $this->Form->input('openings', array(
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
		echo $this->Form->input('number_requested_to_interview', array(
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
		echo $this->Form->input('length_of_experience_desired', array(
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
		echo $this->Form->input('job_description', array(
							'type' => 'file',
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
		echo $this->Form->input('knowledge_skills_abilities_required', array(
							'label' => 'Knowledge, Skills, and Abilities Required',
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
		echo $this->Form->input('years_of_education', array(
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
		echo $this->Form->input('minimum_education_degree', array(
							'label' => 'Minimum Education/Degree',
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
		echo $this->Form->input('minimum_age', array(
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
		?>
		<div class="input radio left applicant-info">
			<p class="left label"><label>How would your company/organization prefer to receive applicant/referral information? <span>(Check all that apply)</span></label></p>
		</div>
		<div class="left checkboxgroup">
		<?php
		echo $this->Form->input('will_accept_trainee', array(
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));

		echo $this->Form->input('email_online_efm_resume', array(
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));

		echo $this->Form->input('apply_at_jobs_center', array(
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo $this->Form->input('company_application_to_be_used', array(
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));

		echo $this->Form->input('mail_online_efm_resume', array(
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));

		echo $this->Form->input('apply_in_person', array(
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));

		echo $this->Form->input('fax_resume', array(
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		?></div><br class="clear" />
		<?php
		echo '<br class="clear" />';
		echo $this->Form->input('full_time_hours', array(
							'label' => 'Full-Time <span>(hrs/wk)</span>',
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
		echo $this->Form->input('part_time_hours', array(
							'label' => 'Part-Time <span>(hrs/wk)</span>',
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
		echo $this->Form->input('temp_hours', array(
							'label' => 'Temporary <span>(hrs/wk)</span>',
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
		echo $this->Form->input('length_of_assignment', array(
							'label' => 'Length of Assignment <span>(hrs/wk)</span>',
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
		echo $this->Form->input('wages_from', array(
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
		echo $this->Form->input('wages_to', array(
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
		?>
		<div class="input radio left wage-schedule">
			<p class="left label"><label>Wage Schedule</label></p>
		</div>
		<div class="left checkboxgroup">
		<?php
		echo $this->Form->input('hourly', array(
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));

		echo $this->Form->input('weekly', array(
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));

		echo $this->Form->input('monthly', array(
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));

		echo $this->Form->input('annually', array(
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		?></div><br class="clear" />
		<?php
		echo '<br class="clear" />';
		?>
		<div class="input radio left shifts">
			<p class="left label"><label>Shifts</label></p>
		</div>
		<div class="left checkboxgroup">
		<?php
		echo $this->Form->input('first_shift', array(
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));

		echo $this->Form->input('second_shift', array(
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));

		echo $this->Form->input('third_shift', array(
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));

		echo $this->Form->input('overtime_paid', array(
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));

		echo $this->Form->input('times_may_vary', array(
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));

		echo $this->Form->input('rotating_shift', array(
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		?></div><br class="clear" />
		<?php
		echo '<br class="clear" />';
		?>
		<div class="input radio left shift-days">
			<p class="left label"><label>Shift Days</label></p>
		</div>
		<div class="left checkboxgroup">
		<?php
		echo $this->Form->input('sunday', array(
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));

		echo $this->Form->input('monday', array(
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));

		echo $this->Form->input('tuesday', array(
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));

		echo $this->Form->input('wednesday', array(
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));

		echo $this->Form->input('thursday', array(
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));

		echo $this->Form->input('friday', array(
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));

		echo $this->Form->input('saturday', array(
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
		?></div><br class="clear" />
		<?php
		echo $this->Form->input('from_time', array(
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
		echo $this->Form->input('to_time', array(
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
		?>
		<div class="input radio left benefits">
			<p class="left label"><label>Benefits</label></p>
		</div>
		<div class="left checkboxgroup">
		<?php
		echo $this->Form->input('medical_insurance', array(
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));

		echo $this->Form->input('dental_insurance', array(
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));

		echo $this->Form->input('vision_insurance', array(
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));

		echo $this->Form->input('life_insurance', array(
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));

		echo $this->Form->input('std', array(
							'label' => 'STD',
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));

		echo $this->Form->input('ltd', array(
							'label' => 'LTD',
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));

		echo $this->Form->input('ad_d', array(
							'label' => 'AD&D',
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));

		echo $this->Form->input('stock_plan', array(
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));

		echo $this->Form->input('401_k', array(
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));

		echo $this->Form->input('retirement', array(
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));

		echo $this->Form->input('paid_vacations', array(
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));

		echo $this->Form->input('paid_holidays', array(
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));

		echo $this->Form->input('paid_sick_leave', array(
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));

		echo $this->Form->input('other_benefits', array(
							'before' => '<p class="left label other-benefits">',
							'between' => '</p><p class="left other-benefits">',
							'after' => '</p>'));
		?></div><br class="clear" />
		<?php
		echo '<br class="clear" />';
		?>
		<div class="input radio left hiring-requirements">
			<p class="left label"><label>Hiring Requirements</label></p>
		</div>
		<div class="left checkboxgroup">
		<?php
		echo $this->Form->input('valid_drivers_license', array(
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));

		echo $this->Form->input('own_tools', array(
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));

		echo $this->Form->input('employment_test_given', array(
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));

		echo $this->Form->input('physical_required', array(
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));

		echo $this->Form->input('reference_check', array(
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));

		echo $this->Form->input('criminal_background_check', array(
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));

		echo $this->Form->input('credit_check', array(
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));

		echo $this->Form->input('drug_screen', array(
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));

		echo $this->Form->input('bondable', array(
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
							
		echo $this->Form->input('reliable_transportation', array(
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
							
		echo $this->Form->input('clean_driving_record', array(
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));

		echo $this->Form->input('mvr_check_max_points', array(
							'label' => 'MVR Check',
							'before' => '<p class="left label mvr-check">',
							'between' => '</p><p class="left mvr-check">',
							'after' => '  Max Points</p>'));

		echo $this->Form->input('cdl_class', array(
							'before' => '<p class="left label cdl-class">',
							'between' => '</p><p class="left cdl-class">',
							'after' => '</p>'));


		echo '<br class="clear" />';
		?></div><br class="clear" />
		<?php
		echo '<br class="clear" />';
		echo $this->Form->input('endorsements_required', array(
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
		echo $this->Form->input('computer_programs_required', array(
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
		?>
		<br />
		<div class="input radio">
			<p class="left label"><label>Is This Your First Time Posting With This Service?</label></p>
			<p class="left">
				<?php 
					if (isset($this->data['JobOrderForm']) && $this->data['JobOrderForm']['first_time_posting'] == 1) {
						$nochecked = null;
						$yeschecked = ' checked="checked"';
					} else if (isset($this->data['JobOrderForm']) && $this->data['JobOrderForm']['first_time_posting'] == 0) {
						$yeschecked = null;
						$nochecked = ' checked="checked"';	
					} else {
						$nochecked = $yeschecked = null;
					}
				?>
				<input type="hidden" name="data[JobOrderForm][first_time_posting]" value="" id="JobOrderFormFirstTimePosting" />
				<input type="radio" name="data[JobOrderForm][first_time_posting]" value="0" id="JobOrderFormFirstTimePosting0" <?php echo $nochecked ?>/>
				<label for="JobOrderFormFirstTimePosting0" class="normal">No</label>
				<input type="radio" name="data[JobOrderForm][first_time_posting]" value="1" id="JobOrderFormFirstTimePosting1" <?php echo $yeschecked ?>/>
				<label for="JobOrderFormFirstTimePosting1" class="normal">Yes</label>
			</p>
		</div>
		<br class="clear" />
		<div class="input radio">
			<p class="left label"><label>Equal Opportunity Employer?</label></p>
			<p class="left">
				<?php 
					if (isset($this->data['JobOrderForm']) && $this->data['JobOrderForm']['equal_oppurtunity_employer'] == 1) {
						$nochecked = null;
						$yeschecked = ' checked="checked"';
					} else if (isset($this->data['JobOrderForm']) && $this->data['JobOrderForm']['equal_oppurtunity_employer'] == 0) {
						$yeschecked = null;
						$nochecked = ' checked="checked"';	
					} else {
						$nochecked = $yeschecked = null;
					}
				?>
				<input type="hidden" name="data[JobOrderForm][equal_oppurtunity_employer]" value="" id="JobOrderFormEqualOppurtunityEmployer" />
				<input type="radio" name="data[JobOrderForm][equal_oppurtunity_employer]" value="0" id="JobOrderFormEqualOppurtunityEmployer0" <?php echo $nochecked ?>/>
				<label for="JobOrderFormEqualOppurtunityEmployer0" class="normal">No</label>
				<input type="radio" name="data[JobOrderForm][equal_oppurtunity_employer]" value="1" id="JobOrderFormEqualOppurtunityEmployer1" <?php echo $yeschecked ?>/>
				<label for="JobOrderFormEqualOppurtunityEmployer1" class="normal">Yes</label>
			</p>
		</div>
		<?php
		echo '<br class="clear" />';
		echo $this->Form->input('career_ladder', array(
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
		echo '<br class="clear" />';
		?>
		<br />
		<div class="input radio">
			<p class="left label"><label>Would You Hire Someone With a Criminal Conviction?</label></p>
			<p class="left">
				<?php 
					if (isset($this->data['JobOrderForm']) && $this->data['JobOrderForm']['hire_with_criminal_conviction'] == 1) {
						$nochecked = null;
						$yeschecked = ' checked="checked"';
					} else if (isset($this->data['JobOrderForm']) && $this->data['JobOrderForm']['hire_with_criminal_conviction'] == 0) {
						$yeschecked = null;
						$nochecked = ' checked="checked"';	
					} else {
						$nochecked = $yeschecked = null;
					}
				?>
				<input type="hidden" name="data[JobOrderForm][hire_with_criminal_conviction]" value="" id="JobOrderFormHireWithCriminalConviction" />
				<input type="radio" name="data[JobOrderForm][hire_with_criminal_conviction]" value="0" id="JobOrderFormHireWithCriminalConviction0" <?php echo $nochecked ?>/>
				<label for="JobOrderFormHireWithCriminalConviction0" class="normal">No</label>
				<input type="radio" name="data[JobOrderForm][hire_with_criminal_conviction]" value="1" id="JobOrderFormHireWithCriminalConviction1" <?php echo $yeschecked ?>/>
				<label for="JobOrderFormHireWithCriminalConviction1" class="normal">Yes</label>
			</p>
		</div>
		<br class="clear" />
		<div class="input radio">
			<p class="left label"><label>Misdemeanor?</label></p>
			<p class="left">
				<?php 
					if (isset($this->data['JobOrderForm']) && $this->data['JobOrderForm']['misdemeanor'] == 1) {
						$nochecked = null;
						$yeschecked = ' checked="checked"';
					} else if (isset($this->data['JobOrderForm']) && $this->data['JobOrderForm']['misdemeanor'] == 0) {
						$yeschecked = null;
						$nochecked = ' checked="checked"';	
					} else {
						$nochecked = $yeschecked = null;
					}
				?>
				<input type="hidden" name="data[JobOrderForm][misdemeanor]" value="" id="JobOrderFormMisdemeanor" />
				<input type="radio" name="data[JobOrderForm][misdemeanor]" value="0" id="JobOrderFormMisdemeanor0" <?php echo $nochecked ?>/>
				<label for="JobOrderFormMisdemeanor0" class="normal">No</label>
				<input type="radio" name="data[JobOrderForm][misdemeanor]" value="1" id="JobOrderFormMisdemeanor1" <?php echo $yeschecked ?>/>
				<label for="JobOrderFormMisdemeanor1" class="normal">Yes</label>
			</p>
		</div>
		<br class="clear" />
		<div class="input radio">
			<p class="left label"><label>Felony?</label></p>
			<p class="left">
				<?php 
					if (isset($this->data['JobOrderForm']) && $this->data['JobOrderForm']['felony'] == 1) {
						$nochecked = null;
						$yeschecked = ' checked="checked"';
					} else if (isset($this->data['JobOrderForm']) && $this->data['JobOrderForm']['felony'] == 0) {
						$yeschecked = null;
						$nochecked = ' checked="checked"';	
					} else {
						$nochecked = $yeschecked = null;
					}
				?>
				<input type="hidden" name="data[JobOrderForm][felony]" value="" id="JobOrderFormFelony" />
				<input type="radio" name="data[JobOrderForm][felony]" value="0" id="JobOrderFormFelony0" <?php echo $nochecked ?>/>
				<label for="JobOrderFormFelony0" class="normal">No</label>
				<input type="radio" name="data[JobOrderForm][felony]" value="1" id="JobOrderFormFelony1" <?php echo $yeschecked ?>/>
				<label for="JobOrderFormFelony1" class="normal">Yes</label>
			</p>
		</div>
		<?php
		echo '<br class="clear" />';
		echo $this->Form->input('depends', array(
							'label' => 'Depends <span>(Please specify)</span>',
							'before' => '<p class="left label">',
							'between' => '</p><p class="left">',
							'after' => '</p>'));
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
