/**
 * Models
 */
Ext.define('Program', {
  extend: 'Ext.data.Model',
  fields: [
    { name: 'id', type: 'int' },
    'name',
    'actions',
    { name: 'disabled', type: 'int' }
  ]
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
    api: {
      read: '/admin/programs/index',
      update: '/admin/programs/update'
    },
    reader: {
      type: 'json',
      root: 'programs'
    },
    writer: {
      encode: true,
      root: 'programs',
      type: 'json',
      writeAllFields: false
    }
  }
});

Ext.onReady(function () {
  Ext.QuickTips.init();

  var programGrid = Ext.create('Ext.grid.Panel', {
    store: 'ProgramStore',
    renderTo: 'programGrid',
    height: 300,
    title: 'Programs',
    plugins: [
      Ext.create('Ext.grid.plugin.CellEditing', {
        clicksToEdit: 2
      })
    ],
    columns: [{
      id: 'id',
      dataIndex: 'id',
      hidden: true,
      text: 'Id',
      width: 50
    }, {
      dataIndex: 'name',
      editor: {
        xtype: 'textfield',
        allowBlank: false
      },
      text: 'Program Name',
      flex: 1
    }, {
      align: 'center',
      text: 'Status',
      dataIndex: 'disabled',
      editor: {
        xtype: 'combo',
        allowBlank: false,
        displayField: 'stringVal',
        store: Ext.create('Ext.data.Store', {
          fields: ['intVal', 'stringVal'],
          data: [{
            'intVal': 1, stringVal: 'Disabled'
          }, {
            'intVal': 0, stringVal: 'Active'
          }]
        }),
        queryMode: 'local',
        valueField: 'intVal'
      },
      renderer: function (value) {
        if (value) {
          return "Disabled";
        } else {
          return "Active";
        }
      },
      width: 75
    }, {
      xtype: 'actioncolumn',
      align: 'center',
      header: 'Edit',
      width: 50,
      items: [{
        icon: '/img/icons/edit.png',
        tooltip: 'Edit Program',
        handler: function (grid, rowIndex, colIndex) {
          var rec = grid.getStore().getAt(rowIndex);
          window.location = '/admin/programs/edit/' + rec.get('id');
        }
      }],
    }, {
      xtype: 'actioncolumn',
      align: 'center',
      header: 'View Responses',
      width: 100,
      items: [{
        icon: '/img/icons/file-cab.png',
        tooltip: 'View Responses',
        handler: function (grid, rowIndex, colIndex) {
          var rec = grid.getStore().getAt(rowIndex);
          window.location = '/admin/program_responses/index/' + rec.get('id');
        }
      }],
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
