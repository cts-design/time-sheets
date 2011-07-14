/*
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2011
 * @link http://ctsfla.com
 * @package Atlas V3
 */

// Override to allow disabling of collapseable groups 
Ext.override(Ext.grid.GroupingView, {
    disableGroupingByClick: false,
    
    processEvent: function(name, e){
        Ext.grid.GroupingView.superclass.processEvent.call(this, name, e);
        var hd = e.getTarget('.x-grid-group-hd', this.mainBody);
        if(hd){
            // group value is at the end of the string
            var field = this.getGroupField(),
                prefix = this.getPrefix(field),
                groupValue = hd.id.substring(prefix.length),
                emptyRe = new RegExp('gp-' + Ext.escapeRe(field) + '--hd');

            // remove trailing '-hd'
            groupValue = groupValue.substr(0, groupValue.length - 3);
            
            // also need to check for empty groups
            if(groupValue || emptyRe.test(hd.id)){
                this.grid.fireEvent('group' + name, this.grid, field, groupValue, e);
            }
            if(name == 'mousedown' && e.button == 0 && !this.disableGroupingByClick){
                this.toggleGroup(hd.parentNode);
            }
        }

    },
});

var rowIndex = null;

var recordId = null;

var kioskId = '';

var buttonParentId = '';

var selfSignLogsProxy = new Ext.data.HttpProxy({
	method: 'GET',
	prettyUrls: true,
	url: '/admin/self_sign_logs/'
});

var selfSignLogsReader = new Ext.data.JsonReader({
	idProperty: 'id',
	root: 'logs',
	totalProperty: 'results',
	fields: [
		'id', 'status', 'firstname', 'lastname', 'last4', 'admin', 
		{name: 'created', type: 'date', dateFormat: 'Y-m-d H:i:s'}, 
		'location', 'service', 'kioskId'
	]
})

var selfSignLogsStore = new Ext.data.GroupingStore({
	reader: selfSignLogsReader,
	proxy:	selfSignLogsProxy,
	storeId: 'SelfSignLogsStore',
	groupField: 'status',
	groupDir: 'DESC',
	autoDestroy: true
});

var kioskButtonsProxy = new Ext.data.HttpProxy({
	method: 'GET',
	prettyUrls: true,
	url: '/admin/self_sign_logs/get_kiosk_buttons/'
});

var level1ButtonsStore = new Ext.data.JsonStore({
	autoDestroy: true,
	proxy: kioskButtonsProxy,
	storeId: 'level1ButtonsStore',
	root: 'buttons',
	idProperty: 'id',
	fields:['id', 'name'],
	listeners: {
		beforeload: function() {
			kioskButtonsProxy.setUrl('/admin/self_sign_logs/get_kiosk_buttons/'+kioskId+'/');
		}
	}
});

var level2ButtonsStore = new Ext.data.JsonStore({
	autoDestroy: true,
	proxy: kioskButtonsProxy,
	storeId: 'level2ButtonsStore',
	root: 'buttons',
	idProperty: 'id',
	fields:['id', 'name'],
	listeners: {
		beforeload: function() {
			kioskButtonsProxy.setUrl('/admin/self_sign_logs/get_kiosk_buttons/'+kioskId+'/'+buttonParentId);
		},
		load: function(store, records, options) {		
			if(records[0] && records[0].data != undefined) {
				level2Buttons.enable();
			}
		}
	}
});

var level3ButtonsStore = new Ext.data.JsonStore({
	autoDestroy: true,
	proxy: kioskButtonsProxy,
	storeId: 'level3ButtonsStore',
	root: 'buttons',
	idProperty: 'id',
	fields:['id', 'name'],
	listeners: {
		beforeload: function() {
			kioskButtonsProxy.setUrl('/admin/self_sign_logs/get_kiosk_buttons/'+kioskId+'/'+buttonParentId);
		},
		load: function(store, records, options) {		
			if(records[0] && records[0].data != undefined) {
				level3Buttons.enable();
			}
		}
	}
});

var level1Buttons = new Ext.form.ComboBox({
	store: level1ButtonsStore,
	id: 'level1',
	hiddenName: 'level1',
	allowBlank: false,
	hideLabel: true,
	valueField: 'id',
    displayField: 'name',
    typeAhead: true,
    mode: 'remote',
    triggerAction: 'all',
    emptyText: 'Select 1st Button',
    selectOnFocus: true,
    width: 270,
    getListParent: function() {
        return this.el.up('.x-menu');
    },
    iconCls: 'no-icon',
    listeners: {
    	select: function(combo, record, index) {
    		buttonParentId = record.data.id;
    		level2ButtonsStore.load();
    		level2Buttons.reset();
    		level2Buttons.disable();
    		level3Buttons.reset();
    		level3Buttons.disable();
    		other.disable();    		
    	},
		beforequery: function(qe){
            delete qe.combo.lastQuery;
        }
    }
});

var level2Buttons = new Ext.form.ComboBox({
	store: level2ButtonsStore,
	id: 'level2',
	hiddenName: 'level2',
	hideLabel: true,
	disabled: true,
	allowBlank: false,
	valueField: 'id',
    displayField: 'name',
    typeAhead: true,
    mode: 'local',
    triggerAction: 'all',
    emptyText: 'Select 2nd Button',
    selectOnFocus: true,
    width: 270,
    getListParent: function() {
        return this.el.up('.x-menu');
    },
    iconCls: 'no-icon',
    listeners: {
    	select: function(combo, record, index) {
    		buttonParentId = record.data.id;
    		other.disable();
    		if(record.data.name  == 'Other' || record.data.name  == 'other') {
    			other.enable();
    		}
    		level3ButtonsStore.load();
    		level3Buttons.reset();
    		level3Buttons.disable();   		
    	}
    }
}); 
   
var level3Buttons = new Ext.form.ComboBox({
    store: level3ButtonsStore,
    hideLabel: true,
    id: 'level3',
    disabled: true,
    allowBlank: false,
    hiddenName: 'level3',
    valueField: 'id',
    displayField: 'name',
    typeAhead: true,
    mode: 'local',
    triggerAction: 'all',
    emptyText: 'Select 3rd Button',
    selectOnFocus: true,
    width: 270,
    getListParent: function() {
        return this.el.up('.x-menu');
    },
    iconCls: 'no-icon',
    listeners: {
    	select: function(combo, record, index) {
    		if(record.data.name  == 'Other' || record.data.name  == 'other') {
    			other.enable();
    		}
    	}	    	
    }
}); 

var other = new Ext.form.TextField({
	id: 'other',
	hideLabel: false,
	allowBlank: false,
	disabled: true,
	fieldLabel: 'Other',
	width: 225
});

var reassign = new Ext.form.FormPanel({
	width: 285,
	frame: true,
	labelWidth: 40,
	items: [
		level1Buttons,
		level2Buttons,
		level3Buttons,
		other
	],
	fbar: {
		items: {
			text: 'Submit',
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
					            selfSignLogsStore.reload();
								Ext.Msg.alert('Success', obj.message)					        		
				        	}
				        	else {
				        		opts.failure();
				        	}		            
				        },
				        failure: function(response, opts){
				        	var obj = Ext.decode(response.responseText);
				            Ext.Msg.alert('Error', obj.message)
				        },
				    });				
				}				
			}
		}
	}
});

var contextMenu = new Ext.menu.Menu({
  items: [{
    text: 'Open',
    id: 'cmOpen',
    iconCls: 'edit',
    handler: function() {
    	var record = selfSignLogsGrid.store.getAt(rowIndex);
    	updateStatus(record.data.id, 0);	
    }
  },{
  	text: 'Close',
  	id: 'cmClose',
    handler: function() {
    	var record = selfSignLogsGrid.store.getAt(rowIndex);
    	updateStatus(record.data.id, 1);	
    }  	
  },{
  	text: 'Not Helped',
  	id: 'cmNotHelped',
    handler: function() {
    	var record = selfSignLogsGrid.store.getAt(rowIndex);
    	updateStatus(record.data.id, 2);	
    }   	
  },{
  	text: 'Reassign',
  	id: 'cmReassign',
	menu: {
		width: 295,
		items: [
			reassign
		]
	}
  }]
})

var selfSignLogsGrid = new Ext.grid.GridPanel({
	store: selfSignLogsStore,
	id: 'selfSignGrid',
	height: 500,
	frame: true,
	loadMask: false,
	columns: [{
		header: 'Id',
		dataIndex: 'id',
		sortable: true,
		hidden: true
	},{
		header: 'Status',
		dataIndex: 'status',
		sortable: true,
		width: 40
	},{
		header: 'First',
		dataIndex: 'firstname',
		sortable: true,
		width: 40
	},{
		header: 'Last',
		dataIndex: 'lastname',
		sortable: true,
		width: 40		
	},{
		header: 'Last 4',
		dataIndex: 'last4',
		sortable: true,
		width: 30		
	},{
		header: 'Service',
		dataIndex: 'service',
		width: 280,
		sortable: true
	},{
		header: 'Last Act. Admin',
		dataIndex: 'admin',
		sortable: true,
		width: 65
	},{
		header: 'Location',
		dataIndex: 'location',
		sortable: true,
		width: 70
	},{
		header: 'Date',
		dataIndex: 'created',
		format: 'm/d/y g:i a',
		xtype: 'datecolumn',
		sortable: true,
		width: 65
	}],
	view: new Ext.grid.GroupingView({
		forceFit: true,
		groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Items" : "Item"]})',
		startCollapsed: false,
		hideGroupedColumn: true,
		enableGroupingMenu: false,
		disableGroupingByClick: true,
		deferEmptyText: false,
		ignoreAdd: true,
		emptyText: '<div class="x-grid-empty">No records at this time.</div>'
	}),
	sm: new Ext.grid.RowSelectionModel({singleSelect:true}),
	listeners: {
		cellcontextmenu: function(grid, index, columnIndex, event) {
			event.stopEvent();
	    	var record = this.store.getAt(index);
	    	switch(record.data.status) {
	    		case 'Open': {
	    			Ext.getCmp('cmOpen').hide();
	    			Ext.getCmp('cmNotHelped').show();
	    			Ext.getCmp('cmClose').show();
	    			break;
	    		}
	    		case 'Closed': {
	    			Ext.getCmp('cmClose').hide();
	    			Ext.getCmp('cmOpen').show();
	    			Ext.getCmp('cmNotHelped').show();
	    			break;
	    		}
	    		case 'Not Helped': {
	    			Ext.getCmp('cmNotHelped').hide();
	    			Ext.getCmp('cmClose').show();
	    			Ext.getCmp('cmOpen').show();
	    			break;	    			
	    		}
	    	}		
     		contextMenu.showAt(event.xy);
     		rowIndex = index;
     		recordId = record.data.id;
     		kioskId = record.data.kioskId; 
		}
	}

})

var locationsProxy = new Ext.data.HttpProxy({
	url: '/admin/locations/get_location_list',
	method: 'GET'
});

var locationsStore = new Ext.data.JsonStore({
	proxy: locationsProxy,
	root: 'locations',
	autoLoad: true,
	fields: ['id', 'name']
});

var servicesProxy = new Ext.data.HttpProxy({
	url: '/admin/self_sign_logs/get_services',
	method: 'GET'
});

var servicesStore = new Ext.data.JsonStore({
	proxy: servicesProxy,
	root: 'services',
	autoLoad: false,
	fields: ['id', 'name']
});

var selfSignSearch = new Ext.form.FormPanel({
	frame: true,
	collapsible: true,
	labelWidth: 55,
	title: 'Filters',
	id: 'selfSignSearch',
	items: [{
		layout: 'column',
		items: [{
			layout: 'form',
			columnWidth: 0.5,
			items: [{
				xtype: 'superboxselect',
				id: 'locationsSelect',
				store: locationsStore,
				mode: 'local',
				allowAddNewData: true,
				blankText: 'Please make a selection',
				valueField: 'id',
				displayField: 'name',
				name: 'locations',
				fieldLabel: 'Locations',
				allowBlank: true,
				msgTarget: 'under',
				width: 400,
				listeners: {
					'additem': function() {
						Ext.getCmp('servicesSelect').reset();
						servicesStore.load({params: {
							locations: this.getValue()
						}})
					},
					'removeitem' : function() {
						Ext.getCmp('servicesSelect').reset();
						servicesStore.load({params: {
							locations: this.getValue()
						}})						
					}
				}
			}]
		},{
			layout: 'form',
			columnWidth: 0.5,
			items: [{
				xtype: 'superboxselect',
				id: 'servicesSelect',
				store: servicesStore,
				mode: 'local',
				valueField: 'id',
				displayField: 'name',
				name: 'services',
				fieldLabel: 'Services',
				allowBlank: true,
				msgTarget: 'under',
				width: 400,
				listeners: {

				}
			}]
		}]
	}],
	fbar: [{
		text: 'Filter',
		handler: function() {
			var form = selfSignSearch.getForm();
			if(form.isValid()) {
				var locations = Ext.getCmp('locationsSelect').getValue();
				var services = Ext.getCmp('servicesSelect').getValue();
				selfSignLogsStore.load({
					params: {
						locations: locations,
						services: services
					}
				});	
			}	
		}
	},{
		text: 'Reset',
		handler: function() {
			Ext.getCmp('servicesSelect').reset();
			Ext.getCmp('locationsSelect').reset();
			selfSignLogsStore.load();
		}
	}]
})

function updateStatus(id, status) {
    Ext.Ajax.request({
        url: '/admin/self_sign_logs/update_status/' + id + '/' + status,
        success: function(response){  
            selfSignLogsStore.reload();
        },
        failure: function(response){
            Ext.Msg.alert('Error', 'An error has occured, please try again.');
        },
    });	
}

Ext.onReady( function() {
	Ext.QuickTips.init();
	selfSignSearch.render('SelfSignSearch');
	selfSignLogsStore.load();	
	selfSignLogsGrid.render('SelfSignLogs');
	setInterval('selfSignLogsStore.reload()', 10000);
});