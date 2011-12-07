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
		msgTarget: 'under'		
	},{
		xtype: 'combobox',
		id: 'locationSelect',
		fieldLabel: 'Location',
		displayField: 'name',
		valueField: 'id',
		store: 'locations',			
		queryMode: 'remote',
		emptyText: 'Please Select',
		name: 'locations',
		allowBlank: false,
		msgTarget: 'under',
		listeners: {
			change: function() {
			}
		}
	},{
		fieldLabel: 'Level 1 Buttons',
		xtype: 'combobox',
		disabled: true
	},{
		fieldLabel: 'Level 2 Buttons',
		xtype: 'combobox',
		disabled: true
	},{
		fieldLabel: 'Level 3 Buttons',
		xtype: 'combobox',
		disabled: true
	},{
		fieldLabel: 'Also send email',
		xtype: 'checkbox'
	},{
		xtype: 'button',
		text: 'Save'
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
		id: 'kioskButtonProxy',	
		url: '/admin/self_sign_logs/get_kiosk_buttons/',
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
				this.getProxy().url = '/admin/self_sign_logs/get_kiosk_buttons/'+kioskId+'/';
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
	        		listeners: {
	        			select: function() {
							this.up('panel').removeAll(true);
							if(this.getValue() == 'selfSign') {
								this.up('panel').add({xtype: 'selfsignalertformpanel'});
							}						
	        			}
	        		}
	        	}]	        	
	        	
	        }]
	    },
	    {
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