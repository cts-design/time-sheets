/**
 * formBuilder
 */
var encodeObject = function (obj) {
  if (Object.keys(obj).length) {
    return Ext.JSON.encode(obj);
  }
  return null;
};

Ext.define('ProgramFormField', {
  extend: 'Ext.data.Model',
  fields: [
    { name: 'id', type: 'int' },
    { name: 'program_step_id', type: 'int' },
    'label',
    'type',
    { name: 'order', type: 'int' },
    'name',
    { name: 'attributes', type: 'string', useNull: true },
    { name: 'options', type: 'string', useNull: true },
    { name: 'validation', type: 'string', useNull: true },
    { name: 'instructions', type: 'string', useNull: true },
    { name: 'created',  type: 'date', dateFormat: 'Y-m-d H:i:s' },
    { name: 'modified', type: 'date', dateFormat: 'Y-m-d H:i:s' }
  ]
});

Ext.define('ProgramStep', {
  extend: 'Ext.data.Model',
  fields: [
    { name: 'id', type: 'int' },
    { name: 'program_id', type: 'int' },
    { name: 'parent_id', type: 'int', useNull: true },
    'name',
    'type',
    'media_location',
    'media_type',
    { name: 'redoable', type: 'int' },
    { name: 'lft', type: 'int' },
    { name: 'rght', type: 'int' },
    { name: 'created',  type: 'date', dateFormat: 'Y-m-d H:i:s' },
    { name: 'modified', type: 'date', dateFormat: 'Y-m-d H:i:s' },
    { name: 'expires',  type: 'date', dateFormat: 'Y-m-d H:i:s' }
  ]
});

Ext.create('Ext.data.Store', {
  storeId: 'ProgramFormFieldStore',
  model: 'ProgramFormField',
  sorters: [{
    property: 'order',
    direction: 'ASC'
  }],
  proxy: {
    type: 'ajax',
    api: {
      create: '/admin/program_form_fields/create',
      read: '/admin/program_form_fields/read',
      update: '/admin/program_form_fields/update',
      destroy: '/admin/program_form_fields/destroy'
    },
    reader: {
      type: 'json',
      messageProperty: 'message',
      root: 'program_form_fields'
    },
    writer: {
      type: 'json',
      allowSingle: false,
      writeAllFields: true,
      encode: true,
      root: 'program_form_fields'
    }
  }
});

Ext.create('Ext.data.Store', {
  storeId: 'ProgramStepStore',
  model: 'ProgramStep',
  proxy: {
    type: 'ajax',
    api: {
      create: '/admin/program_steps/create',
      read: '/admin/program_steps/read',
      update: '/admin/program_steps/update',
      destroy: '/admin/program_steps/destroy'
    },
    reader: {
      type: 'json',
      messageProperty: 'message',
      root: 'program_steps'
    },
    writer: {
      type: 'json',
      encode: true,
      root: 'program_steps'
    }
  }
});

var states = {
  AL: 'Alabama',
  AK: 'Alaska',
  AZ: 'Arizona',
  AR: 'Arkansas',
  CA: 'California',
  CO: 'Colorado',
  CT: 'Connecticut',
  DE: 'Delaware',
  DC: 'District Of Columbia',
  FL: 'Florida',
  GA: 'Georgia',
  HI: 'Hawaii',
  ID: 'Idaho',
  IL: 'Illinois',
  IN: 'Indiana',
  IA: 'Iowa',
  KS: 'Kansas',
  KY: 'Kentucky',
  LA: 'Louisiana',
  ME: 'Maine',
  MD: 'Maryland',
  MA: 'Massachusetts',
  MI: 'Michigan',
  MN: 'Minnesota',
  MS: 'Mississippi',
  MO: 'Missouri',
  MT: 'Montana',
  NE: 'Nebraska',
  NV: 'Nevada',
  NH: 'New Hampshire',
  NJ: 'New Jersey',
  NM: 'New Mexico',
  NY: 'New York',
  NC: 'North Carolina',
  ND: 'North Dakota',
  OH: 'Ohio',
  OK: 'Oklahoma',
  OR: 'Oregon',
  PA: 'Pennsylvania',
  RI: 'Rhode Island',
  SC: 'South Carolina',
  SD: 'South Dakota',
  TN: 'Tennessee',
  TX: 'Texas',
  UT: 'Utah',
  VT: 'Vermont',
  VA: 'Virginia',
  WA: 'Washington',
  WI: 'Wisconsin',
  WV: 'West Virginia',
  WY: 'Wyoming'
};

Ext.create('Ext.panel.Panel', {
  bodyPadding: 0,
  height: 406,
  id: 'programFormFieldPanel',
  title: 'Program Form Fields',
  layout: 'border',
  items: [{
    xtype: 'grid',
    frame: false,
    height: 350,
    id: 'formFieldGrid',
    region: 'west',
    store: 'ProgramFormFieldStore',
    width: 660,
    selModel: {
      mode: 'SINGLE'
    },
    columns: [{
      header: 'Order',
      dataIndex: 'order',
      width: 50
    }, {
      header: 'Label',
      dataIndex: 'label',
      flex: 1
    }, {
      header: 'Type',
      dataIndex: 'type'
    }, {
      header: 'Name',
      dataIndex: 'name'
    }, {
      header: 'Attributes',
      dataIndex: 'attributes',
      hidden: true
    }, {
      header: 'Options',
      dataIndex: 'options',
      hidden: true
    }, {
      header: 'Validation',
      dataIndex: 'validation',
      hidden: true
    }, {
      header: 'Instructions',
      dataIndex: 'instructions'
    }],
    bbar: Ext.create('Ext.ux.StatusBar', {
      id: 'status-bar',
      defaultText: 'Ready',
      text: 'Ready'
    }),
    listeners: {
      select: function (rm, rec, index) {
        var formPanel = Ext.getCmp('formPanel'),
          form = formPanel.getForm(),
          requiredCb = formPanel.down('#requiredCb'),
          readOnlyCb = formPanel.down('#readOnlyCb'),
          fieldType = Ext.getCmp('fieldType'),
          fieldOptionsContainer = Ext.getCmp('fieldOptionsContainer'),
          fieldOptions = Ext.getCmp('fieldOptions'),
          deleteFieldBtn = Ext.getCmp('deleteFieldBtn'),
          updateBtn = Ext.getCmp('updateBtn'),
          builderSaveBtn = Ext.getCmp('builderSaveBtn');

        // check the appropriate checkboxes
        if (rec.data.validation && rec.data.validation.match(/notEmpty/g)) {
          requiredCb.setValue(true);
        }

        if (!rec.data.validation || !rec.data.validation.match(/notEmpty/g)) {
          requiredCb.setValue(false);
        }

        if (rec.data.attributes) {
          if (rec.data.attributes.match(/readonly/g)) {
            readOnlyCb.setValue(true);
          }

          if (rec.data.attributes.match(/datepicker/g)) {
            fieldType.setValue('datepicker');
            rec.data.type = 'datepicker';
          }

          if (rec.data.attributes.match(/value/g)) {
            attrs = Ext.JSON.decode(rec.data.attributes);
            rec.data.default_value = attrs.value;
          }
        }

        if (!rec.data.attributes || !rec.data.attributes.match(/readonly/g)) {
          readOnlyCb.setValue(false);
        }


        // if it's a state list we need to present it
        // differently to the user
        if (rec.data.options) {
          if (rec.data.options.match(/"AL":"Alabama"/gi)) {
            fieldType.setValue('states');
            fieldOptions.setValue('');
            fieldOptionsContainer.setVisible(false);
            rec.data.type = 'states';
            rec.data.options = '';
          } else if (rec.data.options.match(/"Yes":"Yes","No":"No"/gi)) {
            fieldOptions.setValue('');
            fieldOptionsContainer.setVisible(false);
            rec.data.options = 'yesno';
          } else if (rec.data.options.match(/"True":"True","False":"False"/gi)) {
            fieldOptions.setValue('');
            fieldOptionsContainer.setVisible(false);
            rec.data.options = 'truefalse';
          } else {
            var opts = Ext.JSON.decode(rec.data.options),
              vals = '',
              key;

            for (key in opts) {
              vals += opts[key] + ",";
            }

            vals = vals.replace(/(,$)/g, '');

            fieldOptions.setValue(vals);
            fieldOptionsContainer.setVisible(true);
          }
        }

        deleteFieldBtn.enable();
        updateBtn.show();
        builderSaveBtn.hide();
        form.loadRecord(rec);
      }
    },
    viewConfig: {
      emptyText: 'Please add your form fields',
      listeners: {
        drop: function (node, data, overModel, dropPos, eOpts) {
          var sb = Ext.getCmp('status-bar');
          sb.showBusy('Reordering....');
          var programFormFieldStore = Ext.data.StoreManager.lookup('ProgramFormFieldStore'),
            grid = data.view.up('#formFieldGrid'),
            gridEl = grid.getEl(),
            selectedRec = data.records[0],
            parseDrop,
            batch = new Ext.data.Batch(),
            i;

          // Reorder the selected field and it's overModel
          // based on the drop position
          parseDrop = (function () {
            return {
              before: function () {
                var overModelOrder = overModel.get('order');

                selectedRec.set('order', overModelOrder);
                overModel.set('order', (overModelOrder + 1));
              },
              after: function () {
                var overModelOrder = overModel.get('order');

                selectedRec.set('order', overModelOrder);
                overModel.set('order', (overModelOrder - 1));
              }
            };
          }());
          parseDrop[dropPos] && parseDrop[dropPos]();

          programFormFieldStore.sort('order', 'ASC');

          i = 1;
          programFormFieldStore.each(function (rec) {
            if (rec.get('order') !== i) {
              rec.set('order', i);
            }
            i++;
          });

          programFormFieldStore.sync({
            success: function() {
              sb.setStatus({
                text: 'Fields were reordered',
                iconCls: 'x-status-valid',
                clear: {
                  anim: false
                }
              });
            }
          });
        }
      },
      plugins: {
        ptype: 'gridviewdragdrop',
        dragText: 'Drag and drop to reorder form fields'
      }
    }
  }, {
    xtype: 'form',
    bodyPadding: 10,
    dockedItems: [{
      xtype: 'toolbar',
      dock: 'top',
      items: [{
        icon: '/img/icons/add.png',
        id: 'addFieldBtn',
        text: 'Add Field',
        handler: function () {
          var formPanel = Ext.getCmp('formPanel'),
            form = formPanel.getForm(),
            saveBtn = formPanel.down('#builderSaveBtn'),
            updateBtn = formPanel.down('#updateBtn'),
            grid = Ext.getCmp('formFieldGrid');

          form.reset();
          saveBtn.enable().show();
          updateBtn.disable().hide();
          grid.getSelectionModel().deselectAll();
        }
      }, {
        disabled: true,
        icon: '/img/icons/delete.png',
        id: 'deleteFieldBtn',
        text: 'Delete Field',
        handler: function () {
          var sb = Ext.getCmp('status-bar');
          sb.showBusy('Deleting...');
          var store = Ext.data.StoreManager.lookup('ProgramFormFieldStore'),
            formPanel = Ext.getCmp('formPanel'),
            form = formPanel.getForm(),
            deleteFieldBtn = Ext.getCmp('deleteFieldBtn'),
            updateBtn = Ext.getCmp('updateBtn'),
            builderSaveBtn = Ext.getCmp('builderSaveBtn'),
            grid = Ext.getCmp('formFieldGrid');

          Ext.Msg.show({
            title: 'Delete Field?',
            msg: 'Are you sure you want to delete this field?',
            buttons: Ext.Msg.YESNO,
            icon: Ext.Msg.QUESTION,
            fn: function (btn) {
              if (btn === 'yes') {
                store.remove(formPanel.getRecord());
                deleteFieldBtn.disable();
                updateBtn.disable().hide();
                builderSaveBtn.enable().show();
                form.reset();
                this.disable();
                grid.getSelectionModel().deselectAll();
                store.sync({
                  success: function(batch, options) {
                    var responseText = Ext.JSON.decode(batch.operations[0].response.responseText);
                    if(responseText.success) {
                      sb.setStatus({
                        text: 'Field was deleted',
                        iconCls: 'x-status-valid',
                        clear: {
                          anim: false
                        }
                      });
                    }
                    else {
                      this.failure();
                    }
                  },
                  failure: function() {
                      sb.setStatus({
                        text: 'An error has occurred',
                        iconCls: 'x-status-error',
                        clear: {
                          anim: false
                        }
                      });
                  },
                  scope: this
                });
              }
              else {
                sb.clearStatus();
              }
            },
            scope: this
          });
        }
      }]
    }],
    id: 'formPanel',
    margin: '0 0 15',
    region: 'center',
    items: [{
      xtype: 'textfield',
      allowBlank: false,
      fieldLabel: 'Label',
      name: 'label'
    }, {
      xtype: 'combo',
      allowBlank: false,
      displayField: 'ucase',
      editable: false,
      fieldLabel: 'Field Type',
      id: 'fieldType',
      listeners: {
        change: {
          fn: function (field, newValue, oldValue) {
            var container = Ext.getCmp('fieldOptionsContainer'),
              fieldOptions = Ext.getCmp('fieldOptions');

            if (newValue === 'select') {
              fieldOptions.allowBlank = false;
              container.getEl().highlight('C9DFEE', { duration: 1000 });
            } else {
              container.setVisible(false);
              fieldOptions.allowBlank = true;
            }
          }
        }
      },
      name: 'type',
      queryMode: 'local',
      store: Ext.create('Ext.data.Store', {
        fields: ['lcase', 'ucase'],
        data: [{
          lcase: 'checkbox', ucase: 'Checkbox'
        }, {
          lcase: 'datepicker', ucase: 'Datepicker'
        }, {
          lcase: 'states', ucase: 'List of States'
        }, {
          lcase: 'text', ucase: 'Textbox'
        }, {
          lcase: 'select', ucase: 'Select'
        }]
      }),
      value: 'text',
      valueField: 'lcase'
    }, {
      xtype: 'fieldcontainer',
      hidden: true,
      id: 'fieldOptionsContainer',
      items: [{
        xtype: 'combo',
        displayField: 'ucase',
        fieldLabel: 'Field Options',
        id: 'fieldOptions',
        name: 'options',
        queryMode: 'local',
        store: Ext.create('Ext.data.Store', {
          fields: ['lcase', 'ucase'],
          data: [{
            lcase: 'truefalse', ucase: 'True/False'
          }, {
            lcase: 'yesno', ucase: 'Yes/No'
          }]
        }),
        value: '',
        valueField: 'lcase'
      }, {
        border: false,
        cls: 'x-text-light',
        html: '<em>Enter options as comma separated values, or choose from the list</em>',
        padding: 5
      }]
    }, {
      xtype: 'checkbox',
      fieldLabel: 'Required',
      id: 'requiredCb',
      name: 'required'
    }, {
      xtype: 'checkbox',
      fieldLabel: 'Read only',
      id: 'readOnlyCb',
      name: 'read_only',
      listeners: {
        change: function (field, newVal, oldVal) {
          var container = Ext.getCmp('defaultValueContainer'),
            defaultValue = Ext.getCmp('defaultValue');

          if (newVal) {
            container.setVisible(newVal);
            defaultValue.allowBlank = false;
            container.getEl().highlight('C9DFEE', { duration: 1000 });
          } else {
            container.setVisible(false);
            defaultValue.allowBlank = true;
          }
        }
      }
    }, {
      xtype: 'fieldcontainer',
      hidden: true,
      id: 'defaultValueContainer',
      items: [{
        xtype: 'textfield',
        fieldLabel: 'Default Value',
        id: 'defaultValue',
        name: 'default_value'
      }]
    }, {
      xtype: 'textarea',
      fieldLabel: 'Instructions',
      name: 'instructions'
    }],
    buttons: [{
      disabled: true,
      formBind: true,
      id: 'builderSaveBtn',
      text: 'Save',
      handler: function () {
        var sm = Ext.data.StoreMgr,
          formPanel = this.up('form'),
          form = formPanel.getForm(),
          vals = form.getValues(),
          sb = Ext.getCmp('status-bar'),
          parseVals,
          attributes = {},
          options = {},
          validation = {},
          programFormFieldStore = sm.lookup('ProgramFormFieldStore'),
          programStep = sm.lookup('ProgramStepStore'),
          programStepId = programStep.last().data.id,
          grid = Ext.getCmp('formFieldGrid');
        sb.showBusy('Saving...');
        parseVals = (function () {
          return {
            datepicker: function () {
              attributes['class'] = 'datepicker';
              vals.type = 'text';
            },
            select: function () {
              if (vals.options === 'truefalse') {
                options.True = 'True';
                options.False = 'False';
              } else if (vals.options === 'yesno') {
                options.Yes = 'Yes';
                options.No = 'No';
              } else {
                Ext.Array.each(vals.options.split(','), function (item, index) {
                  options[item] = item;
                });
              }

              attributes.empty = 'Please Select';
            },
            states: function () {
              vals.type = 'select';
              options = states;
            }
          };
        }());

        parseVals[vals.type] && parseVals[vals.type]();

        if (vals.read_only === 'on') {
          attributes.readonly = 'readonly';

          if (vals.default_value) {
            attributes.value = vals.default_value;
          }
        }

        if (vals.required === 'on') {
          validation.rule = 'notEmpty';
        }

        vals.attributes      = encodeObject(attributes);
        vals.options         = encodeObject(options);
        vals.validation      = encodeObject(validation);
        vals.program_step_id = programStepId;
        vals.name            = vals.label.underscore();
        vals.order           = (programFormFieldStore.count() + 1);

        grid.store.add(vals);
        form.reset();

        programFormFieldStore.sync({
          success: function(batch, options) {
            var responseText = Ext.JSON.decode(batch.operations[0].response.responseText);
            if(responseText.success) {
              sb.setStatus({
                text: 'Field Saved',
                iconCls: 'x-status-valid',
                clear: {
                  anim: false
                }
              });
            }
            else {
              this.failure();
            }
          },
          failure: function() {
            sb.setStatus({
              text: 'An error has occurred.',
              iconCls: 'x-status-error',
              clear: {
                anim: false
              }
            });
          },
          scope: this
        });
        return true;
      }
    }, {
      disabled: true,
      formBind: true,
      hidden: true,
      id: 'updateBtn',
      text: 'Update',
      handler: function () {
        var formPanel = this.up('form'),
          form = formPanel.getForm(),
          vals = form.getValues(),
          parseVals,
          attributes = {},
          options = {},
          validation = {},
          programStep = Ext.data.StoreManager.lookup('ProgramStepStore'),
          sb = Ext.getCmp('status-bar'),
          programFormFieldStore = Ext.data.StoreManager.lookup('ProgramFormFieldStore'),
          programStepId = programStep.last().data.id,
          grid = Ext.getCmp('formFieldGrid'),
          selectedRecord = grid.getSelectionModel().getSelection()[0],
          deleteFieldBtn = Ext.getCmp('deleteFieldBtn'),
          updateBtn = Ext.getCmp('updateBtn'),
          builderSaveBtn = Ext.getCmp('builderSaveBtn');

        sb.showBusy('Updating...');
        
        parseVals = (function () {
          return {
            datepicker: function () {
              attributes['class'] = 'datepicker';
              vals.type = 'text';
            },
            select: function () {
              if (vals.options === 'truefalse') {
                options.True = 'True';
                options.False = 'False';
              } else if (vals.options === 'yesno') {
                options.Yes = 'Yes';
                options.No = 'No';
              } else {
                Ext.Array.each(vals.options.split(','), function (item, index) {
                  options[item] = item;
                });
              }

              attributes.empty = 'Please Select';
            },
            states: function () {
              vals.type = 'select';
              options = states;
            }
          };
        }());

        parseVals[vals.type] && parseVals[vals.type]();

        if (vals.read_only === 'on') {
          attributes.readonly = 'readonly';
        } else {
          vals.readonly = 'off';
          attributes = {};
        }

        if (vals.required === 'on') {
          validation.rule = 'notEmpty';
        } else {
          vals.required = 'off';
          validation = {};
        }

        vals.attributes      = encodeObject(attributes);
        vals.options         = encodeObject(options);
        vals.validation      = encodeObject(validation);
        vals.program_step_id = programStepId;
        vals.name            = vals.label.underscore();

        selectedRecord.set(vals);
        updateBtn.disable().hide();
        builderSaveBtn.enable().show();
        deleteFieldBtn.disable();
        form.reset();
        grid.getSelectionModel().deselectAll();

        programFormFieldStore.sync({
          success: function() {
            var responseText = Ext.JSON.decode(batch.operations[0].response.responseText);
            if(responseText.success) {
              sb.setStatus({
                text: 'Field updated',
                iconCls: 'x-status-valid',
                clear: {
                  anim: false
                }
              });
            }
            else {
              this.failure();
            }

          },
          failure: function() {
            sb.setStatus({
              text: 'An error has occurred',
              iconCls: 'x-status-error',
              clear: {
                anim: false
              }
            });
          },
          scope: this
        });
        return true;
      }
    }]
  }]
});

Ext.onReady(function() {
  var programStep = Ext.data.StoreManager.lookup('ProgramStepStore'),
    programFormFieldStore = Ext.data.StoreManager.lookup('ProgramFormFieldStore');
  programStep.load({ params: { program_id: programId } });
  programFormFieldStore.load({ params: { program_step_id: programStepId } });
  Ext.getCmp('programFormFieldPanel').render('programFormFieldPanel');
});
