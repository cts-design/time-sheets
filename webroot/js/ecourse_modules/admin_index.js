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
  proxy: {
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
      root: 'ecourse_modules'
    },
    writer: {
      encode: true,
      root: 'ecourse_modules',
      type: 'json',
      writeAllFields: false
    }
  }
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
      width: 50
    }, {
      dataIndex: 'name',
      flex: 1,
      text: 'Name'
    }, {
      dataIndex: 'media_name',
      flex: 1,
      text: 'Media Name'
    }, {
      dataIndex: 'media_type',
      flex: 1,
      text: 'Media Type',
      renderer: function (value) {
        switch (value) {
          case 'pdf':
          case 'url':
            return value.toUpperCase();
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
      width: 125
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
      itemdblclick: function (grid, record, item, index) {
        if (moduleForm.getCollapsed()) {
          moduleForm.expand();
        }

        moduleForm.loadRecord(record);
      }
    },
    viewConfig: {
      deferEmptyText: false,
      emptyText: 'There are no modules for this ecourse at this time',
      getRowClass: function (rec) {
        return rec.get('disabled') ? 'row-disabled' : 'row-active';
      },
      loadMask: true
    },
    dockedItems: [{
      xtype: 'toolbar',
      dock: 'top',
      items: [{
        icon: '/img/icons/add.png',
        text: 'New Module',
        handler: function () {
        var mediaUploadField = moduleForm.down('#mediaUpload'),
          mediaLocationField = moduleForm.down('#mediaLocation');

          if (moduleForm.getCollapsed()) {
            moduleForm.expand();
          }

          mediaUploadField.disable();
          mediaLocationField.disable();
          moduleForm.getForm().reset(true);
        }
      }, {
        disabled: true,
        icon: '/img/icons/edit.png',
        text: 'Edit Module'
      }, {
        disabled: true,
        icon: '/img/icons/delete.png',
        text: 'Delete Module'
      }]
    }, {
      xtype: 'pagingtoolbar',
      dock: 'bottom',
      width: 360,
      displayInfo: true
    }]
  });

  moduleForm = Ext.create('Ext.form.Panel', {
    bodyPadding: 10,
    collapsible: true,
    collapsed: true,
    height: 475,
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
      xtype: 'hiddenfield',
      name: 'order',
      value: 0
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
      width: 250
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

          if (newVal === 'url') {
            if (typeof oldVal !== 'undefined') {
              uploadField.disable();
              uploadField.allowBlank = true;
            }

            locationField.enable();
            locationField.allowBlank = false;
          } else if (newVal === 'pdf' || newVal === 'powerpoint') {
            if (oldVal === 'url') {
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
          { dbVal: 'pdf', stringVal: 'PDF Document' },
          { dbVal: 'powerpoint', stringVal: 'PowerPoint Presentation' },
          { dbVal: 'url', stringVal: 'URL' }
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

          if (form.isValid()) {
            formValues.order = store.count() + 1;

            if (uploadField.isDisabled()) {
                store.add(formValues);
                form.reset();
                formPanel.toggleCollapse();
            } else {
              form.submit({
                url: '/admin/ecourse_modules/upload_media',
                waitMsg: 'Uploading your media...',
                success: function (form, operation) {
                  if (operation.result.success) {
                    formValues.media_location = operation.result.location;
                    store.add(formValues);
                    form.reset();
                    formPanel.toggleCollapse();
                  }
                }
              });
            }
          }
        }
      }]
    }]
  });
});
