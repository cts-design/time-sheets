Ext.define('Event', {
  extend: 'Ext.data.Model',
  fields: [
    {name: 'id'},
    {name: 'event_category_id'},
    {name: 'category'},
    {name: 'name'},
    {name: 'description'},
    {name: 'location', serverKey: 'location_id'},
    {name: 'other_location'},
    {name: 'url'},
    {name: 'address'},
    {name: 'scheduled', type: 'date'},
    {name: 'seats_available'},
    {name: 'duration'},
    {name: 'registered'},
    {name: 'attended'},
    {name: 'cat_1'},
    {name: 'cat_2'},
    {name: 'cat_3'},
    {name: 'created', type: 'date', dateFormat: 'n/j h:ia'},
    {name: 'modified', type: 'date', dateFormat: 'n/j h:ia'}
  ]
});

var itmesPerPage = 20;

Ext.create('Ext.data.Store', {
  model: 'Event',
  storeId: 'eventsStore',
  pageSize: itmesPerPage,
  proxy: {
    type: 'ajax',
    url: '/admin/events/archive',
    reader: {
      type: 'json',
      root: 'events',
      totalProperty: 'totalCount'
    },
    directionParam: 'direction',
    simpleSortMode: true
  },
  remoteSort: true,
  autoLoad: true,
  listeners: {
  beforeload: function (store, options) {
    if(store.sorters.items[0]){
      var oldSortParam = store.sorters.items[0].property;
      var i = null;
      for(i=0; i < gridColumns.length; i++) {
        var currentCol = gridColumns[i];
        if(currentCol.sortable && currentCol.customSort &&
           currentCol.dataIndex === oldSortParam) {
             store.sorters.items[0].property =
             currentCol.customSort;
             break;
        }
     }
    }
  }
}
});
	var dateSearchTb = Ext.create('Ext.Toolbar', {
		width: 275,
		items: [{
			text: 'Today',
			handler: function() {
				var dt = new Date();
				Ext.getCmp('fromDate').setValue(Ext.Date.format(dt, 'm/d/Y'));
				Ext.getCmp('toDate').setValue(Ext.Date.format(dt, 'm/d/Y'));
			}
		}, {
			xtype: 'tbseparator'
		}, {
			text: 'Yesterday',
			handler: function() {
				var dt = new Date();
				dt.setDate(dt.getDate() - 1);
				Ext.getCmp('fromDate').setValue(Ext.Date.format(dt, 'm/d/Y'));
				Ext.getCmp('toDate').setValue(Ext.Date.format(dt, 'm/d/Y'));
			}
		}, {
			xtype: 'tbseparator'
		}, {
			text: 'Last Week',
			handler: function() {
				var dt = new Date();
				dt.setDate(dt.getDate() - (parseInt(Ext.Date.format(dt, 'N'), 10) + 6));
				Ext.getCmp('fromDate').setValue(Ext.Date.format(dt, 'm/d/Y'));
				dt.setDate(dt.getDate() + 6);
				Ext.getCmp('toDate').setValue(Ext.Date.format(dt, 'm/d/Y'));
			}
		}, {
			xtype: 'tbseparator'
		}, {
			text: 'Last Month',
			handler: function() {
				var now = new Date(),
					firstDayPrevMonth = new Date(now.getFullYear(), now.getMonth() - 1, 1),
					lastDayPrevMonth = Ext.Date.getLastDateOfMonth(firstDayPrevMonth);
				Ext.getCmp('fromDate').setValue(Ext.Date.format(firstDayPrevMonth, 'm/d/Y'));
				Ext.getCmp('toDate').setValue(Ext.Date.format(lastDayPrevMonth, 'm/d/Y'));
			}
		}]
	});
	
	Ext.define('SearchType', {
		extend: 'Ext.data.Model',
		fields : ['type', 'label']
	});
		
	var searchTypeStore = Ext.create('Ext.data.Store', {
		model: 'SearchType',
		data : [
			{type: 'firstname', label: 'First Name'},
			{type: 'lastname', label : 'Last Name'},
			{type: 'last4', label: 'Last 4 SSN'},
			{type: 'fullssn', label: 'Full SSN'}
		]
	});
	
	var programResponseSearch = Ext.create('Ext.form.Panel', {
		frame : true,
		fieldDefaults:{
			labelWidth: 50
		},
		collapsible : true,
		height : 190,
		region : 'north',
		title : 'Filters',
		id : 'programResponseSearch',
		items : [{
			layout : 'column',
			xtype: 'container',
			bodyStyle: 'margin: 0 0 0 7px',
			items : [{
				layout : 'anchor',
				columnWidth : 0.333,
				height : 120,
				frame : true,
				bodyStyle : 'padding: 0 10px',
				title : 'Dates',
				items: [{
					xtype: 'datefield',
					id: 'fromDate',
					fieldLabel: 'From',
					name: 'fromDate',
					width: 200
				}, {
					xtype: 'datefield',
					fieldLabel: 'To',
					name: 'toDate',
					id: 'toDate',
					width: 200
				}, dateSearchTb]
			}, {
				layout : 'anchor',
				columnWidth : 0.333,
				height : 120,
				frame : true,
				bodyStyle : 'padding: 0 10px',
				title : 'Response',
				items : [{
					xtype : 'textfield',
					fieldLabel : 'Id',
					name: 'id',
					width : 200
				}]
			},{
				layout : 'anchor',
				columnWidth : 0.333,
				height : 120,
				frame : true,
				bodyStyle : 'padding: 0 10px',
				title : 'Customer',
				items : [{
					xtype : 'combo',
					fieldLabel : 'Search Type',
					name : 'searchType',
					store : searchTypeStore,
					queryMode: 'local',
					valueField: 'type',
					displayField: 'label',
					width: 250
				},
				{
					xtype : 'textfield',
					fieldLabel : 'Search',
					name : 'search',
					width : 250
				}]
			}]
		}],
		fbar : [{
			text : 'Search',
			id : 'docSearch',
			icon : '/img/icons/find.png',
			handler : function() {
				var f = programResponseSearch.getForm(), vals = f.getValues();
				Ext.iterate(vals, function (key, value){
					programResponseProxy.extraParams[key] = value;
				});
				programResponseTabs.getActiveTab().getStore().loadPage(1, {start: 0, limit: 10});
			}
		}, {
			text : 'Reset',
			icon : '/img/icons/arrow_redo.png',
			handler : function() {
				var f = programResponseSearch.getForm();
				f.reset();
        programResponseProxy.extraParams.id = undefined;
        programResponseProxy.extraParams.search = undefined;
        programResponseProxy.extraParams.toDate = undefined;
        programResponseProxy.extraParams.fromDate = undefined;
				programResponseTabs.getActiveTab().getStore().loadPage(1, {start: 0, limit: 10});
			}
		},{
			text: 'Report',
			icon:  '/img/icons/excel.png',
			handler: function(){
				var f = programResponseSearch.getForm();
				var vals = f.getValues();
        vals.status = programResponseProxy.extraParams.status;
        vals.progId = progId;
				vals = Ext.urlEncode(vals);
				window.location = '/admin/program_responses/report?'+ vals;
			}
		}]
	});

var gridColumns = [{
  text: 'id',
  dataIndex: 'id',
  hidden: true
},{
  text: 'Name',
  dataIndex: 'name',
  flex: 1
},{
  text: 'Category',
  dataIndex: 'category',
  flex: 1,
  customSort: 'EventCategory.name'
},{
  text: 'Location',
  dataIndex: 'location',
  flex: 1,
  customSort: 'Location.name'
},{
  text: 'Scheduled',
  dataIndex: 'scheduled',
  xtype: 'datecolumn',
  format: 'm/d/y h:i a',
  width: 120
},{
  text: 'Registered',
  dataIndex: 'registered',
  width: 75,
  sortable: false
},{
  text: 'Attended',
  dataIndex: 'registered',
  width: 75,
  sortable: false
}];

Ext.create('Ext.menu.Menu', {
  id: 'contextMenu',
  title: 'Event Actions',
  bodyPadding: 5,
  items:[{
    id: 'reportButton',
    xtype: 'button',
    text: 'Attendance Report',
    icon: '/img/icons/excel.png',
    href: '/admin/events/attendance_report/'
  }]
});

Ext.onReady(function(){
  Ext.QuickTips.init();

  Ext.create('Ext.grid.Panel', {
    store: Ext.data.StoreManager.lookup('eventsStore'),
    title: 'Archive',
    height: 400,
    renderTo: 'events',
    dockedItems: [{
      xtype: 'pagingtoolbar',
      store: Ext.data.StoreManager.lookup('eventsStore'),
      dock: 'bottom',
      displayInfo: true
    }],
    columns: gridColumns,
    listeners: {
      itemcontextmenu: function(view, rec, node, index, e){
        e.stopEvent();
        Ext.getCmp('contextMenu').showAt(e.getXY());
        Ext.getCmp('reportButton').setParams({id: eventId});
      }
    }
  });
});

