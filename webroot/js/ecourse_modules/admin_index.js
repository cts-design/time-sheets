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
  ],
  proxy: {
    type: 'ajax',
    api: {
      read: '/admin/ecourses/index'
    },
    reader: {
      type: 'json',
      root: 'ecourses'
    }
  }
});

Ext.define('EcourseModule', {
  extend: 'Ext.data.Model',
  fields: [
    { name: 'id', type: 'int' },
    { name: 'ecourse_id', type: 'int' },
    'name',
    'instructions',
    'media_name',
    'media_type',
    'media_location',
    { name: 'order', type: 'int' },
    { name: 'passing_percentage', type: 'int' }
  ]
});

/**
 * DataStores
 */
Ext.create('Ext.data.Store', {
  storeId: 'EcourseModuleStore',
  autoLoad: true,
  autoSync: true,
  model: 'EcourseModule',
  pageSize: 10,
  proxy: {
    directionParam: 'direction',
    simpleSortMode: true,
    type: 'ajax',
    api: {
      create: '/admin/ecourse_modules/create',
      read: '/admin/ecourse_modules/index',
      update: '/admin/ecourse_modules/update',
      destroy: '/admin/ecourse_modules/destroy'
    },
    extraParams: {
      ecourse_id: ecourse.id
    },
    reader: {
      type: 'json',
      root: 'ecourse_modules',
      totalProperty: 'totalCount'
    },
    writer: {
      encode: true,
      root: 'ecourse_modules',
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

Ext.onReady(function () {
  Ext.QuickTips.init();

  var modulesGrid,
    moduleForm,
    ecourseModuleStore = Ext.data.StoreManager.lookup('EcourseModuleStore');

  modulesGrid = Ext.create('Ext.grid.Panel', {
    collapsible: true,
    height: 250,
    forceFit: true,
    renderTo: 'ecourseModulesGrid',
    selModel: {
      allowDeselect: true,
      mode: 'SINGLE'
    },
    selType: 'rowmodel',
    store: 'EcourseModuleStore',
    title: (ecourse.name + ' Modules').capitalize(),
    columns: [{
      dataIndex: 'id',
      hidden: true,
      text: 'Id'
    }, {
      dataIndex: 'ecourse_id',
      hidden: true,
      text: 'Ecourse Id'
    }, {
      align: 'center',
      dataIndex: 'order',
      text: 'Order',
      width: 50,
      editor: {
        xtype: 'numberfield',
        allowBlank: false
      }
    }, {
      dataIndex: 'name',
      flex: 1,
      text: 'Name',
      editor: {
        xtype: 'textfield',
        allowBlank: false
      }
    }, {
      dataIndex: 'media_name',
      flex: 1,
      text: 'Media Name',
      editor: {
        xtype: 'textfield',
        allowBlank: false
      }
    }, {
      dataIndex: 'media_type',
      flex: 1,
      text: 'Media Type',
      renderer: function (value) {
        switch (value) {
          case 'flv':
            return 'Flash Video';
            break;

          case 'pdf':
            return 'PDF Document';
            break;

          case 'ppt':
            return 'PowerPoint Presentation';
            break;

          case 'url':
            return 'URL';
            break;

          case 'youtube':
            return 'Youtube';
            break;
          default:
            return value;
        }
      }
    }, {
      xtype: 'numbercolumn',
      dataIndex: 'passing_percentage',
      format: '0%',
      text: 'Passing Percentage',
      width: 125,
      editor: {
        xtype: 'numberfield',
        allowBlank: false
      }
    }, {
      xtype: 'actioncolumn',
      align: 'center',
      text: 'Quiz',
      width: 50,
      items: [{
        icon: '/img/icons/text_list_bullets.png',
        tooltop: 'Quizzes',
        handler: function (grid, rowIndex, colIndex) {
            var rec = grid.store.getAt(rowIndex);
            window.location = '/admin/ecourse_module_questions/index/' + rec.get('id')
        }
      }]
    }],
    listeners: {
      containerclick: function (grid, e) {
        grid.getSelectionModel().deselectAll();
      },
      deselect: function (grid, record, index) {
        Ext.getCmp('editModuleBtn').disable();
        Ext.getCmp('deleteModuleBtn').disable();
      },
      itemclick: function (grid, record, item, index) {
        Ext.getCmp('editModuleBtn').enable();
        Ext.getCmp('deleteModuleBtn').enable();
      },
      itemcontextmenu: function (view, rec, item, index, e) {
        var menu,
          items = [],
          media_type = rec.get('media_type'),
          media_icon;

        e.preventDefault();

        switch (media_type) {
          case 'flv':
            media_icon = '/img/icons/page_white_flash.png';
            break;

          case 'pdf':
            media_icon = '/img/icons/pdf.png';
            break;

          case 'ppt':
            media_icon = '/img/icons/page_white_powerpoint.png';
            break;

          case 'url':
            media_icon = '/img/icons/link.png';
            break;

          case 'youtube':
            media_icon = '/img/icons/link.png';
            break;
        }

        items.push({
          icon: media_icon,
          text: 'View Module Media',
          handler: function () {
            new Ext.Window({
              title : 'Media Preview',
              width : 633,
              height : 453,
              layout : 'fit',
              items : [{
                xtype : 'component',
                autoEl : {
                  tag : 'iframe',
                  src : '/admin/ecourse_modules/view_media/' + rec.get('id')
                }
              }]
            }).show();
          }
        });

        menu = Ext.create('Ext.menu.Menu', {
          items: items
        }).showAt(e.getXY());
      }
    },
    plugins: [
      Ext.create('Ext.grid.plugin.RowEditing', {
        clicksToEdit: 2,
        listeners: {
          edit: function (editor, e) {
            if (e.originalValues.order !== e.newValues.order) {
              e.store.sort('order', 'ASC');
            }
          }
        }
      })
    ],
    viewConfig: {
      deferEmptyText: false,
      emptyText: 'There are no modules for this ecourse at this time',
      getRowClass: function (rec) {
        return rec.get('disabled') ? 'row-disabled' : 'row-active';
      },
      listeners: {
        drop: function (node, data, overModel, dropPosition) {
          var store = overModel.store,
            gridEl = modulesGrid.getEl(),
            selectedRecord = data.records[0],
            overModelOrder = overModel.get('order'),
            parseDrop,
            batch = new Ext.data.Batch(),
            i;

          gridEl.mask('Reordering modules...');

          // Reorder the selected field and it's overModel
          // based on the drop position
          parseDrop = (function () {
            return {
              before: function () {
                selectedRecord.set('order', overModelOrder);
                overModel.set('order', (overModelOrder + 1));
              },
              after: function () {
                selectedRecord.set('order', overModelOrder);
                overModel.set('order', (overModelOrder - 1));
              }
            };
          }());
          parseDrop[dropPosition] && parseDrop[dropPosition]();

          store.sort('order', 'ASC');

          i = 1;
          store.each(function (record) {
            if (record.get('order') !== i) {
              record.set('order', i);
            }
            i++;
          });

          gridEl.unmask();
        }
      },
      loadMask: true,
      plugins: {
        ptype: 'gridviewdragdrop',
        dragText: 'Drag and drop to re-order',
      }
    },
    dockedItems: [{
      xtype: 'toolbar',
      dock: 'top',
      items: [{
        icon: '/img/icons/add.png',
        text: 'New Module',
        handler: function () {
          var mediaUploadField = moduleForm.down('#mediaUpload'),
            mediaLocationField = moduleForm.down('#mediaLocation'),
            orderField = moduleForm.down('#orderField'),
            ecourseModuleStore = modulesGrid.store;

          modulesGrid.getSelectionModel().deselectAll();

          mediaUploadField.disable();
          mediaLocationField.disable();
          moduleForm.getForm().reset(true);

          orderField.setValue(ecourseModuleStore.count() + 1);
          mediaUploadField.allowBlank = false;

          if (moduleForm.getCollapsed()) {
            moduleForm.expand();
          }
        }
      }, {
        disabled: true,
        icon: '/img/icons/edit.png',
        id: 'editModuleBtn',
        text: 'Edit Module',
        handler: function () {
          var record = modulesGrid.getSelectionModel().getSelection()[0];

          if (moduleForm.getCollapsed()) {
            moduleForm.expand();
          }

          moduleForm.getForm().reset(true);
          moduleForm.loadRecord(record);
          moduleForm.down('#mediaUpload').allowBlank = true; // On an edit we don't want to require another upload
        }
      }, {
        disabled: true,
        icon: '/img/icons/delete.png',
        id: 'deleteModuleBtn',
        text: 'Delete Module',
        handler: function () {
          var gridPanel = this.up('grid'),
            store = gridPanel.store,
            formPanel = moduleForm,
            form = formPanel.getForm(),
            selectedRecord = modulesGrid.getSelectionModel().getSelection()[0];

          if (form.getRecord() === selectedRecord) {
            form.reset(true);
          }

          store.remove(selectedRecord);
        }
      }]
    }, {
      xtype: 'pagingtoolbar',
      displayInfo: true,
      dock: 'bottom',
      store: 'EcourseModuleStore',
      width: 360
    }]
  });

  moduleForm = Ext.create('Ext.form.Panel', {
    bodyPadding: 10,
    collapsible: true,
    collapsed: true,
    height: 500,
    renderTo: 'ecourseModuleForm',
    title: 'New Module Form',
    fieldDefaults: {
      width: 400
    },
    items: [{
      border: 0,
      html: '<h1>Ecourse Module Details</h1>',
      margin: '0 0 10'
    }, {
      xtype: 'hiddenfield',
      name: 'ecourse_id',
      value: ecourse.id
    }, {
      xtype: 'numberfield',
      allowBlank: false,
      fieldLabel: 'Order',
      id: 'orderField',
      minValue: 1,
      name: 'order',
      width: 175
    }, {
      xtype: 'textfield',
      allowBlank: false,
      fieldLabel: 'Name',
      name: 'name'
    }, {
      xtype: 'numberfield',
      allowBlank: false,
      fieldLabel: 'Passing Percentage',
      maxValue: 100,
      minValue: 1,
      name: 'passing_percentage',
      value: ecourse.default_passing_percentage,
      width: 175
    }, {
      xtype: 'htmleditor',
      allowBlank: false,
      anchor: '100%',
      fieldLabel: 'Instructions',
      height: 150,
      name: 'instructions',
      style: 'background-color: white;'
    }, {
      border: 0,
      html: '<h1>Ecourse Module Media Details</h1>',
      margin: '0 0 10'
    }, {
      xtype: 'textfield',
      allowBlank: false,
      fieldLabel: 'Media Name',
      name: 'media_name'
    }, {
      xtype: 'combo',
      allowBlank: false,
      displayField: 'stringVal',
      fieldLabel: 'Media Type',
      listeners: {
        change: function (field, newVal, oldVal) {
          var uploadField = Ext.getCmp('mediaUpload'),
            locationField = Ext.getCmp('mediaLocation');

          if (newVal === 'url' || newVal === 'youtube') {
            if (typeof oldVal !== 'undefined') {
              uploadField.disable();
              uploadField.allowBlank = true;
            }

            locationField.enable();
            locationField.allowBlank = false;
          } else if (newVal === 'pdf' || newVal === 'ppt' || newVal === 'flv' || newVal === 'swf') {
            if (oldVal === 'url' || oldVal === 'youtube') {
              locationField.disable();
              locationField.allowBlank = true;
            }

            uploadField.enable();
            uploadField.allowBlank = false;
          }
        }
      },
      name: 'media_type',
      store: Ext.create('Ext.data.Store', {
        fields: ['dbVal', 'stringVal'],
        data: [
          { dbVal: 'flv', stringVal: 'Flash Video' },
          { dbVal: 'pdf', stringVal: 'PDF Document' },
          { dbVal: 'ppt', stringVal: 'PowerPoint Presentation' },
          { dbVal: 'swf', stringVal: 'Shockwave Video' },
          { dbVal: 'url', stringVal: 'URL' },
          { dbVal: 'youtube', stringVal: 'Youtube' }
        ]
      }),
      queryMode: 'local',
      valueField: 'dbVal',
      width: 300
    }, {
      xtype: 'filefield',
      disabled: true,
      fieldLabel: 'Media Upload',
      id: 'mediaUpload',
      name: 'media'
    }, {
      xtype: 'textfield',
      disabled: true,
      fieldLabel: 'Media Location',
      id: 'mediaLocation',
      name: 'media_location'
    }],
    dockedItems: [{
      xtype: 'toolbar',
      dock: 'bottom',
      items: [{
        xtype: 'tbfill'
      }, {
        formBind: true,
        text: 'Save Module',
        handler: function () {
          var formPanel = this.up('form'),
            form = formPanel.getForm(),
            store = Ext.data.StoreManager.lookup('EcourseModuleStore'),
            formValues = form.getValues(),
            uploadField = formPanel.down('filefield');

          // Is this a new record?
          if (typeof form.getRecord() === 'undefined') {
            if (formValues.media_type === 'url') {
              if (!formValues.media_location.match(/http:\/\//gi)) {
                formValues.media_location = 'http://' + formValues.media_location;
              }

              store.add(formValues);
              form.reset();
              formPanel.collapse();
            } else {
              form.submit({
                url: '/admin/ecourse_modules/upload_media',
                waitMsg: 'Uploading your media...',
                success: function(form, operation) {
                  if (operation.result.success) {
                    formValues.media_location = operation.result.location;
                    store.add(formValues);
                    form.reset();
                    formPanel.collapse();
                  }
                }
              });
            }
          } else {
            if (!uploadField.isDisabled() && uploadField.getValue()) {
              form.submit({
                url: '/admin/ecourse_modules/upload_media',
                waitMsg: 'Uploading your media...',
                success: function(form, operation) {
                  var record = form.getRecord();

                  if (operation.result.success) {
                    record.set('media_location', operation.result.location);
                    form.updateRecord();
                    form.reset(true);
                    formPanel.collapse();
                  }
                }
              });
            } else {
              form.updateRecord();
              form.reset(true);
              formPanel.collapse();
            }
          }
        }
      }]
    }]
  });
});
