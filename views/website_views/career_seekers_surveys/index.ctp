<div class="survey careerSeekersSurveys form">
	<fieldset>
    <legend><?php __('Career Seekers Survey') ?></legend>
		
		<?php echo $form->create('CareerSeekersSurvey', array('action' => 'add')) ?>
			<?php echo $form->input('first_name', array('before' => '<p class="left label">',
														'between' => '</p><p class="left">',
														'after' => '</p>')) ?>
			<br class="clear" />
			<?php echo $form->input('last_name', array('before' => '<p class="left label">',
														'between' => '</p><p class="left">',
														'after' => '</p>')) ?>
			<br class="clear" />
			<?php echo $form->input('title', array('before' => '<p class="left label">',
														'between' => '</p><p class="left">',
														'after' => '</p>')) ?>
			<br class="clear" />
			<?php echo $form->input('date_you_worked_with_the_business_services_team_or_the_website', array('type' => 'date', 
												  'label' => 'Date you worked with the ' . Configure::read('Company.name') . ' Business Services Team or the ' . Configure::read('Company.name') . ' Website',
												  'dateFormat' => 'MDY',
												  'maxYear' => date('Y'),
												  'before' => '<p class="left">',
												  'between' => '</p><p class="left date">',
												  'after' => '</p>',
												  'div' => 'input date tall')) ?>
			<br class="clear" />
			<div class="input select tall">
				<p class="left label">
					<?php echo $form->label(null, 'Are your comments related to the ' . Configure::read('Company.name') . ' Business Services Team or the ' . Configure::read('Company.name') . ' Website?') ?>
				</p>
				<p class="left">
					<?php echo $form->select('are_your_comments_related_to_the_business_services_team_or_the_website', $locations); ?>
				</p>
			</div>
			<br class="clear" />
			<div class="input text tall">
				<p class="left label">
					<?php echo $form->label(null, 'Name(s) of ' . Configure::read('Company.name') . ' Staff (if any) who assisted you') ?>
				</p>
				<p class="left">
					<?php echo $form->textarea('names_of_staff_who_assisted_you') ?>
				</p>
			</div>
			<br class="clear" />
			
			<p class="wide">Utilizing a scale of 1 to 10 where "1" means "Very Dissatisfied" and "10" means "Very Satisfied," please answer the following three questions :</p>
			<div class="input rating tall">
				<?php echo $form->label(null, '1) Overall, how satisfied are you with the services you received from  ' . Configure::read('Company.name') . '?') ?>
				<br />
				<?php $options = array('1' => '1',
									   '2' => '2', 
									   '3' => '3', 
									   '4' => '4', 
									   '5' => '5', 
									   '6' => '6', 
									   '7' => '7', 
									   '8' => '8', 
									   '9' => '9', 
									   '10' => '10', 
									   'No Answer/Don\'t Know' => 'No Answer/Don\'t Know') ?>
				<?php echo $form->radio('overall_how_satisfied_are_you_with_the_services_you_received', $options, array('legend' => false)) ?>
			</div>
			
			<div class="input rating tall">
				<?php echo $form->label(null, '2) Think about what you expected from  ' . Configure::read('Company.name') . '. How well did the services you received meet your expectations?') ?>
				<br />
				<?php $options = array('1' => '1',
									   '2' => '2', 
									   '3' => '3', 
									   '4' => '4', 
									   '5' => '5', 
									   '6' => '6', 
									   '7' => '7', 
									   '8' => '8', 
									   '9' => '9', 
									   '10' => '10', 
									   'No Answer/Don\'t Know' => 'No Answer/Don\'t Know') ?>
				<?php echo $form->radio('think_about_what_you_expected', $options, array('legend' => false)) ?>
			</div>
			
			<div class="input rating tall">
				<?php echo $form->label(null, '3) Think about the ideal services for other people in your circumstances. How well did the services you received from ' . Configure::read('Company.name') . '  or the ' . Configure::read('Company.name') . ' website compare to your ideal?') ?>
				<br />
				<?php $options = array('1' => '1',
									   '2' => '2', 
									   '3' => '3', 
									   '4' => '4', 
									   '5' => '5', 
									   '6' => '6', 
									   '7' => '7', 
									   '8' => '8', 
									   '9' => '9', 
									   '10' => '10', 
									   'No Answer/Don\'t Know' => 'No Answer/Don\'t Know') ?>
				<?php echo $form->radio('think_about_the_ideal_services_for_other_people', $options, array('legend' => false)) ?>
			</div>
			
			<div class="input supertall">
				<p class="left">
					<?php echo $form->label(null, 'What programs are you currently participating in? (please check all that apply) :') ?><br />
				</p>
				<p class="left date">
					<?php echo $form->checkbox('food_stamps') ?><?php echo $form->label(null, 'Food Stamps (FSET)') ?><br />
					<?php echo $form->checkbox('unemployment_comp') ?><?php echo $form->label(null, 'Unemployment Compensation') ?><br />
					<?php echo $form->checkbox('wia') ?><?php echo $form->label(null, 'WIA') ?><br />
					<?php echo $form->checkbox('vocational_rehab') ?><?php echo $form->label(null, 'Vocational Rehabilitation') ?><br />
					<?php echo $form->checkbox('welfare_transition') ?><?php echo $form->label(null, 'Welfare Transition (AFDC/TANF)') ?><br />
					<?php echo $form->checkbox('veterans_services') ?><?php echo $form->label(null, 'Veterans Services') ?><br />
					<?php echo $form->checkbox('universal_services') ?><?php echo $form->label(null, 'Universal Services (Resource Room, Job Search)') ?><br />
				</p>
			</div>
			<br class="clear" />
			<div class="input select tall">
				<?php $options = array('Billboard' => 'Billboard', 
									   'Brochure' => 'Brochure', 
									   'Employer' => 'Employer', 
									   'Flyer' => 'Flyer', 
									   'Friend or Relative' => 'Friend or Relative', 
									   'Internet Search Engine' => 'Internet Search Engine', 
									   'Job Fair' => 'Job Fair',
									   'Newspaper' => 'Newspaper',
									   'One-Stop Letter' => 'One-Stop Letter',
									   'Phone Book' => 'Phone Book',
									   'County Website' => 'County Website', 
									   'Radio' => 'Radio',
									   'Referred for Services' => 'Referred for Services',
									   'Television' => 'Television',
									   'Other' => 'Other') ?>
				<p class="left label">
					<?php echo $form->label(null, 'How did you learn about ' . Configure::read('Company.name') . ' (Please choose one)') ?>
				</p>
				<p class="left">
					<?php echo $form->select('how_did_you_learn', $options) ?>
				</p>
			</div>
			<br class="clear" />
			<div class="input text tall">
				<p class="left label">
					<?php echo $form->label(null, 'If you chose "Other" above, please eloborate if possible') ?>
				</p>
				<p class="left">
					<?php echo $form->textarea('if_you_chose_Other') ?>
				</p>
			</div>
			<div class="input text">
				<?php $options = array('Agricultural Services' => 'Agricultural Services',
									   'Amusement & Recreation' => 'Amusement & Recreation',
									   'Apparel & Accessory' => 'Apparel & Accessory',
									   'Apparel & Other Textile Products (Mfg)' => 'Apparel & Other Textile Products (Mfg)',
									   'Auto Repair, Services, & Parking' => 'Auto Repair, Services, & Parking',
									   'Automotive Dealers & Service Substations' => 'Automotive Dealers & Service Substations',
									   'Building & Garden Supplies' => 'Building & Garden Supplies',
									   'Business Services' => 'Business Services',
									   'Chemicals & Related Products (Mfg)' => 'Chemicals & Related Products (Mfg)',
									   'Communications' => 'Communications',
									   'Depository Institutions' => 'Depository Institutions',
									   'Eating & Drinking Places' => 'Eating & Drinking Places',
									   'Educational Services' => 'Educational Services',
									   'Electric, Gas & Sanitary Services' => 'Electric, Gas & Sanitary Services',
									   'Electronic & Electric Equipment (Mfg)' => 'Electronic & Electric Equipment (Mfg)',
									   'Fabricated Metal Products (Mfg)' => 'Fabricated Metal Products (Mfg)',
									   'Federal Government' => 'Federal Government',
									   'Fishing, Hunting & Trapping' => 'Fishing, Hunting & Trapping',
									   'Food Stores' => 'Food Stores',
									   'Furniture & Fixtures (Mfg)' => 'Furniture & Fixtures (Mfg)',
									   'Furniture & Homefurnishings Stores' => 'Furniture & Homefurnishings Stores',
									   'General Building Contractors' => 'General Building Contractors',
									   'General Merchandise Stores' => 'General Merchandise Stores',
									   'Health Services' => 'Health Services',
									   'Heavy Construction (except Building)' => 'Heavy Construction (except Building)',
									   'Holding & Other Investment Offices' => 'Holding & Other Investment Offices',
									   'Hotels & Other Lodging Places' => 'Hotels & Other Lodging Places',
									   'Industrial Machinery & Equipment (Mfg)' => 'Industrial Machinery & Equipment (Mfg)',
									   'Instruments & Related Products (Mfg)' => 'Instruments & Related Products (Mfg)',
									   'Insurance Agents, Brokers, & Services' => 'Insurance Agents, Brokers, & Services',
									   'Insurance Carriers' => 'Insurance Carriers',
									   'Leather & Leather Products (Mfg)' => 'Leather & Leather Products (Mfg)',
									   'Legal Services' => 'Legal Services',
									   'Local & Intercity Passenger Transit' => 'Local & Intercity Passenger Transit',
									   'Local Government' => 'Local Government',
									   'Lumber & Wood Products (Mfg)' => 'Lumber & Wood Products (Mfg)',
									   'Membership Organizations' => 'Membership Organizations',
									   'Miscellaneous Manufacturing Industries' => 'Miscellaneous Manufacturing Industries',
									   'Miscellaneous Repair Services' => 'Miscellaneous Repair Services',
									   'Miscellaneous Retail' => 'Miscellaneous Retail',
									   'Miscellaneous Services' => 'Miscellaneous Services',
									   'Motion Pictures' => 'Motion Pictures',
									   'Museums, Botanical Gardens, & Zoos' => 'Museums, Botanical Gardens, & Zoos',
									   'Nondepository Institutions' => 'Nondepository Institutions',
									   'Nonmetallic Minerals (except Fuels)' => 'Nonmetallic Minerals (except Fuels)',
									   'Paper & Related Products (Mfg)',
									   'Personal Services' => 'Personal Services',
									   'Petroleum & Coal Products (Mfg)' => 'Petroleum & Coal Products (Mfg)',
									   'Primary Metal Industries' => 'Primary Metal Industries',
									   'Printing & Publishing' => 'Printing & Publishing',
									   'Private Households & The Self-Employed' => 'Private Households & The Self-Employed',
									   'Railroad Transportation' => 'Railroad Transportation',
									   'Real Estate' => 'Real Estate',
									   'Rubber & Misc. Plastics Products (Mfg)' => 'Rubber & Misc. Plastics Products (Mfg)',
									   'Security & Commodity Brokers' => 'Security & Commodity Brokers',
									   'Social Services' => 'Social Services',
									   'Special Trade Contractors' => 'Special Trade Contractors',
									   'State Government' => 'State Government',
									   'Stone, Clay & Glass Products (Mfg)' => 'Stone, Clay & Glass Products (Mfg)',
									   'Textile Mill Products (Mfg)' => 'Textile Mill Products (Mfg)',
									   'Transportation by Air' => 'Transportation by Air',
									   'Transportation Equipment (Mfg)' => 'Transportation Equipment (Mfg)',
									   'Transportation Services' => 'Transportation Services',
									   'Trucking & Warehousing' => 'Trucking & Warehousing',
									   'U.S. Postal Services' => 'U.S. Postal Services',
									   'Water Transportation' => 'Water Transportation',
									   'Wholesale Trade - Durable Goods' => 'Wholesale Trade - Durable Goods',
									   'Wholesale Trade - Nondurable Goods' => 'Wholesale Trade - Nondurable Goods') ?>
				<p class="left label">
					<?php echo $form->label(null, 'Industry') ?>
				</p>
				<p class="left">
					<?php echo $form->select('industry', $options); ?>
				</p>
			</div>
			<br class="clear" />
			<div class="input text">
				<p class="left label">
					<?php echo $form->label(null, 'Gender') ?>
				</p>
				<p class="left">
					<?php echo $form->select('gender', array('Male' => 'Male', 'Female' => 'Female')) ?>
				</p>
			</div>
			<br class="clear" />
			<div class="input text">
				<?php $options = array('Under 18' => 'Under 18',
									   '18-24' => '18-24',
									   '25-34' => '25-34',
									   '35-44' => '35-44',
									   '45-54' => '45-54',
									   '55-64' => '55-64',
									   '65-Older' => '65-Older')?>
				<p class="left label">
				<?php echo $form->label(null, 'Age') ?>
				</p>
				<p class="left">
				<?php echo $form->select('age', $options) ?>
				</p>
			</div>
			<br class="clear" />
			<div class="input text">
				<?php $options = array('Some High School' => 'Some High School', 
									   'GED' => 'GED', 
									   'High School Diploma' => 'High School Diploma',
									   'Some College' => 'Some College',
									   'Associates Degree' => 'Associates Degree',
									   'Technical School Graduate' => 'Technical School Graduate',
									   'Bachelors Degree' => 'Bachelors Degree',
									   'Masters Degree or higher' => 'Masters Degree or higher') ?>
				<p class="left label">
				<?php echo $form->label('Highest Level of Education Attained') ?>
				</p>
				<p class="left">
				<?php echo $form->select('highest_level_of_education_attained', $options) ?>
				</p>
			</div>
			<br class="clear" />
			<div class="input textarea supertall">
				<p class="left label">
                    <label><?php printf(__('Please share any comments or suggestions you have regarding %s, our Business Services or the %s website. Please enter your name, phone number and email address here, if you would like our staff to contact you.', true), Configure::read('Company.name'), Configure::read('Company.name'))?></label>
				</p>
				<p class="left">
					<?php echo $form->textarea('please_share_any_comments') ?>
				</p>
			</div>
			<br class="clear" />
		<?php echo $form->end(__('Submit', true)); ?>
	</fieldset>
</div>

