Ext.onReady(function () {

  Ext.QuickTips.init();

  Ext.define('Program', {
    extend: 'Ext.data.Model',
    fields: ['id', 'name', 'actions']
  });

  var programStore = Ext.create('Ext.data.Store', {
    storeId: 'programStore',
    autoLoad: true,
    model: 'Program',
    proxy: {
      type: 'ajax',
      url: '/admin/programs/index',
      reader: {
        type: 'json',
        root: 'programs'
      }
    }
  });

  var programGrid = Ext.create('Ext.grid.Panel', {
    store: programStore,
    renderTo: 'programGrid',
    height: 300,
    title: 'Programs',
    columns: [{
      id: 'id',
      text: 'Id',
      dataIndex: 'id',
      width: 50
    }, {
      text: 'Program Name',
      dataIndex: 'name',
      flex: 1
    }, {
      text: 'Actions',
      dataIndex: 'actions',
      flex: 1
    }],
    dockedItems: [{
      xtype: 'toolbar',
      dock: 'top',
      items: [{
        text: 'New Program',
        menu: [{
          text: 'Registration',
          handler: function () {
            window.location = '/admin/programs/create_registration';
          }
        }, {
          text: 'Orientation',
          handler: function () {
            window.location = '/admin/programs/create_orientation';
          }
        }]
      }]
    }]
  });
});
