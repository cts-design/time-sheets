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
    { name: 'order', type: 'int' },
    { name: 'default_passing_percentage', type: 'int' },
    { name: 'requires_user_assignment', type: 'int' },
    { name: 'disabled', type: 'int' },
    { name: 'assigned_user_count', type: 'int' }
  ]
});

/**
 * DataStores
 */
Ext.create('Ext.data.Store', {
  storeId: 'EcourseStore',
  autoLoad: true,
  autoSync: true,
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
  },
  remoteSort: false,
  sorters: [{
    property: 'order',
    direction: 'ASC'
  }]
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
    id: 'ecoursesTabPanel',
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
            window.location = '/admin/ecourses/create/customer';
          }
        }, {
          icon: '/img/icons/user_suit.png',
          text: 'Staff',
          handler: function () {
            window.location = '/admin/ecourses/create/staff';
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
        Ext.create('Ext.grid.plugin.RowEditing', {
          clicksToEdit: 2,
          listeners: {
            edit: function (editor, e) {
              var store = e.store,
                isFiltered = store.isFiltered,
                recordWasDisabled = !e.originalValues.disabled && e.newValues.disabled;

              if (recordWasDisabled && isFiltered) {
                store.filter('disabled', 0);
              }
            }
          }
        })
      ],
      title: 'Customer',
      columns: [{
        dataIndex: 'id',
        hidden: true,
        text: 'Id',
        width: 50
      }, {
        align: 'center',
        dataIndex: 'order',
        text: 'Order',
        width: 50,
        editor: {
          xtype: 'numberfield',
          allowBlank: false,
          minValue: 1
        }
      }, {
        dataIndex: 'name',
        text: 'Name',
        flex: 1,
        editor: {
          xtype: 'textfield',
          allowBlank: false
        }
      }, {
        xtype: 'numbercolumn',
        dataIndex: 'default_passing_percentage',
        format: '0%',
        text: 'Default Passing Percentage',
        width: 150,
        editor: {
          xtype: 'numberfield',
          allowBlank: false,
          minValue: 1,
          maxValue: 100
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
            var rec = grid.store.getAt(rowIndex);
            window.location = '/admin/ecourses/update/' + rec.get('id')
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
            var rec = grid.store.getAt(rowIndex);
            window.location = '/admin/ecourse_responses/index/' + rec.get('id')
          }
        }]
      }],
      listeners: {
        select: function (rm, rec) {
          tabPanel.down('#duplicateEcourseBtn').enable();
        },
        itemcontextmenu: function (view, rec, item, index, e) {
          var menu,
            items = [];

          e.preventDefault();

          items.push({
            icon: '/img/icons/report_modules',
            text: 'Add/Edit Ecourse Modules',
            handler: function () {
              window.location = '/admin/ecourse_modules/index/' + rec.get('id');
            }
          });

          if (rec.get('requires_user_assignment')) {
            items.push('-', {
              icon: '/img/icons/group_link.png',
              text: 'Assign Customers',
              handler: function () {
                window.location = '/admin/ecourse_users/assign_customers/' + rec.get('id');
              }
            }, {
              disabled: (rec.get('assigned_user_count') == 0),
              icon: '/img/icons/group_gear.png',
              text: 'View Assigned Customers',
              handler: function () {
                window.location = '/admin/ecourse_users/index/' + rec.get('id');
              }
            });
          }

          menu = Ext.create('Ext.menu.Menu', {
            items: items
          }).showAt(e.getXY());
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
      plugins: [
        Ext.create('Ext.grid.plugin.RowEditing', {
          clicksToEdit: 2,
          listeners: {
            edit: function (editor, e) {
              var store = e.store,
                isFiltered = store.isFiltered,
                recordWasDisabled = !e.originalValues.disabled && e.newValues.disabled;

              if (recordWasDisabled && isFiltered) {
                store.filter('disabled', 0);
              }
            }
          }
        })
      ],
      columns: [{
        dataIndex: 'id',
        hidden: true,
        text: 'Id',
        width: 50
      }, {
        align: 'center',
        dataIndex: 'order',
        text: 'Order',
        width: 50,
        editor: {
          xtype: 'numberfield',
          allowBlank: false,
          minValue: 1
        }
      }, {
        dataIndex: 'name',
        text: 'Name',
        flex: 1,
        editor: {
          xtype: 'textfield',
          allowBlank: false
        }
      }, {
        xtype: 'numbercolumn',
        dataIndex: 'default_passing_percentage',
        format: '0%',
        text: 'Default Passing Percentage',
        width: 150,
        editor: {
          xtype: 'numberfield',
          allowBlank: false,
          minValue: 1,
          maxValue: 100
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
            var rec = grid.store.getAt(rowIndex);
            window.location = '/admin/ecourses/update/' + rec.get('id')
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
            var rec = grid.store.getAt(rowIndex);
            window.location = '/admin/ecourse_responses/index/' + rec.get('id')
          }
        }]
      }],
      listeners: {
        select: function (rm, rec) {
          tabPanel.down('#duplicateEcourseBtn').enable();
        },
        itemcontextmenu: function (view, rec, item, index, e) {
          var menu,
            items = [];

          e.preventDefault();

          items.push({
            icon: '/img/icons/report_modules',
            text: 'Add/Edit Ecourse Modules',
            handler: function () {
              window.location = '/admin/ecourse_modules/index/' + rec.get('id');
            }
          });

          if (rec.get('requires_user_assignment')) {
            items.push('-', {
              icon: '/img/icons/group_link.png',
              text: 'Assign Staff',
              handler: function () {
                window.location = '/admin/ecourse_users/assign_staff/' + rec.get('id');
              }
            }, {
              disabled: (rec.get('assigned_user_count') == 0),
              icon: '/img/icons/group_gear.png',
              text: 'View Assigned Staff',
              handler: function () {
                window.location = '/admin/ecourse_users/index/' + rec.get('id');
              }
            });
          }

          menu = Ext.create('Ext.menu.Menu', {
            items: items
          }).showAt(e.getXY());
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
        var store = newCard.getStore();
          tabPanel = Ext.getCmp('ecoursesTabPanel'),
          duplicateEcourseBtn = tabPanel.down('#duplicateEcourseBtn');

        duplicateEcourseBtn.disable();
        store.getProxy().extraParams.ecourse_type = newCard.id;
        store.load();
      }
    }
  });
});
