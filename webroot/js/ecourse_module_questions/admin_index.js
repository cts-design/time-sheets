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
    'media_description',
    'media_type',
    'media_location',
    { name: 'order', type: 'int' },
    { name: 'passing_percentage', type: 'int' }
  ]
});

Ext.define('EcourseModuleQuestion', {
  extend: 'Ext.data.Model',
  fields: [
    { name: 'id', type: 'int' },
    { name: 'ecourse_module_id', type: 'int' },
    'text',
    { name: 'order', type: 'int' }
  ]
});

Ext.define('EcourseModuleQuestionAnswer', {
  extend: 'Ext.data.Model',
  fields: [
    { name: 'id', type: 'int' },
    { name: 'ecourse_module_question_id', type: 'int' },
    'text',
    { name: 'correct', type: 'int' }
  ]
});

/**
 * DataStores
 */
Ext.create('Ext.data.Store', {
  storeId: 'EcourseModuleQuestionStore',
  //autoLoad: true,
  //autoSync: true,
  model: 'EcourseModuleQuestion',
  proxy: {
    type: 'ajax',
    api: {
      create: '/admin/ecourse_module_questions/create',
      read: '/admin/ecourse_module_questions/index',
      update: '/admin/ecourse_module_questions/update',
      destroy: '/admin/ecourse_module_questions/destroy'
    },
    extraParams: {
      ecourse_module_id: ecourse_module.id
    },
    reader: {
      type: 'json',
      root: 'ecourse_module_questions'
    },
    writer: {
      encode: true,
      root: 'ecourse_module_questions',
      type: 'json',
      writeAllFields: false
    }
  }
});

Ext.onReady(function () {
  Ext.QuickTips.init();

  var moduleForm,
    ecourseModuleQuestionStore = Ext.data.StoreManager.lookup('EcourseModuleQuestionStore');

  moduleForm = Ext.create('Ext.panel.Panel', {
    renderTo: 'ecourseModuleQuestionsForm',
    height: 439,
    width: 966,
    layout: {
      type: 'border'
    },
    title: 'My Panel',
    items: [{
      xtype: 'form',
      region: 'east',
      width: 300,
      defaults: {
        labelWidth: 75
      },
      bodyPadding: 10,
      items: [{
        xtype: 'textareafield',
        anchor: '100%',
        height: 163,
        width: 278,
        fieldLabel: 'Question'
      }, {
        xtype: 'combobox',
        anchor: '100%',
        fieldLabel: 'Type'
      }, {
        xtype: 'fieldcontainer',
        height: 24,
        width: 273,
        layout: {
          align: 'stretch',
          type: 'hbox'
        },
        fieldLabel: 'Answer 1',
        items: [{
          xtype: 'textfield',
          width: 175,
          fieldLabel: 'Label',
          hideLabel: true
        }, {
          xtype: 'radiofield',
          margins: '0 0 0 5',
          fieldLabel: 'Label',
          hideLabel: true,
          boxLabel: '',
          boxLabelAlign: 'before'
        }]
      }, {
        xtype: 'fieldcontainer',
        height: 24,
        width: 273,
        layout: {
          align: 'stretch',
          type: 'hbox'
        },
        fieldLabel: 'Answer 2',
        items: [{
          xtype: 'textfield',
          width: 175,
          fieldLabel: 'Label',
          hideLabel: true
        }, {
          xtype: 'radiofield',
          margins: '0 0 0 5',
          fieldLabel: 'Label',
          hideLabel: true,
          boxLabel: '',
          boxLabelAlign: 'before'
        }]
      }, {
        xtype: 'fieldcontainer',
        height: 24,
        width: 273,
        layout: {
          align: 'stretch',
          type: 'hbox'
        },
        fieldLabel: 'Answer 3',
        items: [{
          xtype: 'textfield',
          width: 175,
          fieldLabel: 'Label',
          hideLabel: true
        }, {
          xtype: 'radiofield',
          margins: '0 0 0 5',
          fieldLabel: 'Label',
          hideLabel: true,
          boxLabel: '',
          boxLabelAlign: 'before'
        }]
      }, {
        xtype: 'fieldcontainer',
        height: 24,
        width: 273,
        layout: {
          align: 'stretch',
          type: 'hbox'
        },
        fieldLabel: 'Answer 4',
        items: [{
          xtype: 'textfield',
          width: 175,
          fieldLabel: 'Label',
          hideLabel: true
        }, {
          xtype: 'radiofield',
          margins: '0 0 0 5',
          fieldLabel: 'Label',
          hideLabel: true,
          boxLabel: '',
          boxLabelAlign: 'before'
        }]
      }],
      dockedItems: [{
        xtype: 'toolbar',
        dock: 'bottom',
        items: [{
          xtype: 'tbfill'
        }, {
          xtype: 'button',
          text: 'Save Question'
        }]
      }]
    }, {
      xtype: 'gridpanel',
      region: 'center',
      forceFit: true,
      columns: [{
        xtype: 'gridcolumn',
        dataIndex: 'string',
        text: 'String'
      }, {
        xtype: 'numbercolumn',
        dataIndex: 'number',
        text: 'Number'
      }, {
        xtype: 'datecolumn',
        dataIndex: 'date',
        text: 'Date'
      }, {
        xtype: 'booleancolumn',
        dataIndex: 'bool',
        text: 'Boolean'
      }],
      viewConfig: {

      },
      dockedItems: [{
        xtype: 'toolbar',
        dock: 'top',
        items: [{
            xtype: 'button',
            text: 'New Question'
          }, {
            xtype: 'button',
            text: 'Edit Question'
          }, {
            xtype: 'button',
            text: 'Delete Question'
          }]
      }]
    }]
  });
});
