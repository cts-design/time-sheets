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
