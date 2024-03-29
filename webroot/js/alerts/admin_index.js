var locationId, parentId;

Ext.define('Atlas.form.field.AlertNameText', {
	extend: 'Ext.form.field.Text',
	alias: 'widget.alertnametextfield',
	fieldLabel: 'Alert Name',
	allowBlank: false,
	msgTarget: 'under',
	name: 'name'
});

Ext.define('Atlas.form.field.AdminComboBox', {
  extend: 'Ext.form.field.ComboBox',
  alias: 'widget.admincombobox',
  xtype: 'combobox',
  fieldLabel: 'Staff Member',
  displayField: 'name',
  valueField: 'id',
  store: 'admins',
  emptyText: 'Please Select',
  name: 'admin_id',
  allowBlank: false,
  msgTarget: 'under',
  queryMode: 'remote',
  queryParm: 'query'
});

Ext.define('Atlas.form.field.LocationComboBox', {
	extend: 'Ext.form.field.ComboBox',
	alias: 'widget.locationcombobox',
	xtype: 'combobox',
	fieldLabel: 'Location',
	displayField: 'name',
	valueField: 'id',
	store: 'locations',
	queryMode: 'remote',
	emptyText: 'Please Select',
	name: 'location_id',
	allowBlank: false,
	msgTarget: 'under'
});

Ext.define('Atlas.form.field.SendEmailCheckbox', {
	extend: 'Ext.form.field.Checkbox',
	alias: 'widget.sendemailcheckbox',
	fieldLabel: 'Also send email',
	name: 'send_email'
});

Ext.define('Atlas.form.field.LastNameText', {
	extend: 'Ext.form.field.Text',
	alias: 'widget.lastnametextfield',
	emptyText: 'Please enter customer last name',
	fieldLabel: 'Last Name',
	disabled: true,
	allowBlank: false,
	msgTarget: 'under',
	name: 'lastname'
});

Ext.define('Atlas.form.field.FirstNameComboBox', {
	extend: 'Ext.form.field.ComboBox',
	alias: 'widget.firstnamecombobox',
	fieldLabel: 'First Name',
	disabled: true,
	allowBlank: false,
	valueField: 'id',
	displayField: 'firstname',
	msgTarget: 'under',
	triggerAction: 'query',
	minChars: 2,
	emptyText: 'Please enter at least two letters of first name',
	name: 'firstname',
	store: 'customerFirstname',
	listConfig: {
		getInnerTpl: function() {
			return '<div>{fullname}</div>';
		}
	},
	listeners: {
		beforequery: function(queryEvent, eOpts) {
			queryEvent.query = this.prev().getValue() + ',' + queryEvent.query;
		}
	}
});

Ext.define('Atlas.form.field.SelfScanCategoryComboBox', {
	extend: 'Ext.form.field.ComboBox',
	alias: 'widget.selfscancategorycombobox',
	xtype: 'combobox',
	fieldLabel: 'Self Scan Category',
	displayField: 'name',
	valueField: 'id',
	store: 'selfScanCategories',
	queryMode: 'remote',
	emptyText: 'Please Select',
	name: 'self_scan_category_id',
	allowBlank: false,
	msgTarget: 'under'
});

Ext.define('SelfScanCategory', {
  extend: 'Ext.data.Model',
  fields: ['id', 'name', 'cat_1', 'cat_2', 'cat_3']
});

Ext.create('Ext.data.Store', {
	model: 'SelfScanCategory',
	storeId: 'selfScanCategories',
	proxy: {
		type: 'ajax',
		url: '/admin/self_scan_categories/get_cats',
		reader: {
			type: 'json',
			root: 'cats'
		},
		limitParam: undefined,
		pageParam: undefined,
		startParam: undefined
	}
});

Ext.define('Customer', {
	extend: 'Ext.data.Model',
	fields: ['id', 'firstname', 'lastname', 'fullname', 'ssn']
});

Ext.create('Ext.data.Store', {
	model: 'Customer',
	storeId: 'customerFirstname',
	proxy: {
		type: 'ajax',
		url: '/admin/users/get_customers_by_first_and_last_name',
		reader: {
			type: 'json',
			root: 'users'
		},
		limitParam: undefined,
		pageParam: undefined,
		startParam: undefined
	}
});

Ext.define('Admin', {
  extend: 'Ext.data.Model',
  fields: ['id', 'name']
});

Ext.create('Ext.data.Store', {
  model: 'Admin',
  storeId: 'admins',
  proxy: {
    type: 'ajax',
    url: '/admin/users/get_all_admins',
    reader: {
      type: 'json',
      root: 'admins'
    },
    limitParam: undefined,
    pageParam: undefined,
    startParam: undefined
  }
});


Ext.define('Atlas.form.field.SsnComboBox', {
	extend: 'Ext.form.field.ComboBox',
	alias: 'widget.ssncombobox',
	fieldLabel: 'Last 4 of SSN',
	disabled: true,
	allowBlank: false,
	emptyText: 'Please enter last 4 of customer ssn',
	msgTarget: 'under',
	name: 'ssn',
	triggerAction: 'query',
	store: 'customerSsn',
	valueField: 'id',
	displayField: 'ssn',
	listConfig: {
		getInnerTpl: function() {
			return '<div>{fullname}</div>';
		}
	}
});

Ext.create('Ext.data.Store', {
	model: 'Customer',
	storeId: 'customerSsn',
	proxy: {
		type: 'ajax',
		url: '/admin/users/get_customers_by_ssn',
		reader: {
			type: 'json',
			root: 'users'
		},
		limitParam: undefined,
		pageParam: undefined,
		startParam: undefined
	}
});

Ext.define('Atlas.form.field.FindCusByComboBox', {
	extend: 'Ext.form.field.ComboBox',
	alias: 'widget.findcusbycombobox',
	fieldLabel: 'Find customer by',
	store: 'findCusBy',
  name: 'find_cus_by',
	emptyText: 'Please Select',
	valueField: 'type',
	displayField: 'type',
	listeners: {
		change: function(combo, newValue, oldValue, eOpts) {
			var first = this.next(),
				last = first.next(),
				ssn = last.next();
			if(!newValue){
				first.disable();
				last.disable();
				ssn.disable();
			}
			if(newValue === 'Name') {
				first.enable();
				last.enable();
				ssn.disable();
			}
			if(newValue === 'Last 4 SSN') {
				first.disable();
				last.disable();
				ssn.enable();
			}
		}
	}
});

Ext.create('Ext.data.ArrayStore', {
	storeId: 'findCusBy',
	autoLoad: true,
	idIndex: 0,
	fields: ['type'],
	data: [
		['Name'],
		['Last 4 SSN']
	]
});

Ext.create('Ext.data.ArrayStore', {
	storeId: 'programType',
	autoLoad: true,
	idIndex: 0,
	fields: ['value', 'label'],
	data: [
		['registration', 'Registration'],
		['orientation', 'Orientation'],
    ['enrollment', 'Enrollment'],
    ['esign', 'E-Signature']
	]
});

Ext.create('Ext.data.ArrayStore', {
	storeId: 'programResponseStatuses',
	autoLoad: true,
	idIndex: 0,
	fields: ['status', 'label'],
	data: [
		['incomplete', 'Open'],
		['complete', 'Closed']
	]
});

Ext.define('Program', {
  extend: 'Ext.data.Model',
  fields: ['id', 'name', 'type', {name: 'approval_required', type: 'int'}]
});

Ext.create('Ext.data.Store', {
  model: 'Program',
  storeId: 'programs',
  proxy: {
    type: 'ajax',
    url: '/admin/programs/get_programs_by_type',
    reader: {
      type: 'json',
      root: 'programs'
    },
    limitParam: undefined,
    pageParam: undefined,
    startParam: undefined
  }
});

Ext.define('Atlas.button.AlertSaveButton', {
	extend: 'Ext.button.Button',
	alias: 'widget.alertsavebutton',
	xtype: 'button',
	text: 'Save',
	formBind: true,
	handler: function() {
		var form = this.up('form').getForm();
		if(form.isValid()){
			form.submit({
				success: function(form, action) {
					Ext.Msg.alert('Success', action.result.message);
					form.reset();
					Ext.getCmp('myAlertsGrid').getStore().load();
				},
				failure: function(form, action) {
					Ext.Msg.alert('Failed', action.result.message);
				}
			});
		}
	}
});

Ext.define('Atlas.button.AlertResetButton', {
	extend: 'Ext.button.Button',
	alias: 'widget.alertresetbutton',
	text: 'Reset',
	handler: function() {
		this.up('form').getForm().reset();
	}
});

Ext.define('Atlas.form.SelfScanAlertPanel', {
	extend: 'Ext.form.Panel',
	alias: 'widget.selfscanalertformpanel',
	padding: 10,
	border: 0,
	defaults: {
		labelWidth: 100,
		width: 375
	},
	items: [{
		xtype: 'alertnametextfield'
	},{
		xtype: 'findcusbycombobox'
	},{
		xtype: 'lastnametextfield'
	},{
		xtype: 'firstnamecombobox',
    id: 'selfScanFirstName'
	},{
		xtype: 'ssncombobox'
	},{
		xtype: 'sendemailcheckbox'
	},{
    xtype: 'hiddenfield',
    name: 'id'
  },{
		xtype: 'alertsavebutton',
		width: 100,
		handler: function() {
			var form = this.up('form').getForm();
      var vals = form.getValues();
      var url = '/admin/alerts/add_self_scan_alert';
      if (vals.id) {
        var url = '/admin/alerts/update_self_scan_alert';
      }
			if(form.isValid()) {
				form.submit({
          url: url,
					success: function(form, action) {
						Ext.Msg.alert('Success', action.result.message);
						form.reset();
						Ext.getCmp('myAlertsGrid').getStore().load();
					},
					failure: function(form, action)	{
						Ext.Msg.alert('Failed', action.result.message);
					}
				});
			}
		}
	},{
		xtype: 'alertresetbutton',
		width: 100,
		margin: '0 0 0 10'
	}]
});

Ext.define('Atlas.form.SelfScanCategoryAlertPanel', {
	extend: 'Ext.form.Panel',
	alias: 'widget.selfscancategoryalertformpanel',
	padding: 10,
	border: 0,
	defaults: {
		labelWidth: 100,
		width: 375
	},
	items: [{
		xtype: 'alertnametextfield'
	},{
    xtype: 'locationcombobox',
    allowBlank: true,
    id: 'selfScanLocation'
  },{
    xtype: 'selfscancategorycombobox',
    id: 'selfScanCategory'
	},{
		xtype: 'sendemailcheckbox'
  },{
    xtype: 'hiddenfield',
    name: 'id'
  },{
		xtype: 'alertsavebutton',
		width: 100,
		handler: function() {
			var form = this.up('form').getForm();
      var vals = form.getValues();
      var url = '/admin/alerts/add_self_scan_category_alert';
      if (vals.id) {
        var url = '/admin/alerts/update_self_scan_category_alert';
      }
			if(form.isValid()) {
				form.submit({
          url: url,
					success: function(form, action) {
						Ext.Msg.alert('Success', action.result.message);
						form.reset();
						Ext.getCmp('myAlertsGrid').getStore().load();
					},
					failure: function(form, action)	{
						Ext.Msg.alert('Failed', action.result.message);
					}
				});
			}
		}
	},{
		xtype: 'alertresetbutton',
		width: 100,
		margin: '0 0 0 10'
	}]
});



Ext.define('Atlas.form.SelfSignAlertPanel', {
	extend: 'Ext.form.Panel',
	alias: 'widget.selfsignalertformpanel',
	padding: 10,
	border: 0,
	defaults: {
		labelWidth: 100,
		width: 350
	},
	items: [{
    id: 'alertName',
		xtype: 'alertnametextfield'
	},{
		xtype: 'locationcombobox',
		id: 'locationSelect',
		listeners: {
			select: function() {
				locationId = this.getValue();
				var level1Buttons = this.nextNode(),
					level2Buttons = level1Buttons.nextNode(),
					level3Buttons = level2Buttons.nextNode();
				level1Buttons.getStore().load();
				if(!level2Buttons.isDisabled()) {
					level2Buttons.reset();
					level2Buttons.disable();
				}
				if(!level3Buttons.isDisabled()) {
					level3Buttons.reset();
					level3Buttons.disable();
				}
			}
		}
	},{
		fieldLabel: 'Level 1 Buttons',
		id: 'level1Buttons',
		xtype: 'combobox',
		disabled: true,
		store: 'level1ButtonsStore',
		valueField: 'id',
		emptyText: 'Please Select',
		allowBlank: false,
		displayField: 'name',
		queryMode: 'local',
		name: 'level1',
		listeners: {
			select: function() {
				parentId = this.getValue();
				var level2Buttons = this.nextNode();
				level2Buttons.getStore().load();
			}
		}
	},{
		fieldLabel: 'Level 2 Buttons',
		id: 'level2Buttons',
		xtype: 'combobox',
		disabled: true,
		store: 'level2ButtonsStore',
		valueField: 'id',
		emptyText: 'Please Select',
		displayField: 'name',
		queryMode: 'local',
		name: 'level2',
		allowBlank: true,
		listeners: {
			select: function() {
				parentId = this.getValue();
				var level3Buttons = this.nextNode();
				level3Buttons.getStore().load();
			}
		}
	},{
		fieldLabel: 'Level 3 Buttons',
		id: 'level3Buttons',
		xtype: 'combobox',
		disabled: true,
		store: 'level3ButtonsStore',
		valueField: 'id',
		emptyText: 'Please Select',
		displayField: 'name',
		queryMode: 'local',
		name: 'level3',
		allowBlank: true
	},{
		xtype: 'sendemailcheckbox'
	},{
    xtype: 'hiddenfield',
    name: 'id',
    value: null
  },{
		xtype: 'alertsavebutton',
		width: 100,
		handler: function() {
			var form = this.up('form').getForm();
      var vals = form.getValues();
      var url = '/admin/alerts/add_self_sign_alert';
      if (vals.id) {
        var url = '/admin/alerts/update_self_sign_alert';
      }
			if(form.isValid()) {
				form.submit({
          url: url,
					success: function(form, action) {
						Ext.Msg.alert('Success', action.result.message);
						form.reset();
						disableAndResetButtons(['1', '2', '3']);
						Ext.getCmp('myAlertsGrid').getStore().load();
					},
					failure: function(form, action)	{
						Ext.Msg.alert('Failed', action.result.message);
					}
				});
			}
		}
	},{
		xtype: 'alertresetbutton',
		width: 100,
		margin: '0 0 0 10',
		handler: function() {
			this.up('form').getForm().reset();
			disableAndResetButtons(['1', '2', '3']);
		}
	}]
});

Ext.define('Atlas.form.CustomerDetailsAlertPanel', {
	extend: 'Ext.form.Panel',
	alias: 'widget.customerdetailsalertformpanel',
	padding: 10,
	border: 0,
	defaults: {
		labelWidth: 100,
		width: 350
	},
	items: [{
		xtype: 'alertnametextfield'
	},{
		xtype: 'locationcombobox',
		allowBlank: true,
		emptyText: 'Select a specific location if nessesary'
	},{
		xtype: 'combobox',
		id: 'detailsSelect',
		fieldLabel: 'Detail',
		displayField: 'label',
		valueField: 'detail',
		store: 'details',
		queryMode: 'local',
		emptyText: 'Please Select',
		name: 'detail',
		allowBlank: false,
		msgTarget: 'under'
	},{
		xtype: 'sendemailcheckbox'
	},{
    xtype: 'hiddenfield',
    name: 'id'
  },{
		xtype: 'alertsavebutton',
		width: 100,
		handler: function() {
			var form = this.up('form').getForm();
      var vals = form.getValues();
      var url = '/admin/alerts/add_customer_details_alert';
      if (vals.id) {
        var url = '/admin/alerts/update_customer_details_alert';
      }
			if(form.isValid()) {
				form.submit({
          url: url,
					success: function(form, action) {
						Ext.Msg.alert('Success', action.result.message);
						form.reset();
						Ext.getCmp('myAlertsGrid').getStore().load();
					},
					failure: function(form, action)	{
						Ext.Msg.alert('Failed', action.result.message);
					}
				});
			}
		}
	}, {
		xtype: 'alertresetbutton',
		width: 100,
		margin: '0 0 0 10'
	}]
});

Ext.define('Atlas.form.QueuedDocumentAlertPanel', {
	extend: 'Ext.form.Panel',
	alias: 'widget.queueddocumentalertformpanel',
	padding: 10,
	border: 0,
	defaults: {
		labelWidth: 100,
		width: 350
	},
	items: [{
		xtype: 'alertnametextfield'
	},{
		xtype: 'locationcombobox'
	},{
		xtype: 'combobox',
		fieldLabel: 'Queued Doc Cat:',
		queryMode: 'remote',
		valueField: 'id',
		labelField: 'name',
		store: 'queueCats',
		name: 'queue_cat'
	},{
		xtype: 'sendemailcheckbox'
	},{
		xtype: 'alertsavebutton',
		width: 100
	},{
		xtype: 'alertresetbutton',
		width: 100,
		margin: '0 0 0 10'
	}]
});

Ext.define('Atlas.form.CusFiledDocAlertPanel', {
	extend: 'Ext.form.Panel',
	alias: 'widget.cusfileddocalertformpanel',
	padding: 10,
	border: 0,
	defaults: {
		labelWidth: 100,
		width: 375
	},
	items: [{
		xtype: 'alertnametextfield'
	},{
		xtype: 'findcusbycombobox'
	},{
		xtype: 'lastnametextfield'
	},{
		xtype: 'firstnamecombobox',
    id: 'cusFiledDocFirstName'
	},{
		xtype: 'ssncombobox'
	},{
		xtype: 'sendemailcheckbox'
	},{
    xtype: 'hiddenfield',
    name: 'id'
  },{
		xtype: 'alertsavebutton',
		width: 100,
		handler: function() {
			var form = this.up('form').getForm();
      var vals = form.getValues();
      var url = '/admin/alerts/add_cus_filed_doc_alert';
      if (vals.id) {
        var url = '/admin/alerts/update_cus_filed_doc_alert';
      }
			if(form.isValid()) {
				form.submit({
          url: url,
					success: function(form, action) {
						Ext.Msg.alert('Success', action.result.message);
						form.reset();
						Ext.getCmp('myAlertsGrid').getStore().load();
					},
					failure: function(form, action)	{
						Ext.Msg.alert('Failed', action.result.message);
					}
				});
			}
		}
	},{
		xtype: 'alertresetbutton',
		width: 100,
		margin: '0 0 0 10'
	}]
});


Ext.define('Atlas.form.CustomerLoginAlertPanel', {
	extend: 'Ext.form.Panel',
	alias: 'widget.customerloginalertformpanel',
	padding: 10,
	border: 0,
	defaults: {
		labelWidth: 100,
		width: 375
	},
	items: [{
		xtype: 'alertnametextfield'
	},{
		xtype: 'locationcombobox',
		allowBlank: true,
		emptyText: 'Select a specific location if nessesary',
    id: 'cusLoginLocation'
	},{
		xtype: 'findcusbycombobox'
	},{
		xtype: 'lastnametextfield'
	},{
		xtype: 'firstnamecombobox',
    id: 'cusLoginFirstName'
	},{
		xtype: 'ssncombobox'
	},{
		xtype: 'sendemailcheckbox'
	},{
    xtype: 'hiddenfield',
    name: 'id'
  },{
		xtype: 'alertsavebutton',
		width: 100,
		handler: function() {
			var form = this.up('form').getForm();
      var vals = form.getValues();
      var url = '/admin/alerts/add_customer_login_alert';
      if (vals.id) {
        var url = '/admin/alerts/update_customer_login_alert';
      }
			if(form.isValid()) {
				form.submit({
          url: url,
					success: function(form, action) {
						Ext.Msg.alert('Success', action.result.message);
						form.reset();
						Ext.getCmp('myAlertsGrid').getStore().load();
					},
					failure: function(form, action)	{
						Ext.Msg.alert('Failed', action.result.message);
					}
				});
			}
		}
	},{
		xtype: 'alertresetbutton',
		width: 100,
		margin: '0 0 0 10'
	}]
});

Ext.define('Atlas.form.StaffFiledDocumentAlertPanel', {
	extend: 'Ext.form.Panel',
	alias: 'widget.stafffileddocumentalertformpanel',
	padding: 10,
	border: 0,
	defaults: {
		labelWidth: 100,
		width: 375
	},
	items: [{
		xtype: 'alertnametextfield'
	},{
    xtype: 'admincombobox',
    id: 'staffFiledAdminSelect'
  },{
		xtype: 'sendemailcheckbox'
	},{
    xtype: 'hiddenfield',
    name: 'id'
  },{
		xtype: 'alertsavebutton',
		width: 100,
		handler: function() {
			var form = this.up('form').getForm();
      var vals = form.getValues();
      var url = '/admin/alerts/add_staff_filed_document_alert';
      if (vals.id) {
        var url = '/admin/alerts/update_staff_filed_document_alert';
      }
			if(form.isValid()) {
				form.submit({
          url: url,
					success: function(form, action) {
						Ext.Msg.alert('Success', action.result.message);
						form.reset();
						Ext.getCmp('myAlertsGrid').getStore().load();
					},
					failure: function(form, action)	{
						Ext.Msg.alert('Failed', action.result.message);
					}
				});
			}
		}
	},{
		xtype: 'alertresetbutton',
		width: 100,
		margin: '0 0 0 10'
	}]
});

Ext.define('Atlas.form.ProgramResponseStatusAlertPanel', {
	extend: 'Ext.form.Panel',
	alias: 'widget.programresponsestatusalertformpanel',
	padding: 10,
	border: 0,
	defaults: {
		labelWidth: 100,
		width: 375
	},
	items: [{
		xtype: 'alertnametextfield'
	},{
		xtype: 'combobox',
		fieldLabel: 'Program Type',
		displayField: 'label',
		valueField: 'value',
    store: 'programType',
		queryMode: 'local',
		emptyText: 'Please Select',
		name: 'program_type',
		allowBlank: false,
		msgTarget: 'under',
    listeners: {
      change: function() {
        this.nextSibling().reset();
        this.nextSibling().nextSibling().reset();
        this.nextSibling().getStore().load({ params:{ type: this.getValue() } });
        this.nextSibling().enable();
      }
    }
  },{
		xtype: 'combobox',
		fieldLabel: 'Program',
    disabled: true,
		displayField: 'name',
		valueField: 'id',
    store: 'programs',
		queryMode: 'local',
		emptyText: 'Please Select',
		name: 'program_id',
		allowBlank: false,
		msgTarget: 'under',
    listeners: {
      select: function(combo, records, eOpts) {
        this.nextSibling().reset();
        if(records[0].data.approval_required) {
          if(!this.nextSibling().getStore().findRecord('label', 'Pending Approval')) {
            this.nextSibling().getStore().add({label: 'Pending Approval', status: 'pending_approval'});
          }
        }
        else {
          this.nextSibling().getStore().load();
        }
        this.nextSibling().enable();
      }
    }
  },{
		xtype: 'combobox',
		fieldLabel: 'Respone Status',
    id: 'programResponseStatus',
    disabled: true,
		displayField: 'label',
		valueField: 'status',
    store: 'programResponseStatuses',
		queryMode: 'local',
		emptyText: 'Please Select',
		name: 'response_status',
		allowBlank: false,
		msgTarget: 'under'
  },{
		xtype: 'sendemailcheckbox'
	},{
    xtype: 'hiddenfield',
    name: 'id'
  },{
		xtype: 'alertsavebutton',
		width: 100,
		handler: function() {
			var form = this.up('form').getForm();
      var vals = form.getValues();
      var url = '/admin/alerts/add_program_response_status_alert';
      if (vals.id) {
        var url = '/admin/alerts/update_program_response_status_alert';
      }
			if(form.isValid()) {
				form.submit({
          url: url,
					success: function(form, action) {
						Ext.Msg.alert('Success', action.result.message);
						form.reset();
						Ext.getCmp('myAlertsGrid').getStore().load();
					},
					failure: function(form, action)	{
						Ext.Msg.alert('Failed', action.result.message);
					}
				});
			}
		}
	},{
		xtype: 'alertresetbutton',
		width: 100,
		margin: '0 0 0 10'
	}]
});



Ext.define('DocumentQueueCategory', {
	extend: 'Ext.data.Model',
	fields: ['id', 'name']
});

Ext.create('Ext.data.Store', {
	model: 'DocumentQueueCategory',
	storeId: 'queueCats',
	proxy: {
		type: 'ajax',
		url: '/admin/document_queue_categories/get_cats',
		reader: {
			type: 'json',
			root: 'cats'
		},
		limitParam: undefined,
		pageParam: undefined,
		startParam: undefined
	}
});



Ext.create('Ext.data.ArrayStore', {
	storeId: 'details',
	autoLoad: true,
	idIndex: 0,
	fields: ['label', 'detail'],
	data: [
		['Veteran', 'veteran'],
		['Spanish', 'spanish']
	]
});

Ext.define('Location', {
	extend: 'Ext.data.Model',
	fields: ['id', 'name']
});

Ext.create('Ext.data.Store', {
	model: 'Location',
	storeId: 'locations',
	proxy: {
		type: 'ajax',
		url: '/admin/locations/get_location_list',
		reader: {
			type: 'json',
			root: 'locations'
		},
		limitParam: undefined,
		pageParam: undefined,
		startParam: undefined
	}
});

Ext.define('KioskButton', {
	extend: 'Ext.data.Model',
	fields:['id', 'name']
});

var kioskButtonProxy = Ext.create('Ext.data.proxy.Ajax', {
	url: '',
	reader: {
		type: 'json',
		root: 'buttons'
	},
	limitParam: undefined,
	pageParam: undefined,
	startParam: undefined
});

Ext.define('Atlas.data.KioskButtonStore', {
	extend: 'Ext.data.Store',
	model: 'KioskButton',
	autoDestroy: true,
	proxy: kioskButtonProxy
});

Ext.create('Atlas.data.KioskButtonStore', {
	storeId: 'level1ButtonsStore',
	listeners: {
		beforeload: function() {
			this.getProxy().url =
				'/admin/kiosks/get_kiosk_buttons_by_location/'+locationId+'/';
		},
		load: function(store, records, successful, operation, eOpts) {
			var level1Buttons = Ext.getCmp('level1Buttons');
			level1Buttons.reset();
			if(level1Buttons.isDisabled() && records[0] !== undefined) {
				level1Buttons.enable();
			}
			if(!level1Buttons.isDisabled() && records[0] === undefined) {
				level1Buttons.disable();
			}
		}
	}
});

Ext.create('Atlas.data.KioskButtonStore', {
	storeId: 'level2ButtonsStore',
	listeners: {
		beforeload: function() {
			this.getProxy().url =
				'/admin/kiosks/get_kiosk_buttons_by_location/'+locationId+'/'+parentId;
		},
		load: function(store, records, successful, operation, eOpts) {
			var level2Buttons = Ext.getCmp('level2Buttons');
			level2Buttons.reset();
			if(level2Buttons.isDisabled() && records[0] !== undefined) {
				level2Buttons.enable();
			}
			if(!level2Buttons.isDisabled() && records[0] === undefined) {
				level2Buttons.disable();
				disableAndResetButtons(['3']);
			}
		}
	}
});

Ext.create('Atlas.data.KioskButtonStore', {
	storeId: 'level3ButtonsStore',
	listeners: {
		beforeload: function() {
			this.getProxy().url =
				'/admin/kiosks/get_kiosk_buttons_by_location/'+locationId+'/'+parentId;
		},
		load: function(store, records, successful, operation, eOpts) {
			var level3Buttons = Ext.getCmp('level3Buttons');
			level3Buttons.reset();
			if(level3Buttons.isDisabled() && records[0] !== undefined) {
				level3Buttons.enable();
			}
			if(!level3Buttons.isDisabled() && records[0] === undefined) {
				level3Buttons.disable();
			}
		}
	}
});
	
Ext.create('Ext.data.Store', {
	storeId: 'alertTypes',
	fields: ['id', 'label'],
	proxy: {
		type: 'ajax',
		url: '/admin/alerts/get_alert_types',
		reader: {
			type: 'json',
			root: 'types'
		}
	}
});

function disableAndResetButtons(level) {
	for(var i in level) {
		var buttons = Ext.getCmp('level' + level[i] + 'Buttons');
		buttons.reset();
		buttons.disable();
	}
}


Ext.define('Alert', {
	extend: 'Ext.data.Model',
	fields: ['id', 'name', 'type', 'send_email', 'disabled', 'location_id', 'user_id', 'watched_id', 'detail']
});

Ext.create('Ext.data.Store', {
	storeId: 'alerts',
	model: 'Alert',
	proxy: {
		type: 'ajax',
		url: '/admin/alerts/index',
		reader: {
			type: 'json',
			root: 'alerts'
		},
		limitParam: undefined,
		pageParam: undefined,
		startParam: undefined
	},
	autoLoad: true
});

Ext.create('Ext.menu.Menu', {
	title: 'Actions',
	id: 'contextMenu',
	items: [{
		text: 'Send Email',
		id: 'sendEmail',
		xtype: 'menucheckitem',
		handler: function() {
			var selected = Ext.getCmp('myAlertsGrid').getSelectionModel().getLastSelected();
			Ext.getCmp('contextMenu').hide();
			Ext.Ajax.request({
				url: '/admin/alerts/toggle_email',
				params: {
					id: selected.data.id,
					send_email: this.checked
				},
				success: function(response){
					Ext.getCmp('myAlertsGrid').getStore().load();
				},
				failure: function() {
					Ext.Msg.alert('Error', 'An erorr has occured.');
				}
			});
		}
	},{
		text: 'Disabled',
		xtype: 'menucheckitem',
		handler: function() {
			var selected = Ext.getCmp('myAlertsGrid').getSelectionModel().getLastSelected();
			Ext.getCmp('contextMenu').hide();
			Ext.Ajax.request({
			url: '/admin/alerts/toggle_disabled',
			params: {
				id: selected.data.id,
				disabled: this.checked
			},
			success: function(response){
				Ext.getCmp('myAlertsGrid').getStore().load();
			},
			failure: function() {
				Ext.Msg.alert('Error', 'An erorr has occured.');
			}
			});
		}
	},{
		text: 'Delete',
		icon: '/img/icons/delete.png',
		handler: function() {
			Ext.Msg.confirm('Confirm Delete', 'Are you sure you want to delete this alert', function(button){
				if(button === 'yes') {
					var selected = Ext.getCmp('myAlertsGrid').getSelectionModel().getLastSelected();
					Ext.getCmp('contextMenu').hide();
					Ext.Ajax.request({
						url: '/admin/alerts/delete',
						params: {
							id: selected.data.id
						},
						success: function(response) {
							var responseText = Ext.JSON.decode(response.responseText);
							Ext.Msg.alert('Success', responseText.message);
							Ext.getCmp('myAlertsGrid').getStore().load();
						},
						failure: function(response) {
							var responseText = Ext.JSON.decode(response.responseText);
							Ext.Msg.alert('Success', responseText.message);
							Ext.Msg.alert('Error', response.responseText.message);
						}
					});
				}
			});
		}
	}]
});

Ext.onReady(function(){
  Ext.QuickTips.init();
				
	Ext.create('Ext.Panel', {
		width: 950,
		height: 400,
		title: "Alerts",
		layout: {
			type: 'hbox',
			align: 'stretch'
		},
    tools: [{
      type: 'save',
      tooltip: 'Download Alerts Client',
      handler: function() {
        window.location = '/admin/alerts/download'
      }
    }],
		renderTo: 'alerts',
		items: [{
			xtype: 'panel',
			flex: 1.5,
			layout: 'card',
			activeItem: 0,
			items: [{
				html: [
					'<p>Please select an alert type to add from the dropdown above. ',
					'If no alert types appear in the dropdown menu, please have your ',
					'supervisor check your permissions.</p>'
				],
				padding: 10,
				border: 0
				},{
					xtype: 'selfsignalertformpanel',
					id: 'selfSignAlertFormPanel'
				},{
					xtype: 'customerdetailsalertformpanel',
					id: 'customerDetailsAlertFromPanel',
				},{
					xtype: 'queueddocumentalertformpanel',
					id: 'queuedDocumentAlertFormPanel',
				},{
					xtype: 'selfscanalertformpanel',
					id: 'selfScanAlertFormPanel',
				},{
					xtype: 'selfscancategoryalertformpanel',
					id: 'selfScanCategoryAlertFormPanel',
				},{
					xtype: 'cusfileddocalertformpanel',
					id: 'cusFiledDocAlertFormPanel',
				},{
					xtype: 'customerloginalertformpanel',
					id: 'customerLoginAlertFormPanel',
				},{
          xtype: 'stafffileddocumentalertformpanel',
          id: 'staffFiledDocumentAlertFormPanel',
        },{
          xtype: 'programresponsestatusalertformpanel',
          id: 'programResponseStatusAlertFormPanel',
        }],
				dockedItems: [{
					xtype: 'toolbar',
					dock: 'top',
					items: [{
						xtype: 'combobox',
						width: 300,
						fieldLabel: 'Select Alert Type',
            id: 'alertType',
						store: 'alertTypes',
						displayField: 'label',
						valueField: 'id',
						emptyText: 'Please Select',
						listeners: {
						select: function() {
							this.up('panel').getLayout().setActiveItem(this.getValue());
						}
					}
				}]
			}]
			},{
			xtype: 'gridpanel',
			flex: 2,
			title: 'My Alerts',
			id: 'myAlertsGrid',
			viewConfig: {
				loadMask: true,
				singleSelect: true,
				emptyText: 'No records at this time.',
				listeners: {
					itemcontextmenu: function(view, rec, node, index, e) {
						e.stopEvent();
						var cm = Ext.getCmp('contextMenu');
						cm.items.items[0].setChecked(Boolean(rec.data.send_email));
						cm.items.items[1].setChecked(Boolean(rec.data.disabled));
						cm.showAt(e.getXY());
						return false;
					},
          itemdblclick: function(grid, record, item, index, e, eOpts) {
            var alertType = Ext.getCmp('alertType');
            var store = alertType.getStore();
            store.load({
              callback: function() {
                var val = store.findRecord('label', record.data.type);
                alertType.select(val.data.id, true);
                alertType.fireEvent('select');
                switch(record.data.type) {
                  case 'Self Sign':
                    editSelfSignAlert(record);
                    break;
                  case 'Customer Details':
                    editCustomerDetailsAlert(record);
                    break;
                  case 'Self Scan':
                    editSelfScanAlert(record);
                    break;
                  case 'Self Scan Category':
                    editSelfScanCategoryAlert(record);
                    break;
                  case 'Customer Filed Document':
                    editCusFiledDocAlert(record);
                    break;
                  case 'Customer Login':
                    editCustomerLoginAlert(record);
                    break;
                  case 'Staff Filed Document':
                    editStaffFiledDocumentAlert(record);
                    break;
                  case 'Program Response Status':
                    editProgramResponseStatusAlert(record);
                    break;
                }
              }
            });
          }
				}
				},
				columns: [{
					text: 'Id',
					dataIndex: 'id',
					hidden: true
				},
				{
					text: 'Name',
					dataIndex: 'name',
					flex: 1
				},
				{
					text: 'Type',
					dataIndex: 'type',
					width: 150
				},
				{
					text: 'Send Email',
					dataIndex: 'send_email',
					xtype: 'booleancolumn',
					trueText: 'Yes',
					falseText: 'No',
					width: 70
				},
				{
					text: 'Disabled',
					dataIndex: 'disabled',
					xtype: 'booleancolumn',
					trueText: 'Yes',
					falseText: 'No',
					width: 60
				}],
			store: 'alerts'
		}]
	});

  var editSelfSignAlert = function(record) {
    var text = null;
    Ext.Ajax.request({
      url: '/admin/master_kiosk_buttons/get_button_path/'+record.data.watched_id,
      success: function(response) {
        text = Ext.JSON.decode(response.responseText);
        populateForm(text);
      }
      
    });
    function populateForm(text) {
      var formPanel = Ext.getCmp('selfSignAlertFormPanel');
      formPanel.getForm().reset();
      var locationSelect = Ext.getCmp('locationSelect');
      var store = locationSelect.getStore();
      var buttons1 = Ext.getCmp('level1Buttons');
      var buttons2 = Ext.getCmp('level2Buttons');
      var buttons3 = Ext.getCmp('level3Buttons');
      disableAndResetButtons(['1', '2', '3']);
      formPanel.loadRecord(record);
      locationId = record.data.location_id;
      store.load({
        callback: function() {
          locationSelect.select(record.data.location_id);
          var button1store = buttons1.getStore();
          button1store.load({
            callback: function() {
              buttons1.enable();
              buttons1.setValue(text.buttons[0].id);
              parentId = text.buttons[0].id;
              if(text.buttons.length > 1) {
                var button2store = buttons2.getStore(); 
                button2store.load({
                  callback: function() {
                    buttons2.enable();
                    buttons2.select(text.buttons[1].id);
                    parentId = text.buttons[1].id;
                    if(text.buttons.length > 2) {
                      button3store = buttons3.getStore();
                      button3store.load({
                        callback: function() {
                          buttons3.enable();
                          buttons2.select(text.buttons[2].id);
                        }
                      });
                    }
                  }
                });
              }
            }
          });
        }
      });
    }
  };

  var editCustomerDetailsAlert = function(record) {
    var formPanel = Ext.getCmp('customerDetailsAlertFromPanel');
    var locationSelect = Ext.getCmp('locationSelect');
    var store = locationSelect.getStore();
    store.load({
      callback: function() {
        formPanel.loadRecord(record);
      }
    });
  };

  var editSelfScanAlert = function(record) {
    var formPanel = Ext.getCmp('selfScanAlertFormPanel');
    record.data.find_cus_by = 'Name';

    Ext.Ajax.request({
      url: '/admin/users/get_customer_by_id/'+record.data.watched_id,
      success: function(response) {
        text = Ext.JSON.decode(response.responseText);
        populateForm(text);
      }
    });

    function populateForm(text) {
      record.data.lastname = text.user.lastname;
      record.data.firstname = text.user.firstname;
      formPanel.loadRecord(record);
      firstNameCombo = Ext.getCmp('selfScanFirstName');
      firstNameCombo.setValue(text.user.id);
      firstNameCombo.doQuery();
      firstNameCombo.select(text.user.id);
      firstNameCombo.collapse();
    }
  };

  var editCusFiledDocAlert = function(record) {
    var formPanel = Ext.getCmp('cusFiledDocAlertFormPanel');
    record.data.find_cus_by = 'Name';

    Ext.Ajax.request({
      url: '/admin/users/get_customer_by_id/'+record.data.watched_id,
      success: function(response) {
        text = Ext.JSON.decode(response.responseText);
        populateForm(text);
      }
    });

    function populateForm(text) {
      record.data.lastname = text.user.lastname;
      record.data.firstname = text.user.firstname;
      formPanel.loadRecord(record);
      firstNameCombo = Ext.getCmp('cusFiledDocFirstName');
      firstNameCombo.setValue(text.user.id);
      firstNameCombo.doQuery();
      firstNameCombo.select(text.user.id);
      firstNameCombo.collapse();
    }
  };

  var editCustomerLoginAlert = function(record) {
    var formPanel = Ext.getCmp('customerLoginAlertFormPanel');
    record.data.find_cus_by = 'Name';

    Ext.Ajax.request({
      url: '/admin/users/get_customer_by_id/'+record.data.watched_id,
      success: function(response) {
        text = Ext.JSON.decode(response.responseText);
        populateForm(text);
      }
    });

    function populateForm(text) {
    var locationSelect = Ext.getCmp('cusLoginLocation');
    var store = locationSelect.getStore();
    store.load({
      callback: function() {
        record.data.lastname = text.user.lastname;
        record.data.firstname = text.user.firstname;
        formPanel.loadRecord(record);
        firstNameCombo = Ext.getCmp('cusLoginFirstName');
        firstNameCombo.setValue(text.user.id);
        firstNameCombo.doQuery();
        firstNameCombo.select(text.user.id);
        firstNameCombo.collapse();
      }
    });
    }
  };

  var editStaffFiledDocumentAlert = function(record) {
    var formPanel = Ext.getCmp('staffFiledDocumentAlertFormPanel'),
      adminSelect = Ext.getCmp('staffFiledAdminSelect');

    adminSelect.getStore().load({
      callback: function() {
        record.data.admin_id = record.data.watched_id
        formPanel.loadRecord(record);
      }
    });
  };

  var editSelfScanCategoryAlert = function(record) {
    var formPanel = Ext.getCmp('selfScanCategoryAlertFormPanel'),
        locationSelect = Ext.getCmp('selfScanLocation'),
        categorySelect = Ext.getCmp('selfScanCategory');
    locationSelect.getStore().load({
      callback: function() {
        categorySelect.getStore().load({
          callback: function() {
            record.data.self_scan_category_id = record.data.watched_id;
            formPanel.loadRecord(record);
          }
        })
      }
    })
  }

  var editProgramResponseStatusAlert = function(record) {
    var formPanel = Ext.getCmp('programResponseStatusAlertFormPanel');
    Ext.Ajax.request({
      url: '/admin/programs/get_program_by_id/'+record.data.watched_id,
      success: function(response) {
        text = Ext.JSON.decode(response.responseText);
        populateForm(text);
      }
      
    });
    function populateForm(text) {
      var status = Ext.getCmp('programResponseStatus');
      record.data.program_type = text.program.type;
      record.data.program_id = text.program.id;
      status.enable();
      if(record.data.detail === 'pending_approval') {
        if(!status.getStore().findRecord('label', 'Pending Approval')) {
          status.getStore().add({label: 'Pending Approval', status: 'pending_approval'});
        }
      }
      record.data.response_status = record.data.detail;
      formPanel.loadRecord(record);
    }

  };
});

