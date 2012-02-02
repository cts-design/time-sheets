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
				'employer_name',
				'street_address1',
				'street_address2',
				'city',
				'state',
				'zip',
				'phone_number',
				'name_of_employee',
				{ name: 'last_four_ssn_employee', type: 'int' },
				{ name: 'start_date', type: 'date', dateFormat: 'Y-m-d' },
				'job_title',
				'salary',
				'hours_per_week',
				{ name: 'benefits', type: 'int' },
				{ name: 'created', type: 'date', dateFormat: 'Y-m-d H:i:s' },
				{ name: 'modified', type: 'date', dateFormat: 'Y-m-d' }
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
          read:    '/admin/employer_verifications/read',
          destroy: '/admin/employer_verifications/destroy'
        },
        reader: {
          type: 'json',
          root: 'employer_verifications'
        },
        writer: {
          type: 'json',
          encode: true,
          root: 'employer_verifications',
          writeAllFields: false
        }
      }
    });
  },

  initGrid: function() {
    var gridToolbar = Ext.create('Ext.toolbar.Toolbar', {
      items: [{
        id: 'deletebtn',
        text: 'Delete Employer Verification',
        icon: '/img/icons/delete.png',
        tooltip: 'Delete the selected employer verification',
        disabled: true,
        scope: this,
        handler: function() {
          this.deleteSurvey(this.selectedRecord);
        },
				listeners: {
					itemclick: {
						fn: function(view, rec, item, index, e, opts) {
							var gridToolbars = this.grid.getDockedItem();
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
      }]
    });

    this.grid = Ext.create('Ext.grid.Panel', {
      store: Ext.data.StoreManager.lookup('jobOrderFormStore'),
      tbar: gridToolbar,
      width: '100%',
      height: 300,
      title: 'Employer Verifications',
      columns: [
        {
          text: 'Employer Name',
          dataIndex: 'employer_name',
          flex: 1
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
          '<p class="label left">Employer name:</p>',
          '<p class="left">{employer_name}</p>',
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
          '<p class="label left">Phone Number:</p>',
          '<p class="left">{phone_number}</p>',
          '<br class="clear" />',
        '</div>',
        '<div class="x-careerseeker-row">',
          '<p class="label left">Name of Employee:</p>',
          '<p class="left">{name_of_employee}</p>',
          '<br class="clear" />',
        '</div>',
        '<div class="x-careerseeker-row">',
          '<p class="label left">Employee Last 4 SSN:</p>',
          '<p class="left">{last_four_ssn_employee}</p>',
          '<br class="clear" />',
        '</div>',
        '<div class="x-careerseeker-row">',
          '<p class="label left">Start Date:</p>',
          '<p class="left">{start_date}</p>',
          '<br class="clear" />',
        '</div>',
        '<div class="x-careerseeker-row">',
          '<p class="label left">Job Title:</p>',
          '<p class="left">{job_title}</p>',
          '<br class="clear" />',
        '</div>',
        '<div class="x-careerseeker-row">',
          '<p class="label left">Salary:</p>',
          '<p class="left">{salary}</p>',
          '<br class="clear" />',
        '</div>',
        '<div class="x-careerseeker-row">',
          '<p class="label left">Hours Per Week:</p>',
          '<p class="left">{hours_per_week}</p>',
          '<br class="clear" />',
        '</div>',
        '<div class="x-careerseeker-row">',
          '<p class="label left">Benefits:</p>',
          '<tpl if="benefits == 1">',
            '<p class="left">Yes</p>',
          '</tpl>',
          '<tpl if="benefits == 0">',
            '<p class="left">No</p>',
          '</tpl>',
          '<br class="clear" />',
        '</div>',
        '</tpl>',
      '</div>'
    );

    if (!this.responseWindow) {
      this.responseWindow = new Ext.Window({
        title: 'Employer Verification',
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
    Ext.data.StoreManager.lookup('jobOrderFormStore').remove(rec);
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
