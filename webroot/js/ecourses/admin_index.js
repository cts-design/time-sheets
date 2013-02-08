/**
 * Models
 */
Ext.define('Ecourse', {
  extend: 'Ext.data.Model',
  fields: [
    { name: 'id', type: 'int' },
    'type',
    'name',
    'instructions',
    { name: 'default_passing_percentage', type: 'int' },
    { name: 'snapshot_cat_1', type: 'int' },
    { name: 'snapshot_cat_2', type: 'int' },
    { name: 'snapshot_cat_3', type: 'int' },
    { name: 'certificate_cat_1', type: 'int' },
    { name: 'certificate_cat_2', type: 'int' },
    { name: 'certificate_cat_3', type: 'int' }
  ]
});

/**
 * DataStores
 */
Ext.create('Ext.data.Store', {
  storeId: 'EcourseStore',
  autoLoad: true,
  model: 'Ecourse',
  proxy: {
    type: 'ajax',
    api: {
      read: '/admin/ecourses/index',
      update: '/admin/ecourses/update'
    },
    reader: {
      type: 'json',
      root: 'ecourses'
    },
    writer: {
      encode: true,
      root: 'ecourses',
      type: 'json',
      writeAllFields: false
    }
  }
});

/**
 * Extended classes
 */
Ext.define('EcourseGridPanel', {
  alias: 'widget.ecoursegridpanel',
  extend: 'Ext.grid.Panel',
  height: 300,
  store: 'EcourseStore'
});

Ext.onReady(function () {
  Ext.QuickTips.init();

  Ext.state.Manager.setProvider(new Ext.state.CookieProvider({
      expires: new Date(new Date().getTime()+(1000*60*60*24*365)) // 1 year
  }));

  var menuItems = [{
    text: 'Registration',
    handler: function () {
      window.location = '/admin/programs/create_registration';
    }
  }];

  var tabPanel = Ext.create('Ext.tab.Panel', {
    renderTo: 'ecoursesGrid',
    height: 400,
    title: 'Ecourses',
    dockedItems: [{
      xtype: 'toolbar',
      dock: 'top',
      items: [{
        text: 'New Ecourse',
        menu: menuItems
      }, {
        disabled: true,
        icon: '/img/icons/copy.png',
        id: 'duplicateProgramBtn',
        text: 'Duplicate Ecourse',
        handler: function () {
        }
      }]
    }],
    items: [{
      xtype: 'ecoursegridpanel',
      id: 'customer',
      title: 'Customer',
      columns: [{
        dataIndex: 'id',
        text: 'Id',
        width: 50
      }],
      viewConfig: {
        deferEmptyText: false,
        emptyText: 'There are no customer ecourses at this time',
        getRowClass: function (rec) {
          return rec.get('disabled') ? 'row-disabled' : 'row-active';
        },
        loadMask: true
      }
    }],
  });
});
