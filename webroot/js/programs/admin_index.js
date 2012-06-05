/**
 * Models
 */
Ext.define('Program', {
  extend: 'Ext.data.Model',
  fields: ['id', 'name', 'actions', 'disabled']
});

/**
 * DataStores
 */
Ext.create('Ext.data.Store', {
  storeId: 'ProgramStore',
  autoLoad: true,
  autoSync: true,
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

Ext.onReady(function () {
  Ext.QuickTips.init();

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
      }, {
        disabled: true,
        icon: '/img/icons/copy.png',
        text: 'Duplicate Program',
        handler: function () {
          Ext.Msg.alert('Not yet implemented', 'This feature is not yet implemented');
        }
      }]
    }]
  });
});
