/*
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2011
 * @link http://ctsfla.com
 * @package Atlas V3
 */

// Kludge to fix not being able to type spaces in context menu text fields 
Ext.override(Ext.menu.KeyNav, {
    constructor: function(menu) {
        var me = this;
        me.menu = menu;
        me.callParent([menu.el, {
            down: me.down,
            enter: me.enter,
            esc: me.escape,
            left: me.left,
            right: me.right,
            //space: me.enter,
            tab: me.tab,
            up: me.up
        }]);
    }
});


Ext.onReady( function() {

	var rowIndex = null,
		recordId = null,
		userId = null,
		kioskId = '',
		buttonParentId = '',
		locationId = null,
		locations = [],
		services = [];
		
	Ext.QuickTips.init();
	Ext.define('SelfSignLog', {
		extend: 'Ext.data.Model',
		fields: [
			'id', 'status', 'firstname', 'lastname', 'last4', 'admin', 
			{name: 'created', type: 'date', dateFormat: 'Y-m-d H:i:s'}, 
			'location', 'service', 'kioskId', 'userId', 'locationId'
		]
	});
	
	var selfSignLogsStore = Ext.create('Ext.data.Store', {
		model: 'SelfSignLog',
		proxy: {
			type: 'ajax',
			url: '/admin/self_sign_logs/',
			simpleSortMode: true,
			extraParams: {
				locations: locations,
				services: services			
			},
			reader: {
				type: 'json',
				idProperty: 'id',
				root: 'logs',
				totalProperty: 'results'		
			}
		},
		storeId: 'SelfSignLogsStore',
		groupField: 'status',
		groupDir: 'DESC',
		autoDestroy: true
	});
	
	Ext.define('KioskButton', {
		extend: 'Ext.data.Model',
		fields:['id', 'name']
	});
	
	var level1ButtonsStore = Ext.create('Ext.data.Store', {
		model: 'KioskButton',
		autoDestroy: true,
		proxy: {
			type: 'ajax',
			url: '/admin/self_sign_logs/get_kiosk_buttons/',
			reader: {
				type: 'json',
				root: 'buttons'
			}
		},
		storeId: 'level1ButtonsStore',
		listeners: {
			beforeload: function() {
				this.getProxy().url = '/admin/self_sign_logs/get_kiosk_buttons/'+kioskId+'/';
			}
		}
	});
	
	var level2ButtonsStore = Ext.create('Ext.data.Store', {
		model: 'KioskButton',
		autoDestroy: true,
		proxy: {
			type: 'ajax',
			url: '/admin/self_sign_logs/get_kiosk_buttons/',
			reader: {
				type: 'json',
				root: 'buttons'
			}
		},
		storeId: 'level2ButtonsStore',
		listeners: {
			beforeload: function() {
				this.getProxy().url = '/admin/self_sign_logs/get_kiosk_buttons/'+kioskId+'/'+buttonParentId;
			},
			load: function(store, records, options) {		
				if(records[0] && records[0].data != undefined) {
					level2Buttons.enable();
					level2Buttons2.enable();
				}
			}
		}
	});
	
	var level3ButtonsStore = Ext.create('Ext.data.Store', {
		model: 'KioskButton',
		autoDestroy: true,
		proxy: {
			type: 'ajax',
			url: '/admin/self_sign_logs/get_kiosk_buttons/',
			reader: {
				type: 'json',
				root: 'buttons'
			}
		},
		storeId: 'level3ButtonsStore',
		root: 'buttons',
		idProperty: 'id',
		fields:['id', 'name'],
		listeners: {
			beforeload: function() {
				this.getProxy().url = '/admin/self_sign_logs/get_kiosk_buttons/'+kioskId+'/'+buttonParentId;
			},
			load: function(store, records, options) {		
				if(records[0] && records[0].data != undefined) {
					level3Buttons.enable();
					level3Buttons2.enable();
				}
			}
		}
	});
	
	var level1Buttons = Ext.create('Ext.form.field.ComboBox', {
		store: level1ButtonsStore,
		id: 'level1',
		name: 'level1',
		allowBlank: false,
		hideLabel: true,
		valueField: 'id',
	    displayField: 'name',
	    typeAhead: true,
	    QueryMode: 'remote',
	    emptyText: 'Select 1st Button',
	    width: 270,
	    getListParent: function() {
	       return this.el.up('.x-menu');
	    },
	    listeners: {
	    	select: function(combo, records, index) {
	    		buttonParentId = records[0].data.id;
	    		level2ButtonsStore.load();
	    		level2Buttons.reset();
	    		level2Buttons.disable();
	    		level3Buttons.reset();
	    		level3Buttons.disable();
	    		other.disable();    		
	    	}
	    }
	});
	
	var level2Buttons = Ext.create('Ext.form.field.ComboBox', {
		store: level2ButtonsStore,
		id: 'level2',
		name: 'level2',
		hideLabel: true,
		disabled: true,
		allowBlank: false,
		valueField: 'id',
	    displayField: 'name',
	    typeAhead: true,
	    queryMode: 'local',
	    emptyText: 'Select 2nd Button',
	    selectOnFocus: true,
	    width: 270,
	    getListParent: function() {
	        return this.el.up('.x-menu');
	    },
	    iconCls: 'no-icon',
	    listeners: {
	    	select: function(combo, records, index) {
	    		buttonParentId = records[0].data.id;
	    		other.disable();
	    		if(records[0].data.name  == 'Other' || records[0].data.name  == 'other') {
	    			other.enable();
	    		}
	    		level3ButtonsStore.load();
	    		level3Buttons.reset();
	    		level3Buttons.disable();   		
	    	}
	    }
	}); 
	   
	var level3Buttons = Ext.create('Ext.form.field.ComboBox', {
	    store: level3ButtonsStore,
	    hideLabel: true,
	    id: 'level3',
	    disabled: true,
	    allowBlank: false,
	    name: 'level3',
	    valueField: 'id',
	    displayField: 'name',
	    typeAhead: true,
	    queryMode: 'local',
	    emptyText: 'Select 3rd Button',
	    selectOnFocus: true,
	    width: 270,
	    getListParent: function() {
	        return this.el.up('.x-menu');
	    },
	    iconCls: 'no-icon',
	    listeners: {
	    	select: function(combo, records, index) {
	    		if(records[0].data.name  == 'Other' || records[0].data.name  == 'other') {
	    			other.enable();
	    		}
	    	}	    	
	    }
	}); 
	
	var other = Ext.create('Ext.form.field.Text', {
		id: 'other',
		hideLabel: false,
		allowBlank: false,
		disabled: true,
		fieldLabel: 'Other',
		width: 225
	});
	
	var reassign = Ext.create('Ext.form.Panel', {
		width: 285,
		frame: true,
		fieldDefaults: {
			labelWidth: 40	
		},
		items: [
			level1Buttons,
			level2Buttons,
			level3Buttons,
			other
		],
		fbar: {
			items: {
				text: 'Save',
				icon:  '/img/icons/save.png',
				handler: function() {
					var form = reassign.getForm();
					if(form.isValid()) {
						var values = form.getValues();
					    form.reset();
						level2Buttons.disable();
						level3Buttons.disable();
					    other.disable();
					    if(contextMenu.isVisible()){
					        contextMenu.hide(); 
					    }  					
					    Ext.Ajax.request({
					        url: '/admin/self_sign_logs/reassign/',
					        params: {
					        	'data[SelfSignLog][id]':  recordId,
					        	'data[SelfSignLog][level_1]': values.level1,
					        	'data[SelfSignLog][level_2]': values.level2,
					        	'data[SelfSignLog][level_3]': values.level3,
					        	'data[SelfSignLog][other]': values.other
					        },
					        success: function(response, opts){			        	
					        	var obj = Ext.decode(response.responseText);
					        	if(obj.success) {
									selfSignLogsStore.load();
									Ext.Msg.alert('Success', obj.message);					        		
					        	}
					        	else {
					        		opts.failure();
					        	}		            
					        },
					        failure: function(response, opts){
					        	var obj = Ext.decode(response.responseText);
					            Ext.Msg.alert('Error', obj.message);
					        }
					    });				
					}				
				}
			}
		}
	});
	
	var level1Buttons2 = level1Buttons.cloneConfig({
		id: 'level1Buttons2',
		listeners: {
	    	select: function(combo, records, index) {
	    		buttonParentId = records[0].data.id;
	    		level2ButtonsStore.load();
	    		level2Buttons2.reset();
	    		level2Buttons2.disable();
	    		level3Buttons2.reset();
	    		level3Buttons2.disable();
	    		other2.disable();    		
	    	}		
		}
	});
	var level2Buttons2 = level2Buttons.cloneConfig({
		id: 'level2Buttons2',
	    listeners: {
	    	select: function(combo, records, index) {
	    		buttonParentId = records[0].data.id;
	    		other2.disable();
	    		if(records[0].data.name  == 'Other' || records[0].data.name  == 'other') {
	    			other2.enable();
	    		}
	    		level3ButtonsStore.load();
	    		level3Buttons2.reset();
	    		level3Buttons2.disable();   		
	    	}
	    }	
	});
	var level3Buttons2 = level3Buttons.cloneConfig({
		id: 'level3Buttons2',
	    listeners: {
	    	select: function(combo, records, index) {
	    		if(records[0].data.name  == 'Other' || records[0].data.name  == 'other') {
	    			other2.enable();
	    		}
	    	}	    	
	    }	
	});
	var other2 = other.cloneConfig({id: 'other2'});
	
	
	var newRecord = Ext.create('Ext.form.Panel', {
		width: 285,
		frame: true,
		fieldDefaults: {
			labelWidth: 40	
		},
		items: [
			level1Buttons2,
			level2Buttons2,
			level3Buttons2,
			other2
		],
		fbar: {
			items: {
				text: 'Save',
				icon:  '/img/icons/save.png',
				handler: function() {
					var form = newRecord.getForm();
					if(form.isValid()) {
						var values = form.getValues();
					    form.reset();
						level2Buttons2.disable();
						level3Buttons2.disable();
					    other2.disable();
					    if(contextMenu.isVisible()){
					        contextMenu.hide(); 
					    }  					
					    Ext.Ajax.request({
					        url: '/admin/self_sign_logs/new_record/',
					        params: {
					        	'data[SelfSignLog][user_id]':  userId,
					        	'data[SelfSignLog][location_id]':  locationId,
					        	'data[SelfSignLog][kiosk_id]':  kioskId,
					        	'data[SelfSignLog][level_1]': values.level1,
					        	'data[SelfSignLog][level_2]': values.level2,
					        	'data[SelfSignLog][level_3]': values.level3,
					        	'data[SelfSignLog][other]': values.other2
					        },
					        success: function(response, opts){			        	
					        	var obj = Ext.decode(response.responseText);
					        	
					        	if(obj.success) { 	
									selfSignLogsStore.load();
									Ext.Msg.alert('Success', obj.message);					        		
					        	}
					        	else {
					        		opts.failure();
					        	}		            
					        },
					        failure: function(response, opts){
					        	var obj = Ext.decode(response.responseText);
					            Ext.Msg.alert('Error', obj.message);
					        }
					    });				
					}				
				}
			}
		}
	});
	
	var contextMenu = Ext.create('Ext.menu.Menu', {
	  items: [{
	    text: 'Open',
	    id: 'cmOpen',
	    icon:  '/img/icons/note_add.png',
	    iconCls: 'edit',
	    handler: function() {
	    	var record = selfSignLogsGrid.store.getAt(rowIndex);
	    	updateStatus(record.data.id, 0);	
	    }
	  },{
	  	text: 'Close',
	  	id: 'cmClose',
	  	icon:  '/img/icons/note_delete.png',
	    handler: function() {
	    	var record = selfSignLogsGrid.store.getAt(rowIndex);
	    	updateStatus(record.data.id, 1);	
	    }  	
	  },{
	  	text: 'Not Helped',
	  	id: 'cmNotHelped',
	  	icon:  '/img/icons/note_error.png',
	    handler: function() {
	    	var record = selfSignLogsGrid.store.getAt(rowIndex);
	    	updateStatus(record.data.id, 2);	
	    }   	
	  },{
	  	text: 'Reassign',
	  	hidden: true,
	  	id: 'cmReassign',
	  	icon:  '/img/icons/arrow_undo.png',
		menu: {
			cls: 'reassign-menu',
			width: 295,
			items: [
				reassign
			],
		    listeners: {
			  	beforehide: function() {
			  		reassign.getForm().reset();	  		
			  		level2Buttons.disable();
			  		level3Buttons.disable();
				    other.disable();			    
			  	}
		   }			
		}
	  },{
	  	text: 'New Sign In',
	  	id: 'cmNewRecord',
	  	hidden: true,
	  	icon:  '/img/icons/add.png',
		menu: {
			cls: 'new-sign-in-menu',
			width: 295,
			items: [
				newRecord
			],
		    listeners: {
			  	beforehide: function() {
			  		newRecord.getForm().reset();	  		
			  		level2Buttons2.disable();
			  		level3Buttons2.disable();
				    other2.disable();			    
			  	}
		   }			
		}  	
	  }]
	});
	
	var groupingFeature = Ext.create('Ext.grid.feature.Grouping',{
	    groupHeaderTpl: '{name} ({rows.length} Item{[values.rows.length > 1 ? "s" : ""]})'
	});
	
	var selfSignLogsGrid = Ext.create('Ext.grid.Panel', {
		store: selfSignLogsStore,
		id: 'selfSignGrid',
		height: 500,
		width: 950,
		frame: true,
		features: [groupingFeature],
		renderTo: 'SelfSignLogs',
		columns: [{
			text: 'Id',
			dataIndex: 'id',
			sortable: true,
			hidden: true
		},{
			text: 'Status',
			dataIndex: 'status',
			sortable: true,
			hidden: true
		},{
			text: 'First',
			dataIndex: 'firstname',
			sortable: true,
			width: 75
		},{
			text: 'Last',
			dataIndex: 'lastname',
			sortable: true,
			width: 75		
		},{
			text: 'Last 4',
			dataIndex: 'last4',
			sortable: true,
			width: 40	
		},{
			text: 'Service',
			dataIndex: 'service',
			sortable: true,
			flex: 1
		},{
			text: 'Last Act. Admin',
			dataIndex: 'admin',
			sortable: true,
			width: 100
		},{
			text: 'Location',
			dataIndex: 'location',
			sortable: true,
			width: 100
		},{
			text: 'Date',
			dataIndex: 'created',
			format: 'm/d/y g:i a',
			xtype: 'datecolumn',
			sortable: true,
			width: 100
		}],
		viewConfig: {
			loadMask: false,
			singleSelect: true,
			onStoreLoad: Ext.emptyFn,
			emptyText: 'No records at this time.',
	        listeners: {
	            itemcontextmenu: function(view, rec, node, index, e) {
	                e.stopEvent();
	                contextMenu.showAt(e.getXY());
			    	switch(rec.data.status) {
			    		case 'Open': {
			    			Ext.getCmp('cmOpen').hide();
			    			Ext.getCmp('cmNotHelped').show();
			    			Ext.getCmp('cmClose').show();
			    			Ext.getCmp('cmReassign').show();
			    			Ext.getCmp('cmNewRecord').hide();
			    			break;
			    		}
			    		case 'Closed': {
			    			Ext.getCmp('cmClose').hide();
			    			Ext.getCmp('cmOpen').show();
			    			Ext.getCmp('cmNotHelped').show();
			    			Ext.getCmp('cmReassign').hide();
			    			Ext.getCmp('cmNewRecord').show();
			    			break;
			    		}
			    		case 'Not Helped': {
			    			Ext.getCmp('cmNotHelped').hide();
			    			Ext.getCmp('cmClose').show();
			    			Ext.getCmp('cmOpen').show();
			    			Ext.getCmp('cmReassign').hide();
			    			Ext.getCmp('cmNewRecord').show();	    			
			    			break;	    			
			    		}
			    	}		
		     		rowIndex = index;
		     		recordId = rec.data.id;
		     		kioskId = rec.data.kioskId; 
		     		userId = rec.data.userId;
		     		locationId = rec.data.locationId;                
		            return false;
	            }
	        }		
		}
	});
	
	Ext.define('Location', {
		extend: 'Ext.data.Model',
		fields: ['id', 'name']
	});
	
	var locationsStore = Ext.create('Ext.data.Store', {
		model: 'Location',
		proxy: {
			type: 'ajax',
			url: '/admin/locations/get_location_list',
			reader: {
				type: 'json',
				root: 'locations'
			}
		},
		autoLoad: true
	});
	
	Ext.define('Service', {
		extend: 'Ext.data.Model',
		fields: ['id', 'name']
	});
	
	var servicesStore = Ext.create('Ext.data.Store', {
		model: 'Service',
		proxy: {
			type: 'ajax',
			url: '/admin/self_sign_logs/get_services',
			reader: {
				type: 'json',
				root: 'services'
			}	
		},
		autoLoad: false	
	});
	
	var selfSignSearch = Ext.create('Ext.form.FormPanel', {
		frame: true,
		collapsible: true,
		renderTo: 'SelfSignSearch',
		fieldDefautls: {
			labelWidth: 55
		},
		title: 'Filters',
		id: 'selfSignSearch',
		items: [{
			xtype: 'container',
			border: 0,
			layout: 'column',
			items: [{
				xtype: 'container',
				layout: 'anchor',
				columnWidth: 0.5,
				padding: 10,
				items: [{
					border: 0,
					xtype: 'boxselect',
					id: 'locationsSelect',
					fieldLabel: 'Locations',
					displayField: 'name',
					valueField: 'id',
					store: locationsStore,			
					queryMode: 'local',
					allowAddNewData: true,
					emptyText: 'Please make a selection',
					name: 'locations',
					allowBlank: true,
					msgTarget: 'under',
					width: 400,
					listeners: {
						'change': function() {
							Ext.getCmp('servicesSelect').reset();
							servicesStore.load({params: {
								locations: Ext.util.Format.htmlEncode(this.getValue())
							}});
						}
					}
				}]
			},{
				layout: 'anchor',
				xtype: 'container',
				columnWidth: 0.5,
				border: 0,
				padding: 10,
				items: [{
					xtype: 'boxselect',
					id: 'servicesSelect',
					store: servicesStore,
					queryMode: 'local',
					valueField: 'id',
					displayField: 'name',
					name: 'services',
					fieldLabel: 'Services',
					emptyText: 'Please make a selection',
					allowBlank: true,
					msgTarget: 'under',
					width: 400,
					listeners: {
						beforequery: function() {
							var val = Ext.getCmp('locationsSelect').getValue();					
							if(val !== []) {
								this.markInvalid('Please select a location first');
							}			
						}
					}
				}]
			}]
		}],
		
		fbar: [{
			text: 'Filter',
			icon:  '/img/icons/find.png',
			handler: function() {
				var form = selfSignSearch.getForm();
				if(form.isValid()) {
					locations = Ext.util.Format.htmlEncode(Ext.getCmp('locationsSelect').getValue());
					services = Ext.util.Format.htmlEncode(Ext.getCmp('servicesSelect').getValue());
					selfSignLogsStore.getProxy().extraParams = {
						locations: locations,
						services: services
					}
					selfSignLogsStore.load();	
				}	
			}
		},{
			text: 'Reset',
			icon:  '/img/icons/arrow_redo.png',
			handler: function() {
				locations = [];
				services = [];
				selfSignLogsStore.getProxy().extraParams = {
					locations: [],
					services: []
				}			
				Ext.getCmp('servicesSelect').reset();
				Ext.getCmp('locationsSelect').reset();
				selfSignLogsStore.load();
			}
		}]
	});
	
	function updateStatus(id, status) {
	    Ext.Ajax.request({
	        url: '/admin/self_sign_logs/update_status/' + id + '/' + status,
	        success: function(response){ 
				selfSignLogsStore.load();
	        },
	        failure: function(response){
	            Ext.Msg.alert('Error', 'An error has occured, please try again.');
	        }
	    });	
	}
	
	var loadLogs = {
		run: function() {
			selfSignLogsStore.load();		
		},
		interval: 10000
	}

	Ext.TaskManager.start(loadLogs);
});