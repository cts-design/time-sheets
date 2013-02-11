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
    { name: 'requires_user_assignment', type: 'int' },
    { name: 'disabled', type: 'int' },
  ]
});

/**
 * DataStores
 */
Ext.create('Ext.data.Store', {
  storeId: 'EcourseStore',
  autoLoad: true,
  model: 'Ecourse',
  filters: [{
    property: 'disabled',
    value: 0
  }],
  proxy: {
    type: 'ajax',
    api: {
      read: '/admin/ecourses/index',
      update: '/admin/ecourses/update'
    },
    extraParams: {
      ecourse_type: 'customer'
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

  var tabPanel = Ext.create('Ext.tab.Panel', {
    renderTo: 'ecoursesGrid',
    height: 400,
    stateful: true,
    stateEvents: ['tabchange'],
    stateId: 'ecourseIndexTabs',
    title: 'Ecourses',
    dockedItems: [{
      xtype: 'toolbar',
      dock: 'top',
      items: [{
        icon: '/img/icons/report_add.png',
        id: 'newEcourseBtn',
        text: 'New Ecourse',
        menu: [{
          icon: '/img/icons/user.png',
          text: 'Customer',
          handler: function () {
            window.location = '/admin/programs/create_registration';
          }
        }, {
          icon: '/img/icons/user_suit.png',
          text: 'Staff',
          handler: function () {
            window.location = '/admin/programs/create_registration';
          }
        }]
      }, {
        disabled: true,
        icon: '/img/icons/copy.png',
        id: 'duplicateEcourseBtn',
        text: 'Duplicate Ecourse',
        handler: function () {
          var tp = this.up('tabpanel'),
            grid = tp.activeTab,
            selectedRecord = grid.getSelectionModel().getSelection()[0];

          alert("Duplicate program id: " + selectedRecord.get('id'));
        }
      }, '->', {
        enableToggle: true,
        icon: '/img/icons/archive.png',
        id: 'showArchivedEcourses',
        text: 'Show Archived Ecourses',
        listeners: {
          toggle: function (btn, pressed) {
            var store = this.up('tabpanel').activeTab.store,
              isFiltered = store.isFiltered();

            if (pressed === true) {
              store.clearFilter();
            } else {
              store.filter('disabled', 0);
            }
          }
        }
      }]
    }],
    applyState: function (state) {
      if (typeof state.activeTab !== 'undefined') {
        this.setActiveTab(state.activeTab);
      }
    },
    getState: function () {
      return { activeTab: this.activeTab.id };
    },
    items: [{
      xtype: 'ecoursegridpanel',
      id: 'customer',
      plugins: [
        Ext.create('Ext.grid.plugin.CellEditing', {
          clicksToEdit: 2
        })
      ],
      title: 'Customer',
      columns: [{
        dataIndex: 'id',
        hidden: true,
        text: 'Id',
        width: 50
      }, {
        dataIndex: 'name',
        text: 'Name',
        flex: 1,
        editor: {
          xtype: 'textfield',
          allowBlank: false
        }
      }, {
        dataIndex: 'default_passing_percentage',
        text: 'Default Passing Percentage',
        width: 150,
        editor: {
          xtype: 'numberfield',
          allowBlank: false,
          minValue: 1,
          maxValue: 100
        },
        renderer: function (val) {
          return val + '%';
        }
      }, {
        align: 'center',
        dataIndex: 'requires_user_assignment',
        text: 'Requires User Assignment?',
        width: 175,
        editor: {
          xtype: 'combo',
          allowBlank: false,
          displayField: 'stringVal',
          store: Ext.create('Ext.data.Store', {
            fields: ['intVal', 'stringVal'],
            data: [{
              intVal: 1, stringVal: 'Yes',
            }, {
              intVal: 0, stringVal: 'No',
            }]
          }),
          queryMode: 'local',
          valueField: 'intVal'
        },
        renderer: function (value) {
          switch (value) {
            case 0:
              return "No";
              break;

            case 1:
              return "Yes";
              break;
          }
        }
      }, {
        align: 'center',
        dataIndex: 'disabled',
        text: 'Active',
        width: 75,
        editor: {
          xtype: 'combo',
          allowBlank: false,
          displayField: 'stringVal',
          store: Ext.create('Ext.data.Store', {
            fields: ['intVal', 'stringVal'],
            data: [{
              intVal: 1, stringVal: 'Archived',
            }, {
              intVal: 0, stringVal: 'Active',
            }]
          }),
          queryMode: 'local',
          valueField: 'intVal'
        },
        renderer: function (value) {
          switch (value) {
            case 0:
              return "Active";
              break;

            case 1:
              return "Archived";
              break;
          }
        }
      }, {
        xtype: 'actioncolumn',
        align: 'center',
        header: 'Edit',
        width: 50,
        items: [{
          icon: '/img/icons/report_edit.png',
          tooltip: 'Edit Ecourse',
          handler: function (grid, rowIndex, colIndex) {
            alert('go to edit');
          }
        }]
      }, {
        xtype: 'actioncolumn',
        align: 'center',
        header: 'Modules',
        width: 75,
        items: [{
          icon: '/img/icons/report_modules.png',
          tooltip: 'Add Modules',
          handler: function (grid, rowIndex, colIndex) {
            alert('go to modules');
          }
        }]
      }, {
        xtype: 'actioncolumn',
        align: 'center',
        header: 'View Responses',
        width: 100,
        items: [{
          icon: '/img/icons/file-cab.png',
          tooltip: 'View Responses',
          handler: function (grid, rowIndex, colIndex) {
            alert('go to responses');
          }
        }]
      }],
      listeners: {
        select: function (rm, rec) {
          tabPanel.down('#duplicateEcourseBtn').enable();
        }
      },
      viewConfig: {
        deferEmptyText: false,
        emptyText: 'There are no customer ecourses at this time',
        getRowClass: function (rec) {
          return rec.get('disabled') ? 'row-disabled' : 'row-active';
        },
        loadMask: true
      }
    }, {
      xtype: 'ecoursegridpanel',
      id: 'staff',
      title: 'Staff',
      columns: [{
        dataIndex: 'id',
        hidden: true,
        text: 'Id',
        width: 50
      }, {
        dataIndex: 'name',
        text: 'Name',
        flex: 1,
        editor: {
          xtype: 'textfield',
          allowBlank: false
        }
      }, {
        dataIndex: 'default_passing_percentage',
        text: 'Default Passing Percentage',
        width: 150,
        editor: {
          xtype: 'numberfield',
          allowBlank: false,
          minValue: 1,
          maxValue: 100
        },
        renderer: function (val) {
          return val + '%';
        }
      }, {
        align: 'center',
        dataIndex: 'requires_user_assignment',
        text: 'Requires User Assignment?',
        width: 175,
        editor: {
          xtype: 'combo',
          allowBlank: false,
          displayField: 'stringVal',
          store: Ext.create('Ext.data.Store', {
            fields: ['intVal', 'stringVal'],
            data: [{
              intVal: 1, stringVal: 'Yes',
            }, {
              intVal: 0, stringVal: 'No',
            }]
          }),
          queryMode: 'local',
          valueField: 'intVal'
        },
        renderer: function (value) {
          switch (value) {
            case 0:
              return "No";
              break;

            case 1:
              return "Yes";
              break;
          }
        }
      }, {
        align: 'center',
        dataIndex: 'disabled',
        text: 'Active',
        width: 75,
        editor: {
          xtype: 'combo',
          allowBlank: false,
          displayField: 'stringVal',
          store: Ext.create('Ext.data.Store', {
            fields: ['intVal', 'stringVal'],
            data: [{
              intVal: 1, stringVal: 'Archived',
            }, {
              intVal: 0, stringVal: 'Active',
            }]
          }),
          queryMode: 'local',
          valueField: 'intVal'
        },
        renderer: function (value) {
          switch (value) {
            case 0:
              return "Active";
              break;

            case 1:
              return "Archived";
              break;
          }
        }
      }, {
        xtype: 'actioncolumn',
        align: 'center',
        header: 'Edit',
        width: 50,
        items: [{
          icon: '/img/icons/report_edit.png',
          tooltip: 'Edit Ecourse',
          handler: function (grid, rowIndex, colIndex) {
            alert('go to edit');
          }
        }]
      }, {
        xtype: 'actioncolumn',
        align: 'center',
        header: 'Modules',
        width: 75,
        items: [{
          icon: '/img/icons/report_modules.png',
          tooltip: 'Add Modules',
          handler: function (grid, rowIndex, colIndex) {
            alert('go to modules');
          }
        }]
      }, {
        xtype: 'actioncolumn',
        align: 'center',
        header: 'View Responses',
        width: 100,
        items: [{
          icon: '/img/icons/file-cab.png',
          tooltip: 'View Responses',
          handler: function (grid, rowIndex, colIndex) {
            alert('go to responses');
          }
        }]
      }],
      listeners: {
        select: function (rm, rec) {
          tabPanel.down('#duplicateEcourseBtn').enable();
        }
      },
      viewConfig: {
        deferEmptyText: false,
        emptyText: 'There are no staff ecourses at this time',
        getRowClass: function (rec) {
          return rec.get('disabled') ? 'row-disabled' : 'row-active';
        },
        loadMask: true
      }
    }],
    listeners: {
      tabchange: function (panel, newCard) {
        var store = newCard.getStore(),
          duplicateEcourseBtn = tabPanel.down('#duplicateEcourseBtn');

        duplicateEcourseBtn.disable();
        store.getProxy().extraParams.ecourse_type = newCard.id;
        store.load();
      }
    }
  });
});
