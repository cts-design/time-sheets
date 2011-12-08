var locationId, parentId;

Ext.define('Atlas.form.SelfSignAlertPanel', {
	extend: 'Ext.form.Panel',
	alias: 'widget.selfsignalertformpanel',	
	padding: 10,
	border: 0,
	defaults: {
		labelWidth: 100
	},
	items: [{
		fieldLabel: 'Alert Name',
		xtype: 'textfield',
		allowBlank: false,
		msgTarget: 'under',
		name: 'name'		
	},{
		xtype: 'combobox',
		id: 'locationSelect',
		fieldLabel: 'Location',
		displayField: 'name',
		valueField: 'id',
		store: 'locations',			
		queryMode: 'remote',
		emptyText: 'Please Select',
		name: 'location',
		allowBlank: false,
		msgTarget: 'under',
		listeners: {
			select: function() {
				locationId = this.getValue();
				var level1Buttons = this.nextNode();
				level1Buttons.reset();
				level1Buttons.getStore().load();
				if(level1Buttons.isDisabled()) {
					level1Buttons.enable();	
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
				level2Buttons.reset();
				level2Buttons.getStore().load();
				level2Buttons.fireEvent('select');				
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
		listeners: {
			select: function() {
				parentId = this.getValue();
				console.log(parentId);
				var level3Buttons = this.nextNode();
				level3Buttons.reset();
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
		name: 'level3'
	},{
		fieldLabel: 'Also send email',
		xtype: 'checkbox',
		name: 'send_email'
	},{
		xtype: 'hiddenfield',
		name: 'user_id',
		value: 2		
	},{
		xtype: 'button',
		text: 'Save',
		formBind: true,
		handler: function() {
			var form = this.up('form').getForm();
			if(form.isValid()) {
				form.submit({
					success: function(form, action) {
						Ext.Msg.alert('Success', action.result.message);
						form.reset();
					},
					failure: function(form, action)	{
						Ext.Msg.alert('Failed', action.result.message);
					}
				});
			}
		}
	}]
});	

Ext.define('Location', {
	extend: 'Ext.data.Model',
	fields: ['id', 'name']
});

Ext.create('Ext.data.Store', {
	model: 'Location',
	id: 'locations',
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
			if(level2Buttons.isDisabled() && records[0] !== undefined) {
			 	level2Buttons.enable();	
			}
			if(!level2Buttons.isDisabled() && records[0] === undefined) {
				level2Buttons.disable();	
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
	id: 'alertTypes',
	fields: ['name', 'label'],
	data: [
		{'name': 'selfSign', 'label': 'Self Sign'},
		{'name': 'user', 'label': 'User'}
	]
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
	        flex: 1,
	        layout: 'fit',
	        items: [{
	        	xtype: 'selfsignalertformpanel',
	        	id: 'selfSignAlertPanel',
	        	url: '/admin/alerts/add_self_sign_alert'
	        }],
	        dockedItems: [{
	        	xtype: 'toolbar',
	        	dock: 'top',
	        	items: [{
	        		xtype: 'combobox',
	        		fieldLabel: 'Select Alert Type',
	        		store: 'alertTypes',
	        		displayField: 'label',
	        		valueField: 'name',
	        		emptyText: 'Please Select',
	        		value: 'Self Sign',
	        		listeners: {
	        			select: function() {
							if(this.getValue() == 'selfSign') {
								Ext.getCmp('selfSignAlertPanel').show();
							}						
	        			}
	        		}
	        	}]	        	
	        	
	        }]
	    },{
	        xtype: 'gridpanel',
	        flex: 2,
	        title: 'User Alerts',
	        columns: [
		        { header: 'Name',  dataIndex: 'name' },
		        { header: 'Type', dataIndex: 'type' },
		        { header: 'Send Email', dataIndex: 'send_email' },
		        { header: 'Disabled', dataIndex: 'disabled' }
    		]
	    }]
	});
});