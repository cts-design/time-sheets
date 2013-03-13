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
    { name: 'certificate_cat_1', type: 'int' },
    { name: 'certificate_cat_2', type: 'int' },
    { name: 'certificate_cat_3', type: 'int' },
    { name: 'requires_user_assignment', type: 'int' }
  ],
  proxy: {
    type: 'ajax',
    api: {
      create: '/admin/ecourses/create',
      read: '/admin/ecourses/read',
      update: '/admin/ecourses/update'
    },
    writer: {
      encode: true,
      root: 'ecourses',
      type: 'json',
      writeAllFields: false
    }
  }
});

Ext.define('DocumentFilingCategory', {
  extend: 'Ext.data.Model',
  fields: [
    { name: 'id', type: 'int' },
    { name: 'name' },
    { name: 'secure'},
    {
      name : 'img',
      convert: function(value, record){
        var img = '',
          secure = record.get('secure');

        if(secure) {
          img = '<img src="/img/icons/lock.png" />&nbsp';
        }
        return img;
      }
  }]
});

/**
 * DataStores
 */
var catProxy = Ext.create('Ext.data.proxy.Ajax', {
  url: '/admin/document_filing_categories/get_cats',
  reader: {
    type: 'json',
    root: 'cats'
  },
  extraParams: {
    parentId: 'parent'
  }
});

Ext.create('Ext.data.Store', {
  storeId: 'Cat1Store',
  model: 'DocumentFilingCategory',
  proxy: catProxy,
  autoLoad: true
});

Ext.create('Ext.data.Store', {
  storeId: 'Cat2Store',
  model: 'DocumentFilingCategory',
  proxy: catProxy,
  listeners: {
    load: function(store, records, successful, operation, eOpts) {
      if(records[0]) {
        Ext.getCmp('cat2Name').enable();
      }
    }
  }
});

Ext.create('Ext.data.Store', {
  storeId: 'Cat3Store',
  model: 'DocumentFilingCategory',
  proxy: catProxy,
  listeners: {
    load: function(store, records, successful, operation, eOpts) {
      if(records[0]) {
        Ext.getCmp('cat3Name').enable();
      }
    }
  }
});

Ext.onReady(function () {
  Ext.QuickTips.init();

  var EcourseModel = Ext.ModelManager.getModel('Ecourse'),
    Cat1Store = Ext.data.StoreMgr.lookup('Cat1Store'),
    Cat2Store = Ext.data.StoreMgr.lookup('Cat2Store'),
    Cat3Store = Ext.data.StoreMgr.lookup('Cat3Store'),
    formPanel;

  formPanel = Ext.create('Ext.form.Panel', {
    renderTo: 'editEcourseForm',
    bodyPadding: 10,
    height: 525,
    title: 'Edit ' + EcourseRecord.name.capitalize() + ' Ecourse',
    defaults: {
      labelWidth: 160
    },
    items: [{
      border: 0,
      html: '<h1>Ecourse Details</h1>',
      margin: '0 0 10'
    }, {
      xtype: 'hiddenfield',
      name: 'type',
      value: EcourseRecord.type
    }, {
      xtype: 'textfield',
      allowBlank: false,
      fieldLabel: 'Name',
      name: 'name',
      width: 400
    }, {
      xtype: 'numberfield',
      allowBlank: false,
      fieldLabel: 'Default Passing Percentage',
      minValue: 1,
      maxValue: 100,
      name: 'default_passing_percentage',
      value: 1,
      width: 225
    }, {
      xtype: 'combo',
      allowBlank: false,
      displayField: 'name',
      fieldLabel: 'Certificate Filing Category 1',
      id: 'cat1Name',
      listConfig: {
          getInnerTpl: function() {
              return '<div>{img}{name}</div>';
          }
      },
      listeners: {
        select: function(combo, records, Eopts) {
          var store = Ext.data.StoreManager.lookup('Cat2Store');

          if(records[0]) {
            Ext.getCmp('cat2Name').disable();
            Ext.getCmp('cat2Name').reset();
            Ext.getCmp('cat3Name').disable();
            Ext.getCmp('cat3Name').reset();
            store.load({params: {parentId: records[0].data.id}});
          }

        }
      },
      name: 'certificate_cat_1',
      queryMode: 'local',
      store: 'Cat1Store',
      value: null,
      valueField: 'id',
      width: 400
    },{
      xtype: 'combo',
      allowBlank: false,
      disabled: true,
      displayField: 'name',
      fieldLabel: 'Certificate Filing Category 2',
      id: 'cat2Name',
      listConfig: {
          getInnerTpl: function() {
              return '<div>{img}{name}</div>';
          }
      },
      listeners: {
        select: function(combo, records, Eopts) {
          var store = Ext.data.StoreManager.lookup('Cat3Store');

          if(records[0]) {
            Ext.getCmp('cat3Name').disable();
            Ext.getCmp('cat3Name').reset();
            store.load({params: {parentId: records[0].data.id}});
          }
        }
      },
      name: 'certificate_cat_2',
      queryMode: 'local',
      store: 'Cat2Store',
      value: null,
      valueField: 'id',
      width: 400
    },{
      xtype: 'combo',
      allowBlank: false,
      disabled: true,
      displayField: 'name',
      fieldLabel: 'Certificate Filing Category 3',
      id: 'cat3Name',
      listConfig: {
        getInnerTpl: function() {
          return '<div>{img}{name}</div>';
        }
      },
      name: 'certificate_cat_3',
      queryMode: 'local',
      store: 'Cat3Store',
      value: null,
      valueField: 'id',
      width: 400
    }, {
      xtype: 'checkboxfield',
      fieldLabel: 'Requires User Assignment',
      inputValue: 1,
      name: 'requires_user_assignment',
      uncheckedValue: 0,
      width: 400
    }, {
      xtype: 'htmleditor',
      anchor: '100%',
      fieldLabel: 'Instructions',
      height: 250,
      name: 'instructions'
    }],
    buttons: [{
      disabled: true,
      formBind: true,
      text: 'Save Ecourse',
      handler: function () {
        var form = this.up('form').getForm();

        if (form.isValid()) {
          form.updateRecord();
          form.getRecord().save({
            success: function (record, operation) {
              Ext.Msg.show({
                buttons: Ext.Msg.OK,
                icon: Ext.Msg.INFO,
                fn: function () {
                  window.location = '/admin/ecourses';
                },
                msg: 'The ecourse, ' + EcourseRecord.name + ', has been updated. You will now be redirected to the Ecourse index so you can add Modules and Quizzes.',
                title: 'Ecourse saved successfully'
              });
            }
          });
        }
      }
    }]
  });

  formPanel.getEl().mask('Loading ecourse...');

  EcourseModel.load(EcourseRecord.id, {
    success: function (ecourse) {
      Cat2Store.load({
        params: {
          parentId: ecourse.data.certificate_cat_1
        }
      });

      Cat3Store.load({
        params: {
          parentId: ecourse.data.certificate_cat_2
        }
      });

      formPanel.getForm().loadRecord(ecourse);
      formPanel.getEl().unmask();
      formPanel.loadRecord(ecourse);
    }
  });
});
