var locationId, parentId;

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
				var level1Buttons = this.nextNode()
				,   level2Buttons = level1Buttons.nextNode()
				,   level3Buttons = level2Buttons.nextNode();
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
		name: 'level3'
	},{
		fieldLabel: 'Also send email',
		xtype: 'checkbox',
		name: 'send_email'
	},{
		xtype: 'button',
		text: 'Save',
		width: 100,
		formBind: true,
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
		xtype: 'button',
		width: 100,
		text: 'Reset',
		margin: '0 0 0 10',
		handler: function() {
			this.up('form').getForm().reset();
			disableAndResetButtons(['1', '2', '3']);
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
	id: 'alertTypes',
	fields: ['name', 'label'],
	data: [
		{'name': 'selfSignAlertFormPanel', 'label': 'Self Sign'},
		{'name': 'userAlertFormPanel', 'label': 'User'}
	]
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
	id: 'alerts',
	model: 'Alert',
	proxy: {
		type: 'ajax',
		url: '/admin/alerts/index',
		reader: {
			type: 'json',
			root: 'alerts'
		}		
	},
	autoLoad: true
});	

Ext.create('Ext.menu.Menu', {
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
	        	xtype: 'selfsignalertformpanel',
	        	id: 'selfSignAlertFormPanel',
	        	url: '/admin/alerts/add_self_sign_alert'
	        },{
	        	xtype: 'panel',
	        	padding: 10,
	        	border: 0,
	        	html: 'User Form Here.....',
	        	id: 'userAlertFormPanel'
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
	        columns: [
	        	{ text: 'Id', dataIndex: 'id', hidden: true},
		        { text: 'Name',  dataIndex: 'name', flex: 1 },
		        { text: 'Type', dataIndex: 'type' },
		        { text: 'Send Email',
		          dataIndex: 'send_email',
		          xtype: 'booleancolumn',
		          trueText: 'Yes',
            	  falseText: 'No' 
		        },
		        { text: 'Disabled',
		          dataIndex: 'disabled', 
		          xtype: 'booleancolumn',
		          trueText: 'Yes',
            	  falseText: 'No' 
		       }
    		],
    		store: 'alerts'
	    }]
	});
});