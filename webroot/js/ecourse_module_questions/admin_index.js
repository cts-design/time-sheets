Ext.override(Ext.data.writer.Json, {
  getRecordData: function(record) {
    var me = this,
      i,
      association,
      childStore,
      data = this.callParent(arguments);

    /* Iterate over all the hasMany associations */
    for (i = 0; i < record.associations.length; i++) {
      association = record.associations.get(i);
      if (association.type == 'hasMany')  {
        data[association.name] = [];
        childStore = eval('record.'+association.name+'()');

        //Iterate over all the children in the current association
        childStore.each(function(childRecord) {
          console.log(childRecord);
          data[association.name].push(childRecord.getData());
        }, me);
      }
    }

    return data;
  }
});

/**
 * Models
 */
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
  associations: [{
    foreignKey: 'ecourse_module_question_id',
    model: 'EcourseModuleQuestionAnswer',
    name: 'answers',
    type: 'hasMany'
  }],
  fields: [
    { name: 'id', type: 'int' },
    { name: 'ecourse_module_id', type: 'int' },
    'text',
    { name: 'order', type: 'int' }
  ],
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
  autoLoad: true,
  autoSync: true,
  model: 'EcourseModuleQuestion',
  listeners: {
    load: function (store, records, successful) {
      Ext.Array.each(records, function(rec) {
        var answer = rec.getAssociatedData();
      });
    }
  },
  sorters: [{
    property: 'order',
    direction: 'ASC'
  }]
});

Ext.onReady(function () {
  Ext.QuickTips.init();

  var moduleForm,
    ecourseModuleQuestionStore = Ext.data.StoreManager.lookup('EcourseModuleQuestionStore');

  moduleForm = Ext.create('Ext.panel.Panel', {
    renderTo: 'ecourseModuleQuestionsForm',
    height: 439,
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
        xtype: 'hiddenfield',
        name: 'ecourse_module_id',
        value: ecourse_module.id
      }, {
        xtype: 'numberfield',
        allowBlank: false,
        fieldLabel: 'Order',
        id: 'orderField',
        name: 'order',
        width: 125
      }, {
        xtype: 'textareafield',
        anchor: '100%',
        fieldLabel: 'Question',
        height: 163,
        name: 'text',
        width: 278
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
          allowBlank: false,
          fieldLabel: 'Label',
          hideLabel: true,
          id: 'answer1',
          name: 'answer',
          width: 175
        }, {
          xtype: 'radiofield',
          boxLabel: '',
          boxLabelAlign: 'before',
          fieldLabel: 'Label',
          hideLabel: true,
          margins: '0 0 0 5',
          name: '0'
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
          allowBlank: false,
          width: 175,
          fieldLabel: 'Label',
          hideLabel: true,
          id: 'answer2',
          name: 'answer'
        }, {
          xtype: 'radiofield',
          margins: '0 0 0 5',
          fieldLabel: 'Label',
          hideLabel: true,
          boxLabel: '',
          boxLabelAlign: 'before',
          name: '1'
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
          hideLabel: true,
          id: 'answer3',
          name: 'answer'
        }, {
          xtype: 'radiofield',
          margins: '0 0 0 5',
          fieldLabel: 'Label',
          hideLabel: true,
          boxLabel: '',
          boxLabelAlign: 'before',
          name: '2'
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
          hideLabel: true,
          id: 'answer4',
          name: 'answer'
        }, {
          xtype: 'radiofield',
          margins: '0 0 0 5',
          fieldLabel: 'Label',
          hideLabel: true,
          boxLabel: '',
          boxLabelAlign: 'before',
          name: '3'
        }]
      }],
      dockedItems: [{
        xtype: 'toolbar',
        dock: 'bottom',
        items: [{
          xtype: 'tbfill'
        }, {
          xtype: 'button',
          formBind: true,
          text: 'Save Question',
          handler: function () {
            var formPanel = this.up('form'),
              form = formPanel.getForm(),
              formValues = form.getValues(),
              question,
              answers,
              questionStore = Ext.data.StoreManager.lookup('EcourseModuleQuestionStore'),
              isNewRecord = (typeof form.getRecord() === 'undefined');

            if (isNewRecord) {
              question = Ext.create('EcourseModuleQuestion', {
                ecourse_module_id: formValues.ecourse_module_id,
                text: formValues.text,
                order: formValues.order
              });

              answers = question.answers();

              Ext.Array.each(formValues.answer, function (answer, index) {
                if (answer) {
                  obj = { text: answer, correct: 0 }
                  if (formValues.hasOwnProperty(index)) { obj.correct = 1; }
                  answers.add(obj)
                }
              });

              question.save();
              questionStore.load();
              form.reset();
            } else {
              console.log(formValues);
              console.log(form.getRecord().answers());
            }
          }
        }]
      }]
    }, {
      xtype: 'gridpanel',
      forceFit: true,
      region: 'center',
      store: 'EcourseModuleQuestionStore',
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
          allowBlank: false
        }
      }, {
        dataIndex: 'text',
        flex: 1,
        text: 'Question',
        editor: {
          xtype: 'textfield',
          allowBlank: false
        }
      }],
      listeners: {
        containerclick: function (grid) {
          grid.getSelectionModel().deselectAll();
          Ext.getCmp('editQuestionBtn').disable();
          Ext.getCmp('deleteQuestionBtn').disable();
        },
        deselect: function (grid, record, index) {
          Ext.getCmp('editQuestionBtn').disable();
          Ext.getCmp('deleteQuestionBtn').disable();
        },
        itemclick: function (grid, rec) {
          Ext.getCmp('editQuestionBtn').enable();
          Ext.getCmp('deleteQuestionBtn').enable();
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
      selModel: {
        allowDeselect: true,
        mode: 'SINGLE'
      },
      selType: 'rowmodel',
      viewConfig: {
        deferEmptyText: false,
        emptyText: 'There are no questions for this module at this time',
      },
      dockedItems: [{
        xtype: 'toolbar',
        dock: 'top',
        items: [{
          icon: '/img/icons/add.png',
          text: 'New Question',
          handler: function () {
            var gridPanel = this.up('grid'),
              formPanel = moduleForm.down('form'),
              form = formPanel.getForm(),
              orderField = formPanel.down('#orderField');

            gridPanel.getSelectionModel().deselectAll();
            form.reset(true);
            orderField.setValue(gridPanel.store.totalCount + 1);
          }
        }, {
          disabled: true,
          icon: '/img/icons/edit.png',
          id: 'editQuestionBtn',
          text: 'Edit Question',
          handler: function () {
            var gridPanel = this.up('grid'),
              formPanel = moduleForm.down('form'),
              form = formPanel.getForm(),
              selectedRecord = gridPanel.getSelectionModel().getSelection()[0];

            form.reset(true);
            form.loadRecord(selectedRecord);
            selectedRecord.answers().each(function (answer, index) {
              var fieldIndex = index + 1,
                field = formPanel.down('#answer' + fieldIndex),
                radio = field.nextNode('radiofield');

              field.setValue(answer.get('text'));
              if (answer.get('correct')) {
                radio.setValue(true);
              }
            });
          }
        }, {
          disabled: true,
          icon: '/img/icons/delete.png',
          id: 'deleteQuestionBtn',
          text: 'Delete Question',
          handler: function() {
            var gridPanel = this.up('grid'),
              store = gridPanel.store,
              formPanel = moduleForm.down('form'),
              form = formPanel.getForm(),
              selectedRecord = gridPanel.getSelectionModel().getSelection()[0],
              reorder = false;

            if (form.getRecord() === selectedRecord) {
              form.reset(true);
            }

            // If the record is in the middle of the store we need to reorder
            // our records
            if (selectedRecord !== store.first() && selectedRecord !== store.last()) {
              store.on({
                remove: {
                  fn: function () {
                    gridPanel.getEl().mask('Reordering module questions...');
                    store.sort('order', 'ASC');
                    store.each(function (record) {
                      var correctOrder = record.index + 1;

                      if (record.get('order') !== correctOrder) {
                        record.set('order', correctOrder);
                      }
                    });
                    gridPanel.getEl().unmask();
                  },
                  scope: this,
                  single: true
                }
              });
            }

            store.remove(selectedRecord);
          }
        }]
      }]
    }]
  });
});
