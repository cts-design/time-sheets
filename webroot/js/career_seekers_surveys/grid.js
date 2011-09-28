/**
 * @author Brandon Cordell
 * @copyright Complete Technology Solutions 2011
 * @link http://ctsfla.com
 * @package ATLAS V3
 */

var CareerSeekers = {
  selectedRecord: null,

  init: function() {
    Ext.Compat.showErrors = true;
    Ext.QuickTips.init();
    Ext.BLANK_IMAGE_URL = '/img/ext/default/s.gif';

    this.initData();
    this.initGrid();

    this.grid.render('surveys');
  },

  initData: function() {
    Ext.define('CareerSeekerSurvey', {
      extend: 'Ext.data.Model',
      fields: [
        { name: 'id', type: 'int' },
        { name: 'full_name', type: 'string', mapping: 'answers.first_name + " " + obj.answers.last_name' },
        'answers',
        { name: 'created', type: 'date', dateFormat: 'Y-m-d H:i:s' }
      ]
    });

    Ext.create('Ext.data.Store', {
      autoLoad: true,
      autoSync: true,
      id: 'surveyStore',
      idProperty: 'id',
      model: 'CareerSeekerSurvey',
      proxy: {
        type: 'ajax',
        api: {
          create:  '/admin/career_seekers_surveys/create',
          read:    '/admin/career_seekers_surveys/read',
          update:  '/admin/career_seekers_surveys/update',
          destroy: '/admin/career_seekers_surveys/destroy'
        },
        reader: {
          type: 'json',
          root: 'surveys'
        },
        writer: {
          type: 'json',
          encode: true,
          root: 'surveys',
          writeAllFields: false
        }
      }
    });
  },

  initGrid: function() {
    var gridToolbar = Ext.create('Ext.toolbar.Toolbar', {
      items: [{
        id: 'deletebtn',
        text: 'Delete Survey',
        icon: '/img/icons/delete.png',
        tooltip: 'Delete the selected survey',
        disabled: true,
        scope: this,
        handler: function() {
          this.deleteSurvey(this.selectedRecord);
        }
      }]
    });

    this.grid = Ext.create('Ext.grid.Panel', {
      store: Ext.data.StoreManager.lookup('surveyStore'),
      tbar: gridToolbar,
      width: '100%',
      height: 300,
      title: 'Career Seekers',
      columns: [
        {
          text: 'Career Seekers Name',
          dataIndex: 'full_name',
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
            var gridToolbars = this.grid.getToolbars();
            this.selectedRecord = rec;

            gridToolbars[0].items.items[0].enable();
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
        '<tpl for="answers">',
        '<div class="x-careerseeker-row">',
          '<p class="label left">First Name:</p>',
          '<p class="left">{first_name}</p>',
          '<br class="clear" />',
        '</div>',
        '<div class="x-careerseeker-row">',
          '<p class="label left">Last Name:</p>',
          '<p class="left">{last_name}</p>',
          '<br class="clear" />',
        '</div>',
        '<div class="x-careerseeker-row">',
          '<p class="label left">Date you worked with the Business Services Team or visited the Website:</p>',
          '<p class="left">',
          '<tpl for="date_you_worked_with_the_business_services_team_or_the_website">',
          '{month}/{day}/{year}',
          '</tpl>',
          '</p>',
          '<br class="clear" />',
        '</div>',
        '<div class="x-careerseeker-row">',
          '<p class="label left">Are your comments related to the Business Services Team or the website:</p>',
          '<p class="left">{are_your_comments_related_to_the_business_services_team_or_the_website}</p>',
          '<br class="clear" />',
        '</div>',
        '<div class="x-careerseeker-row">',
          '<p class="label left">Please enter the name(s) of staff (if any) who assisted you:</p>',
          '<p class="left">{names_of_staff_who_assisted_you}</p>',
          '<br class="clear" />',
        '</div>',
        '<div class="x-careerseeker-row">',
          '<p class="label left">Overall, how satisfied are you with the services you received from the office or website you selected above:</p>',
          '<p class="left">{overall_how_satisfied_are_you_with_the_services_you_received}</p>',
          '<br class="clear" />',
        '</div>',
        '<div class="x-careerseeker-row">',
          '<p class="label left">Think about what you expected from the office or website you visited. How well did the services you received meet your expectations:</p>',
          '<p class="left">{think_about_what_you_expected}</p>',
          '<br class="clear" />',
        '</div>',
        '<div class="x-careerseeker-row">',
          '<p class="label left">Think about the ideal services for other people in your circumstances. How well did the services you received from the office you visited or WorkNet Pinellas website compare to your ideal:</p>',
          '<p class="left">{think_about_the_ideal_services_for_other_people}</p>',
          '<br class="clear" />',
        '</div>',
        '<div class="x-careerseeker-row">',
          '<p class="label left">What programs are you currently participating in:</p>',
          '<p class="left">',
            '<ul>',
            '<tpl if="food_stamps == 1">',
              '<li>Food Stamps (FSET)</li>',
            '</tpl>',
            '<tpl if="unemployment_comp == 1">',
              '<li>Unemployment Compensation</li>',
            '</tpl>',
            '<tpl if="wia == 1">',
              '<li>WIA</li>',
            '</tpl>',
            '<tpl if="vocational_rehab == 1">',
              '<li>Vocational Rehabilitation</li>',
            '</tpl>',
            '<tpl if="welfare_transition == 1">',
              '<li>Veterans Services</li>',
            '</tpl>',
            '<tpl if="universal_services == 1">',
              '<li>Universal Services (Resource Room, Job Search)</li>',
            '</tpl>',
            '</ul>',
          '</p>',
          '<br class="clear" />',
        '</div>',
        '<div class="x-careerseeker-row">',
          '<p class="label left">How did you learn about us (please choose one):</p>',
          '<p class="left">{how_did_you_learn}</p>',
          '<br class="clear" />',
        '</div>',
        '<div class="x-careerseeker-row">',
          '<p class="label left">If you chose "Other" above, please elaborate, if possible:</p>',
          '<p class="left">{if_you_chose_Other}</p>',
          '<br class="clear" />',
        '</div>',
        '<div class="x-careerseeker-row">',
          '<p class="label left">Industry:</p>',
          '<p class="left">{industry}</p>',
          '<br class="clear" />',
        '</div>',
        '<div class="x-careerseeker-row">',
          '<p class="label left">Gender:</p>',
          '<p class="left">{gender}</p>',
          '<br class="clear" />',
        '</div>',
        '<div class="x-careerseeker-row">',
          '<p class="label left">Age:</p>',
          '<p class="left">{age}</p>',
          '<br class="clear" />',
        '</div>',
        '<div class="x-careerseeker-row">',
          '<p class="label left">Highest Level of Education Attained:</p>',
          '<p class="left">{highest_level_of_education_attained}</p>',
          '<br class="clear" />',
        '</div>',
        '<div class="x-careerseeker-row">',
          '<p class="label left">Please share any comments or suggestions you have regarding our Business Services or the website. Please enter your name, phone number and email address here, if you would like our staff to contact you:</p>',
          '<p class="left">{please_share_any_comments}</p>',
          '<br class="clear" />',
        '</div>',
        '</tpl>',
      '</div>'
    );

    if (!this.surveyWindow) {
      this.surveyWindow = new Ext.Window({
        title: 'Career Seeker Survey',
        height: 500,
        width: 650,
        closeAction: 'hide',
        resizable: false,
        bodyStyle: 'padding: 15px',
        autoScroll: true,
        tpl: windowTpl,
        data: rec.data
      });
    } else {
      windowTpl.overwrite(this.surveyWindow.body, rec.data);
    }
  },
  deleteSurvey: function(rec) {
    Ext.data.StoreManager.lookup('surveyStore').remove(rec);
    Ext.getCmp('deletebtn').disable();
    this.selectedRecord = null;
  },
  showWindow: function(rec) {
    this.initWindow(rec);
    this.surveyWindow.show();
  }
};

Ext.onReady(function() {
  CareerSeekers.init();
});
