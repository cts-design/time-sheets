var locationId, parentId;

Ext.define('Atlas.form.field.AlertNameText', {
	extend: 'Ext.form.field.Text',
	alias: 'widget.alertnametextfield',
	fieldLabel: 'Alert Name',
	allowBlank: false,
	msgTarget: 'under',
	name: 'name'
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
	name: 'location',
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
		xtype: 'firstnamecombobox'
	},{
		xtype: 'ssncombobox'
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
		xtype: 'alertsavebutton',
		width: 100,
		handler: function() {
			var form = this.up('form').getForm();
			if(form.isValid()) {
				form.submit({
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
		xtype: 'alertsavebutton',
		width: 100
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
		xtype: 'firstnamecombobox'
	},{
		xtype: 'ssncombobox'
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
		emptyText: 'Select a specific location if nessesary'
	},{
		xtype: 'findcusbycombobox'
	},{
		xtype: 'lastnametextfield'
	},{
		xtype: 'firstnamecombobox'
	},{
		xtype: 'ssncombobox'
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
	fields: ['id', 'name', 'type', 'send_email', 'disabled']
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
						success: function(response){
							console.log(response.responseText.message);
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
				
	Ext.create('Ext.Panel', {
		width: 950,
		height: 400,
		title: "Alerts",
		layout: {
			type: 'hbox',
			align: 'stretch'
		},
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
					id: 'selfSignAlertFormPanel',
					url: '/admin/alerts/add_self_sign_alert'
				},{
					xtype: 'customerdetailsalertformpanel',
					id: 'customerDetailsAlertFromPanel',
					url: '/admin/alerts/add_customer_details_alert'
				},{
					xtype: 'queueddocumentalertformpanel',
					id: 'queuedDocumentAlertFormPanel',
					url: '/admin/alerts/add_queued_document_alert'
				},{
					xtype: 'selfscanalertformpanel',
					id: 'selfScanAlertFormPanel',
					url: '/admin/alerts/add_self_scan_alert'
				},{
					xtype: 'cusfileddocalertformpanel',
					id: 'cusFiledDocAlertFormPanel',
					url: '/admin/alerts/add_cus_filed_doc_alert'
				},{
					xtype: 'customerloginalertformpanel',
					id: 'customerLoginAlertFormPanel',
					url: '/admin/alerts/add_customer_login_alert'
				}],
				dockedItems: [{
					xtype: 'toolbar',
					dock: 'top',
					items: [{
						xtype: 'combobox',
						width: 300,
						fieldLabel: 'Select Alert Type',
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
});