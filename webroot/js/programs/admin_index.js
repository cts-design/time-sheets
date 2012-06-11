/**
 * Models
 */
Ext.define('Program', {
  extend: 'Ext.data.Model',
  fields: [
    { name: 'id', type: 'int' },
    'name',
    'type',
    { name: 'program_response_count', type: 'int', useNull: true },
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
    extraParams: {
      type: 'registrations'
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

/**
 * Extended classes
 */
Ext.define('ProgramGridPanel', {
  extend: 'Ext.grid.Panel',
  forceFit: true,
  height: 300,
  store: 'ProgramStore',
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
    dataIndex: 'program_response_count',
    text: 'Response Count',
    width: 100,
    renderer: function (value) {
      return value || 0;
    }
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
      getClass: function (val, meta, rec) {
        if (rec.get('program_response_count')) {
          this.tooltip = 'You cannot edit this program';
          return 'not-editable';
        } else {
          this.tooltip = 'Edit Program';
          return 'editable';
        }
      },
      handler: function (grid, rowIndex, colIndex) {
        var rec = grid.getStore().getAt(rowIndex),
          type = Ext.util.Inflector.singularize(grid.ownerCt.id);

        if (rec.get('program_response_count')) {
          Ext.Msg.alert('Can not edit', 'You can not edit a program with existing responses');
        } else {
          window.location = '/admin/programs/edit/' + type + '/' + rec.get('id');
        }

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
        var rec = grid.getStore().getAt(rowIndex),
          type = Ext.util.Inflector.singularize(grid.ownerCt.id);

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
  }],
  viewConfig: {
    deferEmptyText: false,
    emptyText: 'There are no programs at this time',
    loadMask: true,
    getRowClass: function (rec) {
      return rec.get('disabled') ? 'row-disabled' : 'row-active';
    }
  }
});

Ext.onReady(function () {
  Ext.QuickTips.init();

  var tabPanel, registrationsGrid, orientationsGrid;

  registrationsGrid = Ext.create('ProgramGridPanel', {
    title: 'Registrations',
    id: 'registrations'
  });

  orientationsGrid = Ext.create('ProgramGridPanel', {
    title: 'Orientations',
    id: 'orientations'
  });

  tabPanel = Ext.create('Ext.tab.Panel', {
    renderTo: 'programGrid',
    height: 300,
    title: 'Programs',
    items: [
      registrationsGrid
      //orientationsGrid
    ],
    listeners: {
      tabchange: function (panel, newCard, oldCard) {
        var programStore = Ext.data.StoreManager.lookup('ProgramStore');

        programStore.getProxy().extraParams.status = newCard.id;
      }
    }
  });
});
