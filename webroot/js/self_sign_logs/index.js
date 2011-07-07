/*
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2011
 * @link http://ctsfla.com
 * @package Atlas V3
 */
var rowIndex = null;

var selfSignLogsProxy = new Ext.data.HttpProxy({
	method: 'GET',
	prettyUrls: true,
	url: '/admin/self_sign_logs/'
});

var selfSignLogsReader = new Ext.data.JsonReader({
	idProperty: 'id',
	root: 'logs',
	totalProperty: 'results',
	fields: ['id', 'status', 'firstname', 'lastname', 'last4', 'admin', 'created', 'location', 'service']
})

var selfSignLogsStore = new Ext.data.GroupingStore({
	reader: selfSignLogsReader,
	proxy:	selfSignLogsProxy,
	storeId: 'SelfSignLogsStore',
	groupField: 'status',
	groupDir: 'DESC',
	autoDestroy: true
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
  	text: 'Re-Assign'
  }]
})

var selfSignLogsGrid = new Ext.grid.GridPanel({
	store: selfSignLogsStore,
	id: 'selfSignGrid',
	height: 500,
	frame: true,
	loadMask: true,
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
		width: 270,
		sortable: true
	},{
		header: 'Last Act. Admin',
		dataIndex: 'admin',
		sortable: true,
		width: 70
	},{
		header: 'Location',
		dataIndex: 'location',
		sortable: true,
		width: 70
	},{
		header: 'Date',
		dataIndex: 'created',
		sortable: true,
		width: 75
	}],
	view: new Ext.grid.GroupingView({
		forceFit: true,
		groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Items" : "Item"]})',
		startCollapsed: true,
		hideGroupedColumn: true,
		deferEmptyText: false,
		emptyText: '<div class="x-grid-empty">No records at this time.</div>'
	}),
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
	labelWidth: 50,
	title: 'Filters',
	id: 'selfSignSearch',
	items: [{
		layout: 'column',
		items: [{
			layout: 'form',
			columnWidth: 0.4,
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
				allowBlank: false,
				msgTarget: 'under',
				width: 300,
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
			columnWidth: 0.4,
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
				width: 300,
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
				selfSignLogsStore.reload({
					params: {
						locations: locations,
						services: services
					}
				});	
			}	
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
            Ext.Msg.show({
                title: 'Status chage error.',
                msg: 'An error occured when trying to update the records status.',
                buttons: Ext.air.Msg.OK,
                icon: Ext.air.MessageBox.ERROR
            });
        },
    });	
}

Ext.onReady( function() {
	Ext.QuickTips.init();
	selfSignSearch.render('SelfSignSearch');
	selfSignLogsGrid.render('SelfSignLogs');
	selfSignLogsStore.load();
	//setInterval('selfSignLogsStore.reload()', 10000);
	selfSignLogsStore.addListener('load', function(store, records, options) {
		var view = selfSignLogsGrid.getView();
		var groupId = view.getGroupId('Open');
		if(records[0]._groupId == groupId) {
			view.toggleGroup(groupId, true);
		}
		
	})
});