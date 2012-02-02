/**
 * @author Brandon Cordell
 * @copyright Complete Technology Solutions 2012
 * @link http://ctsfla.com
 * @package ATLAS V3
 */

var JobOrderForms = {
  selectedRecord: null,

  init: function () {
    Ext.QuickTips.init();
    Ext.BLANK_IMAGE_URL = '/img/ext/default/s.gif';

    this.initData();
    this.initGrid();

    this.grid.render('jobOrderFormsGrid');
  },

  initData: function () {
    Ext.define('JobOrderForm', {
      extend: 'Ext.data.Model',
      fields: [
        { name: 'id', type: 'int' },
		{ name: 'federal_id', type: 'int' },
		{ name: 'federal_contractor', type: 'int' },
		'company_name',
		'street_address1',
		'street_address2',
		'city',
		'state',
		{ name: 'zip', type: 'int' },
		'contact_person_and_title',
		'phone_number',
		'cell_or_alternate',
		'fax_number',
		'email_address',
		'company_website_address',
		'worksite_address',
		{ name: 'busline', type: 'int' },
		'route_number',
		'position_title',
		{ name: 'openings', type: 'int' },
		{ name: 'number_requested_to_interview', type: 'int' },
		{ name: 'length_of_experience_desired', type: 'int' },
		'job_description',
		'knowledge_skills_abilities_required',
		{ name: 'years_of_education', type: 'int' },
		'minimum_education_degree',
		{ name: 'minimum_age', type: 'int' },
		{ name: 'will_accept_trainee', type: 'int' },
		{ name: 'email_online_efm_resume', type: 'int' },
		{ name: 'apply_at_jobs_center', type: 'int' },
		{ name: 'company_application_to_be_used', type: 'int' },
		{ name: 'mail_online_efm_resume', type: 'int' },
		{ name: 'apply_in_person', type: 'int' },
		{ name: 'fax_resume', type: 'int' },
		{ name: 'full_time_hours', type: 'int' },
		{ name: 'part_time_hours', type: 'int' },
		{ name: 'temp_hours', type: 'int' },
		{ name: 'length_of_assignment', type: 'int' },
		{ name: 'wages_from', type: 'int' },
		{ name: 'wages_to', type: 'int' },
		{ name: 'hourly', type: 'int' },
		{ name: 'weekly', type: 'int' },
		{ name: 'monthly', type: 'int' },
		{ name: 'annually', type: 'int' },
		{ name: 'first_shift', type: 'int' },
		{ name: 'second_shift', type: 'int' },
		{ name: 'third_shift', type: 'int' },
		{ name: 'overtime_paid', type: 'int' },
		{ name: 'times_may_vary', type: 'int' },
		{ name: 'rotating_shift', type: 'int' },
		{ name: 'sunday', type: 'int' },
		{ name: 'monday', type: 'int' },
		{ name: 'tuesday', type: 'int' },
		{ name: 'wednesday', type: 'int' },
		{ name: 'thursday', type: 'int' },
		{ name: 'friday', type: 'int' },
		{ name: 'saturday', type: 'int' },
		{ name: 'from_time', type: 'int' },
		{ name: 'to_time', type: 'int' },
		{ name: 'medical_insurance', type: 'int' },
		{ name: 'dental_insurance', type: 'int' },
		{ name: 'vision_insurance', type: 'int' },
		{ name: 'life_insurance', type: 'int' },
		{ name: 'std', type: 'int' },
		{ name: 'ltd', type: 'int' },
		{ name: 'ad_d', type: 'int' },
		{ name: 'stock_plan', type: 'int' },
		{ name: '401_k', type: 'int' },
		{ name: 'retirement', type: 'int' },
		{ name: 'paid_vacation', type: 'int' },
		{ name: 'paid_holidays', type: 'int' },
		{ name: 'paid_sick_leave', type: 'int' },
		'other_benefits',
		{ name: 'valid_drivers_license', type: 'int' },
		{ name: 'own_tools', type: 'int' },
		{ name: 'employment_test_given', type: 'int' },
		{ name: 'physical_required', type: 'int' },
		{ name: 'reference_check', type: 'int' },
		{ name: 'criminal_background_check', type: 'int' },
		{ name: 'credit_check', type: 'int' },
		{ name: 'drug_screen', type: 'int' },
		{ name: 'bondable', type: 'int' },
		{ name: 'clean_driving_record', type: 'int' },
		{ name: 'reliable_transportation', type: 'int' },
		'mvr_check_max_points',
		{ name: 'cdl_class', type: 'int' },
		'endorsements_required',
		'computer_programs_required',
		{ name: 'first_time_posting', type: 'int' },
		{ name: 'equal_oppurtunity_employer', type: 'int' },
		'career_ladder',
		{ name: 'hire_with_criminal_conviction', type: 'int' },
		{ name: 'misdemeanor', type: 'int' },
		{ name: 'felony', type: 'int' },
		'depends',
        { name: 'created', type: 'date', dateFormat: 'Y-m-d H:i:s' }
      ]
    });

    Ext.create('Ext.data.Store', {
      autoLoad: true,
      autoSync: true,
      id: 'jobOrderFormStore',
      idProperty: 'id',
      model: 'JobOrderForm',
      proxy: {
        type: 'ajax',
        api: {
          read:    '/admin/job_order_forms/read',
          destroy: '/admin/job_order_forms/destroy'
        },
        reader: {
          type: 'json',
          root: 'job_order_forms'
        },
        writer: {
          type: 'json',
          encode: true,
          root: 'job_order_forms',
          writeAllFields: false
        }
      }
    });
  },

  initGrid: function() {
    var gridToolbar = Ext.create('Ext.toolbar.Toolbar', {
      items: [{
        id: 'deletebtn',
        text: 'Delete Job Order Form',
        icon: '/img/icons/delete.png',
        tooltip: 'Delete the selected job order form',
        disabled: true,
        scope: this,
        handler: function() {
          this.deleteSurvey(this.selectedRecord);
        },
				listeners: {
					itemclick: {
						fn: function(view, rec, item, index, e, opts) {
							var gridToolbars = this.grid.getDockedItem();
							this.selectedRecord rec;
							
							gridToolbars[1].items.items[0].enable();
						},
						scope: this
					},
					itemdblclick: {
	          fn: function(g,ri,e) {
	            this.showWindow(this.selectedRecord);
	          },
						scope: this
					}
				}
      }]
    });

    this.grid = Ext.create('Ext.grid.Panel', {
      store: Ext.data.StoreManager.lookup('jobOrderFormStore'),
      tbar: gridToolbar,
      width: '100%',
      height: 300,
      title: 'Job Order Forms',
      columns: [
        {
          text: 'Company Name',
          dataIndex: 'company_name',
          flex: 1,
          renderer: function (value) {
            if (value === " ") {
              return 'Anonymous';
            }

            return value;
          }
        }, { 
          xtype: 'datecolumn',
          text: 'Submitted',
          dataIndex: 'created',
          format: 'm/d/Y h:i A',
          width: 150
        }
      ],
      listeners: {
        itemclick: {
          fn: function(view, rec, item, index, e, opts) {
            var gridToolbars = this.grid.getDockedItems();
            this.selectedRecord = rec;

            gridToolbars[1].items.items[0].enable();
          },
          scope: this
        },
        itemdblclick: {
          fn: function(g,ri,e) {
            this.showWindow(this.selectedRecord);
          },
          scope: this
        }
      }
    });
  },
  initWindow: function(rec) {
    var windowTpl = new Ext.XTemplate(
      '<div class="x-careerseeker">',
        '<tpl for=".">',
        '<div class="x-careerseeker-row">',
          '<p class="label left">Federal ID (FEIN):</p>',
          '<p class="left">{federal_id}</p>',
          '<br class="clear" />',
        '</div>',
        '<div class="x-careerseeker-row">',
          '<p class="label left">Federal Contractor:</p>',
          '<tpl if="federal_contractor == 1">',
            '<p class="left">Yes</p>',
          '</tpl>',
          '<tpl if="federal_contractor == 0">',
            '<p class="left">No</p>',
          '</tpl>',
          '<br class="clear" />',
        '</div>',
        '<div class="x-careerseeker-row">',
          '<p class="label left">Company Name:</p>',
          '<p class="left">{company_name}</p>',
          '<br class="clear" />',
        '</div>',
        '<div class="x-careerseeker-row">',
          '<p class="label left">Street Address 1:</p>',
          '<p class="left">{street_address1}</p>',
          '<br class="clear" />',
        '</div>',
        '<div class="x-careerseeker-row">',
          '<p class="label left">Street Address 2:</p>',
          '<p class="left">{street_address2}</p>',
          '<br class="clear" />',
        '</div>',
        '<div class="x-careerseeker-row">',
          '<p class="label left">City:</p>',
          '<p class="left">{city}</p>',
          '<br class="clear" />',
        '</div>',
        '<div class="x-careerseeker-row">',
          '<p class="label left">State:</p>',
          '<p class="left">{state}</p>',
          '<br class="clear" />',
        '</div>',
        '<div class="x-careerseeker-row">',
          '<p class="label left">Zip:</p>',
          '<p class="left">{zip}</p>',
          '<br class="clear" />',
        '</div>',
        '<div class="x-careerseeker-row">',
          '<p class="label left">Contact Person and Title:</p>',
          '<p class="left">{contact_person_and_title}</p>',
          '<br class="clear" />',
        '</div>',
        '<div class="x-careerseeker-row">',
          '<p class="label left">Phone Number:</p>',
          '<p class="left">{phone_number}</p>',
          '<br class="clear" />',
        '</div>',
        '<div class="x-careerseeker-row">',
          '<p class="label left">Cell/Alternate:</p>',
          '<p class="left">{cell_or_alternate}</p>',
          '<br class="clear" />',
        '</div>',
        '<div class="x-careerseeker-row">',
          '<p class="label left">Fax Number:</p>',
          '<p class="left">{fax_number}</p>',
          '<br class="clear" />',
        '</div>',
        '<div class="x-careerseeker-row">',
          '<p class="label left">Email Address:</p>',
          '<p class="left">{email_address}</p>',
          '<br class="clear" />',
        '</div>',
        '<div class="x-careerseeker-row">',
          '<p class="label left">Company Website Address:</p>',
          '<p class="left">{company_website_address}</p>',
          '<br class="clear" />',
        '</div>',
        '<div class="x-careerseeker-row">',
          '<p class="label left">Worksite Address (If different from above):</p>',
          '<p class="left">{worksite_address}</p>',
          '<br class="clear" />',
        '</div>',
        '<div class="x-careerseeker-row">',
          '<p class="label left">Bus Line:</p>',
          '<tpl if="bus_line == 1">',
            '<p class="left">Yes</p>',
          '</tpl>',
          '<tpl if="bus_line == 0">',
            '<p class="left">No</p>',
          '</tpl>',
          '<br class="clear" />',
        '</div>',
        '<div class="x-careerseeker-row">',
          '<p class="label left">Route Number:</p>',
          '<p class="left">{route_number}</p>',
          '<br class="clear" />',
        '</div>',
        '<div class="x-careerseeker-row">',
          '<p class="label left">Position Title:</p>',
          '<p class="left">{position_title}</p>',
          '<br class="clear" />',
        '</div>',
        '<div class="x-careerseeker-row">',
          '<p class="label left">Openings:</p>',
          '<p class="left">{openings}</p>',
          '<br class="clear" />',
        '</div>',
        '<div class="x-careerseeker-row">',
          '<p class="label left">Number Requested to Interview:</p>',
          '<p class="left">{number_requested_to_interview}</p>',
          '<br class="clear" />',
        '</div>',
        '<div class="x-careerseeker-row">',
          '<p class="label left">Length of Experience Desired:</p>',
          '<p class="left">{length_of_experience_desired}</p>',
          '<br class="clear" />',
        '</div>',
        '<div class="x-careerseeker-row">',
          '<p class="label left">Job Description:</p>',
          '<p class="left"><a href="/{job_description}" target="_blank">Download</a></p>',
          '<br class="clear" />',
        '</div>',
        '<div class="x-careerseeker-row">',
          '<p class="label left">Knowledge, Skills, and Abilities Required:</p>',
          '<p class="left">{knowledge_skills_abilities_required}</p>',
          '<br class="clear" />',
        '</div>',
        '<div class="x-careerseeker-row">',
          '<p class="label left">Years of Education:</p>',
          '<p class="left">{years_of_education}</p>',
          '<br class="clear" />',
        '</div>',
        '<div class="x-careerseeker-row">',
          '<p class="label left">Minimum Education/Degree:</p>',
          '<p class="left">{minimum_education_degree}</p>',
          '<br class="clear" />',
        '</div>',
        '<div class="x-careerseeker-row">',
          '<p class="label left">Minimum Age:</p>',
          '<p class="left">{minimum_age}</p>',
          '<br class="clear" />',
        '</div>',
        '<div class="x-careerseeker-row">',
          '<p class="label left">Will Accept Trainee:</p>',
          '<tpl if="will_accept_trainee == 1">',
            '<p class="left">Yes</p>',
          '</tpl>',
          '<tpl if="will_accept_trainee == 0">',
            '<p class="left">No</p>',
          '</tpl>',
          '<br class="clear" />',
        '</div>',
        '<div class="x-careerseeker-row">',
          '<p class="label left">How would your company/organization prefer to receive applicant/referral information?:</p>',
            '<ul>',
            '<tpl if="email_online_efm_resume == 1">',
              '<li>Email online EFM resume</li>',
            '</tpl>',
            '<tpl if="apply_at_jobs_center == 1">',
              '<li>Apply at jobs center</li>',
            '</tpl>',
            '<tpl if="company_application_to_be_used == 1">',
              '<li>Company application to be used</li>',
            '</tpl>',
            '<tpl if="mail_online_efm_resume == 1">',
              '<li>Mail online EFM resume</li>',
            '</tpl>',
            '<tpl if="apply_in_person == 1">',
              '<li>Apply in Person</li>',
            '</tpl>',
            '<tpl if="fax_resume == 1">',
              '<li>Fax Resume</li>',
            '</tpl>',
            '</ul>',
          '<br class="clear" />',
        '</div>',
        '<div class="x-careerseeker-row">',
          '<p class="label left">Full-Time (hrs/wk):</p>',
          '<p class="left">{full_time_hours}</p>',
          '<br class="clear" />',
        '</div>',
        '<div class="x-careerseeker-row">',
	        '<p class="label left">Part-Time (hrs/wk):</p>',
	        '<p class="left">{part_time_hours}</p>',
	        '<br class="clear" />',
        '</div>',
        '<div class="x-careerseeker-row">',
	        '<p class="label left">Temporary (hrs/wk):</p>',
	        '<p class="left">{temp_hours}</p>',
	        '<br class="clear" />',
        '</div>',
        '<div class="x-careerseeker-row">',
	        '<p class="label left">Length of Assignment (hrs/wk):</p>',
	        '<p class="left">{length_of_assignment}</p>',
	        '<br class="clear" />',
        '</div>',
        '<div class="x-careerseeker-row">',
	        '<p class="label left">Wages from:</p>',
	        '<p class="left">${wages_from}</p>',
	        '<br class="clear" />',
        '</div>',
        '<div class="x-careerseeker-row">',
	        '<p class="label left">Wages to:</p>',
	        '<p class="left">${wages_to}</p>',
	        '<br class="clear" />',
        '</div>',
        '<div class="x-careerseeker-row">',
          '<p class="label left">Wage Schedule:</p>',
          '<p class="left">',
            '<ul>',
            '<tpl if="hourly == 1">',
              '<li>Hourly</li>',
            '</tpl>',
            '<tpl if="weekly == 1">',
              '<li>Weekly</li>',
            '</tpl>',
            '<tpl if="monthly == 1">',
              '<li>Monthly</li>',
            '</tpl>',
            '<tpl if="annually == 1">',
              '<li>Annually</li>',
            '</tpl>',
            '</ul>',
          '</p>',
          '<br class="clear" />',
        '</div>',
        '<div class="x-careerseeker-row">',
          '<p class="label left">Shifts:</p>',
          '<p class="left">',
            '<ul>',
            '<tpl if="first_shift == 1">',
              '<li>First Shift</li>',
            '</tpl>',
            '<tpl if="second_shift == 1">',
              '<li>Second Shift</li>',
            '</tpl>',
            '<tpl if="third_shift == 1">',
              '<li>Third Shift</li>',
            '</tpl>',
            '<tpl if="overtime_paid == 1">',
              '<li>Overtime Paid</li>',
            '</tpl>',
            '<tpl if="times_may_vary == 1">',
              '<li>Times May Vary</li>',
            '</tpl>',
            '<tpl if="rotating_shift == 1">',
              '<li>Rotating Shift</li>',
            '</tpl>',
            '</ul>',
          '</p>',
          '<br class="clear" />',
        '</div>',
        '<div class="x-careerseeker-row">',
          '<p class="label left">Shift Days:</p>',
          '<p class="left">',
            '<ul>',
            '<tpl if="sunday == 1">',
              '<li>Sunday</li>',
            '</tpl>',
            '<tpl if="monday == 1">',
              '<li>Monday</li>',
            '</tpl>',
            '<tpl if="tuesday == 1">',
              '<li>Tuesday</li>',
            '</tpl>',
            '<tpl if="wednesday == 1">',
              '<li>Wednesday</li>',
            '</tpl>',
            '<tpl if="thursday == 1">',
              '<li>Thursday</li>',
            '</tpl>',
            '<tpl if="friday == 1">',
              '<li>Friday</li>',
            '</tpl>',
            '<tpl if="saturday == 1">',
              '<li>Saturday</li>',
            '</tpl>',
            '</ul>',
          '</p>',
          '<br class="clear" />',
        '</div>',
        '<div class="x-careerseeker-row">',
	        '<p class="label left">From Time:</p>',
	        '<p class="left">{from_time} am</p>',
	        '<br class="clear" />',
        '</div>',
        '<div class="x-careerseeker-row">',
	        '<p class="label left">To Time:</p>',
	        '<p class="left">{to_time} pm</p>',
	        '<br class="clear" />',
        '</div>',
        '<div class="x-careerseeker-row">',
          '<p class="label left">Benefits:</p>',
          '<p class="left">',
            '<ul>',
            '<tpl if="medical_insurance == 1">',
              '<li>Medical Insurance</li>',
            '</tpl>',
            '<tpl if="dental_insurance == 1">',
              '<li>Dental Insurance</li>',
            '</tpl>',
            '<tpl if="vision_insurance == 1">',
              '<li>Vision Insurance</li>',
            '</tpl>',
            '<tpl if="life_insurance == 1">',
              '<li>Life Insurance</li>',
            '</tpl>',
            '<tpl if="std == 1">',
              '<li>STD</li>',
            '</tpl>',
            '<tpl if="ltd == 1">',
              '<li>LTD</li>',
            '</tpl>',
            '<tpl if="ad_d == 1">',
              '<li>AD&D</li>',
            '</tpl>',
            '<tpl if="stock_plan == 1">',
              '<li>Stock Plan</li>',
            '</tpl>',
            '<tpl if="four-oh-one_k == 1">',
              '<li>401K</li>',
            '</tpl>',
            '<tpl if="retirement == 1">',
              '<li>Retirement</li>',
            '</tpl>',
            '<tpl if="paid_vacation == 1">',
              '<li>Paid Vacation</li>',
            '</tpl>',
            '<tpl if="paid_holidays == 1">',
              '<li>Paid Holidays</li>',
            '</tpl>',
            '<tpl if="paid_sick_leave == 1">',
              '<li>Paid Sick Leave</li>',
            '</tpl>',
            '<tpl if="other_benefits != \'\'">',
              '<li>Other: {other_benefits}</li>',
            '</tpl>',
            '</ul>',
          '</p>',
          '<br class="clear" />',
        '</div>',
        '<div class="x-careerseeker-row">',
          '<p class="label left">Hiring Requirements:</p>',
          '<p class="left">',
            '<ul>',
            '<tpl if="valid_drivers_license == 1">',
              '<li>Valid Drivers License</li>',
            '</tpl>',
            '<tpl if="own_tools == 1">',
              '<li>Own Tools</li>',
            '</tpl>',
            '<tpl if="employment_test_given == 1">',
              '<li>Employment Test Given</li>',
            '</tpl>',
            '<tpl if="physical_required == 1">',
              '<li>Physical Required</li>',
            '</tpl>',
            '<tpl if="reference_check == 1">',
              '<li>Reference Check</li>',
            '</tpl>',
            '<tpl if="criminal_background_check == 1">',
              '<li>Criminal Background Check</li>',
            '</tpl>',
            '<tpl if="credit_check == 1">',
              '<li>Credit Check</li>',
            '</tpl>',
            '<tpl if="drug_screen == 1">',
              '<li>Drug Screen</li>',
            '</tpl>',
            '<tpl if="bondable == 1">',
              '<li>Bondable</li>',
            '</tpl>',
            '<tpl if="clean_driving_record == 1">',
              '<li>Clean Driving Record</li>',
            '</tpl>',
            '<tpl if="reliable_transportation == 1">',
              '<li>Reliable Transportation</li>',
            '</tpl>',
            '<tpl if="mvr_check_max_points != \'\'">',
              '<li>MVR Check: {mvr_check_max_points} Max Points</li>',
            '</tpl>',
            '<tpl if="cdl_class != \'\'">',
              '<li>CDL Class: {cdl_class}</li>',
            '</tpl>',
            '</ul>',
          '</p>',
          '<br class="clear" />',
        '</div>',
        '<div class="x-careerseeker-row">',
          '<p class="label left">Endorsements Required:</p>',
          '<p class="left">{endorsements_required}</p>',
          '<br class="clear" />',
        '</div>',
        '<div class="x-careerseeker-row">',
          '<p class="label left">Computer Programs Required:</p>',
          '<p class="left">{computer_programs_required}</p>',
          '<br class="clear" />',
        '</div>',
        '<div class="x-careerseeker-row">',
          '<p class="label left">Is This Your First Time Posting With This Service?:</p>',
          '<tpl if="first_time_posting == 1">',
            '<p class="left">Yes</p>',
          '</tpl>',
          '<tpl if="first_time_posting == 0">',
            '<p class="left">No</p>',
          '</tpl>',
          '<br class="clear" />',
        '</div>',
        '<div class="x-careerseeker-row">',
          '<p class="label left">Equal Opportunity Employer?:</p>',
          '<tpl if="equal_oppurtunity_employer == 1">',
            '<p class="left">Yes</p>',
          '</tpl>',
          '<tpl if="equal_oppurtunity_employer == 0">',
            '<p class="left">No</p>',
          '</tpl>',
          '<br class="clear" />',
        '</div>',
        '<div class="x-careerseeker-row">',
          '<p class="label left">Career Ladder:</p>',
          '<p class="left">{career_ladder}</p>',
          '<br class="clear" />',
        '</div>',
        '<div class="x-careerseeker-row">',
          '<p class="label left">Would You Hire Someone With a Criminal Conviction?:</p>',
          '<tpl if="hire_with_criminal_conviction == 1">',
            '<p class="left">Yes</p>',
          '</tpl>',
          '<tpl if="hire_with_criminal_conviction == 0">',
            '<p class="left">No</p>',
          '</tpl>',
          '<br class="clear" />',
        '</div>',
        '<div class="x-careerseeker-row">',
          '<p class="label left">Misdemeanor?:</p>',
          '<tpl if="misdemeanor == 1">',
            '<p class="left">Yes</p>',
          '</tpl>',
          '<tpl if="misdemeanor == 0">',
            '<p class="left">No</p>',
          '</tpl>',
          '<br class="clear" />',
        '</div>',
        '<div class="x-careerseeker-row">',
          '<p class="label left">Felony?:</p>',
          '<tpl if="felony == 1">',
            '<p class="left">Yes</p>',
          '</tpl>',
          '<tpl if="felony == 0">',
            '<p class="left">No</p>',
          '</tpl>',
          '<br class="clear" />',
        '</div>',
        '<div class="x-careerseeker-row">',
          '<p class="label left">Depends (Please specify):</p>',
          '<p class="left">{depends}</p>',
          '<br class="clear" />',
        '</div>',
        '</tpl>',
      '</div>'
    );

    if (!this.responseWindow) {
      this.responseWindow = new Ext.Window({
        title: 'Job Order Form',
        height: 500,
        width: 650,
        closeAction: 'hide',
        resizable: false,
        bodyStyle: 'padding: 15px',
		maximizable: true,
        autoScroll: true,
        tpl: windowTpl,
        data: rec.data,
		tools: [{
			itemId: 'print',
			type: 'print',
			tooltip: 'Print This Page',
			scope: this,
			handler: function () {
				this.printWindow(windowTpl, rec.data);
			}
		}],
      });
    } else {
      windowTpl.overwrite(this.responseWindow.body, rec.data);
    }
  },
  deleteSurvey: function(rec) {
    Ext.data.StoreManager.lookup('surveyStore').remove(rec);
    Ext.getCmp('deletebtn').disable();
    this.selectedRecord = null;
  },
  showWindow: function(rec) {
    this.initWindow(rec);
    this.responseWindow.show();
  },
	printWindow: function(bodyTpl, data) {
		var body, 
			htmlMarkup,
			html,
			win;
			
		body = bodyTpl.apply(data);
			
		htmlMarkup = [
			'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">',
			'<html>',
			  '<head>',
			    '<meta content="text/html; charset=UTF-8" http-equiv="Content-Type" />',
			    '<link href="/css/print/job_order_forms.css" rel="stylesheet" type="text/css" media="screen,print" />',
			    '<title>asdf</title>',
			  '</head>',
			  '<body>',
					body,
			  '</body>',
			'</html>'           
		];

		html = Ext.create('Ext.XTemplate', htmlMarkup).apply(data); 
		win = window.open('', 'printgrid');
		
		win.document.write(html);
		win.print();
		win.close();
	}
};

Ext.onReady(function() {
  JobOrderForms.init();
});
