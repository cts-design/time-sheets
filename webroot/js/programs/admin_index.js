var productionURL = function() {
  var url;

  if (Ext.isIE) {
    url  = window.location.protocol;
    url += '//' + window.location.host;
  } else {
    url = window.location.origin;
  }

  return url;
};

/**
 * Models
 */
Ext.define('Program', {
  extend: 'Ext.data.Model',
  fields: [
    { name: 'id', type: 'int' },
    'name',
    'type',
    { name: 'show_in_dash', type: 'int' },
    { name: 'program_response_count', type: 'int', useNull: true },
    { name: 'disabled', type: 'int' },
    { name: 'in_test', type: 'int' }
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
      program_type: 'registration'
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
  alias: 'widget.programgridpanel',
  extend: 'Ext.grid.Panel',
  forceFit: true,
  height: 300,
  store: 'ProgramStore',
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
  }, {
    text: 'Orientation',
    handler: function () {
      window.location = '/admin/programs/create_orientation';
    }
  }];

  if (roleId === 2) {
    menuItems.push({
      text: 'Enrollment',
      handler: function () {
        window.location = '/admin/programs/create_enrollment';
      }
    });

    menuItems.push({
      text: 'Esign',
      handler: function () {
        window.location = '/admin/programs/create_esign';
      }
    });
  }

  var tabPanel = Ext.create('Ext.tab.Panel', {
    renderTo: 'programGrid',
    stateful: true,
    stateEvents: ['tabchange'],
    stateId: 'programsIndexTabs',
    title: 'Programs',
    dockedItems: [{
      xtype: 'toolbar',
      dock: 'top',
      items: [{
        text: 'New Program',
        menu: menuItems
      }, {
        disabled: true,
        icon: '/img/icons/copy.png',
        id: 'duplicateProgramBtn',
        text: 'Duplicate Program',
        handler: function () {
          var tabPanel = this.up('tabpanel'),
            gridPanel = tabPanel.activeTab,
            selectedRecord = gridPanel.getSelectionModel().getSelection()[0],
            programId = selectedRecord.get('id'),
            programType = selectedRecord.get('type'),
            progressMsg;

          progressMsg = Ext.Msg.wait(
            'Please wait while we duplicate the program',
            'Duplicating Program', {
              interval: 150
            });
          Ext.Ajax.request({
            url: '/admin/programs/duplicate',
            params: {
              program_id: programId,
              program_type: programType
            },
            success: function (res) {
              var task = new Ext.util.DelayedTask(function () {
                progressMsg.close();
              });

              task.delay(1240);
            }
          });
        }
      }]
    }],
    applyState: function (state) {
      if (typeof state.activeTab !== 'undefined') {
        this.setActiveTab(state.activeTab);
      }
    },
    getState: function () {
      return { activeTab: this.getActiveTab().id };
    },
    items: [{
      xtype: 'programgridpanel',
      id: 'registration',
      title: 'Registrations',
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
              this.tooltip = 'Editing a live program is limited';
            } else {
              this.tooltip = 'Edit Program';
            }

            return 'editable';
          },
          handler: function (grid, rowIndex, colIndex) {
            var rec = grid.getStore().getAt(rowIndex);

            window.location = '/admin/programs/edit/registration/' + rec.get('id');
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
            var rec = grid.getStore().getAt(rowIndex),
              type = Ext.util.Inflector.singularize(grid.ownerCt.id);

            window.location = '/admin/program_responses/index/' + rec.get('id');
          }
        }],
      }],
      listeners: {
        itemcontextmenu: function (view, rec, item, index, e) {
          var menu,
            items = [],
            progressMsg;

          e.preventDefault();

          items.push({
              icon: '/img/icons/eye.png',
              text: 'Show Production Url',
              handler: function () {
                var msg;

                msg = 'Please copy the following url.<br /><br />';
                msg += productionURL() + '/programs/registration/' + rec.data.id;
                msg += '<br /><br />';

                Ext.Msg.alert('Production Url', msg);
              }
          });

          if (rec.data.in_test) {
            items.push({ xtype: 'menuseparator' });
            items.push({
              icon: '/img/icons/publish.png',
              text: 'Set Program Live',
              handler: function () {
                rec.set({
                  in_test: 0,
                  disabled: 0
                });
                rec.save();
              }
            });
            items.push({
              icon: '/img/icons/delete.png',
              text: 'Purge Test Data',
              handler: function () {
                progressMsg = Ext.Msg.wait(
                  'Please wait while we purge your test data',
                  'Purging Test Data', {
                    interval: 150
                  });
                Ext.Ajax.request({
                  url: '/admin/programs/purge_test_data',
                  params: {
                    program_id: rec.data.id
                  },
                  success: function (res) {
                    var task = new Ext.util.DelayedTask(function () {
                      progressMsg.close();
                    });

                    task.delay(1240);
                  }
                });
              }
            });
          }

          if (rec.data.show_in_dash) {
            showInDashText = 'Remove From Customer Dashboard';
          } else {
            showInDashText = 'Show In Customer Dashboard';
          }

          items.push({ xtype: 'menuseparator' });
          items.push({
              icon: '/img/icons/survey.png',
              text: showInDashText,
              handler: function () {
                var showInDash = rec.get('show_in_dash');
                if (showInDash) {
                  rec.set({
                    show_in_dash: 0
                  });
                } else {
                  rec.set({
                    show_in_dash: 1
                  });
                }
                rec.save();
              }
          });

          menu = Ext.create('Ext.menu.Menu', {
            items: items
          });

          menu.showAt(e.getXY());
        },
        select: function (rm, rec) {
          var duplicateBtn = Ext.getCmp('duplicateProgramBtn');
          duplicateBtn.enable();
        }
      },
      viewConfig: {
        deferEmptyText: false,
        emptyText: 'There are no programs at this time',
        getRowClass: function (rec) {
          return rec.get('disabled') ? 'row-disabled' : 'row-active';
        },
        loadMask: true
      }
    }, {
      xtype: 'programgridpanel',
      title: 'Orientations',
      id: 'orientation',
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
              this.tooltip = 'Editing a live program is limited';
            } else {
              this.tooltip = 'Edit Program';
            }

            return 'editable';
          },
          handler: function (grid, rowIndex, colIndex) {
            var rec = grid.getStore().getAt(rowIndex);

            window.location = '/admin/programs/edit/orientation/' + rec.get('id');
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
        }]
      }],
      listeners: {
        itemcontextmenu: function (view, rec, item, index, e) {
          var menu,
            items = [],
            progressMsg;

          e.preventDefault();

          items.push({
              icon: '/img/icons/eye.png',
              text: 'Show Production Url',
              handler: function () {
                var msg;

                msg = 'Please copy the following url.<br /><br />';
                msg += productionURL() + '/programs/orientation/' + rec.data.id;
                msg += '<br /><br />';

                Ext.Msg.alert('Production Url', msg);
              }
          });

          if (rec.data.in_test) {
            items.push({ xtype: 'menuseparator' });
            items.push({
              icon: '/img/icons/publish.png',
              text: 'Set Program Live',
              handler: function () {
                rec.set({
                  in_test: 0,
                  disabled: 0
                });
                rec.save();
              }
            });
            items.push({
              icon: '/img/icons/delete.png',
              text: 'Purge Test Data',
              handler: function () {
                progressMsg = Ext.Msg.wait(
                  'Please wait while we purge your test data',
                  'Purging Test Data', {
                    interval: 150
                  });
                Ext.Ajax.request({
                  url: '/admin/programs/purge_test_data',
                  params: {
                    program_id: rec.data.id
                  },
                  success: function (res) {
                    var task = new Ext.util.DelayedTask(function () {
                      progressMsg.close();
                    });

                    task.delay(1240);
                  }
                });
              }
            });
          }

          if (rec.data.show_in_dash) {
            showInDashText = 'Remove From Customer Dashboard';
          } else {
            showInDashText = 'Show In Customer Dashboard';
          }

          items.push({ xtype: 'menuseparator' });
          items.push({
              icon: '/img/icons/survey.png',
              text: showInDashText,
              handler: function () {
                var showInDash = rec.get('show_in_dash');
                if (showInDash) {
                  rec.set({
                    show_in_dash: 0
                  });
                } else {
                  rec.set({
                    show_in_dash: 1
                  });
                }
                rec.save();
              }
          });

          menu = Ext.create('Ext.menu.Menu', {
            items: items
          });

          menu.showAt(e.getXY());
        },
        select: function (rm, rec) {
          var duplicateBtn = Ext.getCmp('duplicateProgramBtn');
          duplicateBtn.enable();
        }
      },
      viewConfig: {
        deferEmptyText: false,
        emptyText: 'There are no orientations in the system',
        getRowClass: function (rec) {
          return rec.get('disabled') ? 'row-disabled' : 'row-active';
        },
        loadMask: true
      }
    },{
      xtype: 'programgridpanel',
      title: 'Enrollments',
      id: 'enrollment',
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
              this.tooltip = 'Editing a live program is limited';
            } else {
              this.tooltip = 'Edit Program';
            }

            return 'editable';
          },
          handler: function (grid, rowIndex, colIndex) {
            var rec = grid.getStore().getAt(rowIndex);

            window.location = '/admin/programs/edit/enrollment/' + rec.get('id');
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
        }]
      }],
      listeners: {
        itemcontextmenu: function (view, rec, item, index, e) {
          var menu,
            items = [],
            progressMsg;

          e.preventDefault();

          items.push({
              icon: '/img/icons/eye.png',
              text: 'Show Production Url',
              handler: function () {
                var msg;

                msg = 'Please copy the following url.<br /><br />';
                msg += productionURL() + '/programs/enrollment/' + rec.data.id;
                msg += '<br /><br />';

                Ext.Msg.alert('Production Url', msg);
              }
          });

          if (rec.data.in_test) {
            items.push({ xtype: 'menuseparator' });
            items.push({
              icon: '/img/icons/publish.png',
              text: 'Set Program Live',
              handler: function () {
                rec.set({
                  in_test: 0,
                  disabled: 0
                });
                rec.save();
              }
            });

            if (roleId === 2) {
              items.push({
                icon: '/img/icons/delete.png',
                text: 'Purge Test Data',
                handler: function () {
                  progressMsg = Ext.Msg.wait(
                    'Please wait while we purge your test data',
                    'Purging Test Data', {
                      interval: 150
                    });
                  Ext.Ajax.request({
                    url: '/admin/programs/purge_test_data',
                    params: {
                      program_id: rec.data.id
                    },
                    success: function (res) {
                      var task = new Ext.util.DelayedTask(function () {
                        progressMsg.close();
                      });

                      task.delay(1240);
                    }
                  });
                }
              });
            }
          }

          if (rec.data.show_in_dash) {
            showInDashText = 'Remove From Customer Dashboard';
          } else {
            showInDashText = 'Show In Customer Dashboard';
          }

          items.push({ xtype: 'menuseparator' });
          items.push({
              icon: '/img/icons/survey.png',
              text: showInDashText,
              handler: function () {
                var showInDash = rec.get('show_in_dash');
                if (showInDash) {
                  rec.set({
                    show_in_dash: 0
                  });
                } else {
                  rec.set({
                    show_in_dash: 1
                  });
                }
                rec.save();
              }
          });

          menu = Ext.create('Ext.menu.Menu', {
            items: items
          });

          menu.showAt(e.getXY());
        },
        select: function (rm, rec) {
          var duplicateBtn = Ext.getCmp('duplicateProgramBtn');
          duplicateBtn.enable();
        }
      },
      viewConfig: {
        deferEmptyText: false,
        emptyText: 'There are no enrollments in the system',
        getRowClass: function (rec) {
          return rec.get('disabled') ? 'row-disabled' : 'row-active';
        },
        loadMask: true
      }
    },{
      xtype: 'programgridpanel',
      title: 'E-Sign',
      id: 'esign',
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
              this.tooltip = 'Editing a live program is limited';
            } else {
              this.tooltip = 'Edit Program';
            }

            return 'editable';
          },
          handler: function (grid, rowIndex, colIndex) {
            var rec = grid.getStore().getAt(rowIndex);

            window.location = '/admin/programs/edit/esign/' + rec.get('id');
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
        }]
      }],
      listeners: {
        itemcontextmenu: function (view, rec, item, index, e) {
          var menu,
            items = [],
            progressMsg,
            showInDashText;

          e.preventDefault();

          items.push({
              icon: '/img/icons/eye.png',
              text: 'Show Production Url',
              handler: function () {
                var msg;

                msg = 'Please copy the following url.<br /><br />';
                msg += productionURL() + '/programs/esign/' + rec.data.id;
                msg += '<br /><br />';

                Ext.Msg.alert('Production Url', msg);
              }
          });

          if (rec.data.in_test) {
            items.push({ xtype: 'menuseparator' });
            items.push({
              icon: '/img/icons/publish.png',
              text: 'Set Program Live',
              handler: function () {
                rec.set({
                  in_test: 0,
                  disabled: 0
                });
                rec.save();
              }
            });
            items.push({
              icon: '/img/icons/delete.png',
              text: 'Purge Test Data',
              handler: function () {
                progressMsg = Ext.Msg.wait(
                  'Please wait while we purge your test data',
                  'Purging Test Data', {
                    interval: 150
                  });
                Ext.Ajax.request({
                  url: '/admin/programs/purge_test_data',
                  params: {
                    program_id: rec.data.id
                  },
                  success: function (res) {
                    var task = new Ext.util.DelayedTask(function () {
                      progressMsg.close();
                    });

                    task.delay(1240);
                  }
                });
              }
            });
          }

          if (rec.data.show_in_dash) {
            showInDashText = 'Remove From Customer Dashboard';
          } else {
            showInDashText = 'Show In Customer Dashboard';
          }

          items.push({ xtype: 'menuseparator' });
          items.push({
              icon: '/img/icons/survey.png',
              text: showInDashText,
              handler: function () {
                var showInDash = rec.get('show_in_dash');
                if (showInDash) {
                  rec.set({
                    show_in_dash: 0
                  });
                } else {
                  rec.set({
                    show_in_dash: 1
                  });
                }
                rec.save();
              }
          });

          menu = Ext.create('Ext.menu.Menu', {
            items: items
          });

          menu.showAt(e.getXY());
        },
        select: function (rm, rec) {
          var duplicateBtn = Ext.getCmp('duplicateProgramBtn');
          duplicateBtn.enable();
        }
      },
      viewConfig: {
        deferEmptyText: false,
        emptyText: 'There are no esign enrollments in the system',
        getRowClass: function (rec) {
          return rec.get('disabled') ? 'row-disabled' : 'row-active';
        },
        loadMask: true
      }
    }],
    listeners: {
      tabchange: function (panel, newCard) {
        var store = newCard.getStore(),
          duplicateBtn = Ext.getCmp('duplicateProgramBtn');

        duplicateBtn.disable();
        store.getProxy().extraParams.program_type = newCard.id;
        store.load();
      }
    }
  });
});
