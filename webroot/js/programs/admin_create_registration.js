var encodeObject = function (obj) {
  if (Object.keys(obj).length) {
    return Ext.JSON.encode(obj);
  }
  return null;
};

/**
 * Data Models
 */
Ext.define('Program', {
  extend: 'Ext.data.Model',
  fields: [
    { name: 'id', type: 'int' },
    'name',
    'type',
    'atlas_registration_type',
    { name: 'queue_category_id', type: 'int' },
    { name: 'approval_required', type: 'int' },
    { name: 'form_esign_required', type: 'int' },
    { name: 'user_acceptance_required', type: 'int' },
    { name: 'confirmation_id_length', type: 'int' },
    { name: 'response_expires_in', type: 'int' },
    { name: 'send_expiring_soon', type: 'int' },
    { name: 'program_response_count', type: 'int' },
    { name: 'show_in_dash', type: 'int' },
    { name: 'in_test', type: 'int' },
    { name: 'disabled', type: 'int' },
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

Ext.define('ProgramFormField', {
  extend: 'Ext.data.Model',
  fields: [
    { name: 'id', type: 'int' },
    { name: 'program_step_id', type: 'int' },
    'label',
    'type',
    'name',
    { name: 'attributes', type: 'string', useNull: true },
    { name: 'options', type: 'string', useNull: true },
    { name: 'validation', type: 'string', useNull: true },
    { name: 'instructions', type: 'string', useNull: true },
    { name: 'created',  type: 'date', dateFormat: 'Y-m-d H:i:s' },
    { name: 'modified', type: 'date', dateFormat: 'Y-m-d H:i:s' }
  ]
});

Ext.define('ProgramDocument', {
  extend: 'Ext.data.Model',
  fields: [
    { name: 'id', type: 'int' },
    { name: 'program_id', type: 'int' },
    { name: 'program_step_id', type: 'int' },
    'template',
    'name',
    { name: 'cat_1', type: 'int' },
    { name: 'cat_2', type: 'int' },
    { name: 'cat_3', type: 'int' },
    'type',
    { name: 'created',  type: 'date', dateFormat: 'Y-m-d H:i:s' },
    { name: 'modified', type: 'date', dateFormat: 'Y-m-d H:i:s' }
  ]
});

Ext.define('ProgramInstruction', {
  extend: 'Ext.data.Model',
  fields: [
    { name: 'id', type: 'int' },
    { name: 'program_id', type: 'int' },
    { name: 'program_step_id', type: 'int', useNull: true },
    'text',
    'type',
    { name: 'created',  type: 'date', dateFormat: 'Y-m-d H:i:s' },
    { name: 'modified', type: 'date', dateFormat: 'Y-m-d H:i:s' }
  ]
});

Ext.define('ProgramEmail', {
  extend: 'Ext.data.Model',
  fields: [
    { name: 'id', type: 'int' },
    { name: 'program_id', type: 'int' },
    { name: 'program_step_id', type: 'int', useNull: true },
    { name: 'cat_id', type: 'int' },
    'to',
    { name: 'from', type: 'string', useNull: true },
    'subject',
    'body',
    'type',
    'name',
    { name: 'created',  type: 'date', dateFormat: 'Y-m-d H:i:s' },
    { name: 'modified', type: 'date', dateFormat: 'Y-m-d H:i:s' }
  ]
});

Ext.define('DocumentQueueCategory', {
  extend: 'Ext.data.Model',
  fields: [
    { name: 'id', type: 'int' },
    'name',
    'secure',
    {
      name : 'img',
      convert: function(value, record){
        var img = null,
          secure = record.get('secure');

        if(secure) {
          img = '<img src="/img/icons/lock.png" />&nbsp';
        }
        return img;
      }
    }]
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
        var img = null,
          secure = record.get('secure');

        if(secure) {
          img = '<img src="/img/icons/lock.png" />&nbsp';
        }
        return img;
      }
  }]
});

/**
 * Data Stores
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

Ext.create('Ext.data.Store', {
  storeId: 'DocumentQueueCategoryStore',
  model: 'DocumentQueueCategory',
  proxy: {
    type: 'ajax',
    url: '/admin/document_queue_categories/get_cats',
    reader: {
      type: 'json',
      root: 'cats'
    }
  }
});

Ext.create('Ext.data.Store', {
  autoSync: true,
  storeId: 'ProgramStore',
  model: 'Program',
  proxy: {
    type: 'ajax',
    api: {
      create: '/admin/programs/create_registration',
      update: '/admin/programs/update_registration'
    },
    reader: {
      type: 'json',
      messageProperty: 'message',
      root: 'programs'
    },
    writer: {
      type: 'json',
      encode: true,
      root: 'programs',
      writeAllFields: false
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

Ext.create('Ext.data.Store', {
  storeId: 'ProgramFormFieldStore',
  model: 'ProgramFormField',
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
      encode: true,
      root: 'program_form_fields'
    }
  }
});

Ext.create('Ext.data.Store', {
  autoSync: true,
  storeId: 'ProgramDocumentStore',
  model: 'ProgramDocument',
  proxy: {
    type: 'ajax',
    api: {
      create: '/admin/program_documents/create',
      read: '/admin/program_documents/read',
      update: '/admin/program_documents/update',
      destroy: '/admin/program_documents/destroy'
    },
    reader: {
      type: 'json',
      messageProperty: 'message',
      root: 'program_documents'
    },
    writer: {
      type: 'json',
      encode: true,
      root: 'program_documents',
      writeAllFields: false
    }
  }
});

Ext.create('Ext.data.Store', {
  data: [
    { program_id: 0, text: 'Default text Main', type: 'main', created: null, modified: null },
    { program_id: 0, text: 'Default text Pending Approval', type: 'pending_approval', created: null, modified: null },
    { program_id: 0, text: 'Default text Expired', type: 'expired', created: null, modified: null },
    { program_id: 0, text: 'Default text Not Approved', type: 'not_approved', created: null, modified: null },
    { program_id: 0, text: 'Default text Complete', type: 'complete', created: null, modified: null },
    { program_id: 0, text: 'Default text Acceptance', type: 'acceptance', created: null, modified: null }
  ],
  storeId: 'ProgramInstructionStore',
  model: 'ProgramInstruction',
  proxy: {
    api:{
      create: '/admin/program_instructions/create',
      read: '/admin/program_instructions/read',
      update: '/admin/program_instructions/edit',
      destroy: '/admin/program_instructions/destroy'
    },
    type: 'ajax',
    reader: {
      type: 'json',
      root: 'program_instructions'
    },
    writer: {
      allowSingle: false,
      encode: true,
      root: 'program_instructions',
      writeAllFields: false
    }
  }
});

Ext.create('Ext.data.Store', {
  data: [
    { program_id: 0, name: 'Registration Main', from: null, subject: 'Main', body: 'Default text Main', type: 'main', created: null, modified: null },
    { program_id: 0, name: 'Registration Pending Approval', from: null, subject: 'Pending Approval', body: 'Default text Pending Approval', type: 'pending_approval', created: null, modified: null },
    { program_id: 0, name: 'Registration Expiring Soon', from: null, subject: 'Expiring Soon', body: 'Default text Expiring Soon', type: 'expiring_soon', created: null, modified: null },
    { program_id: 0, name: 'Registration Expired', from: null, subject: 'Expired', body: 'Default text Expired', type: 'expired', created: null, modified: null },
    { program_id: 0, name: 'Registration Not Approved', from: null, subject: 'Not Approved', body: 'Default text Main', type: 'not_approved', created: null, modified: null },
    { program_id: 0, name: 'Registration Complete', from: null, subject: 'Complete', body: 'Default text Complete', type: 'complete', created: null, modified: null }
  ],
  storeId: 'ProgramEmailStore',
  model: 'ProgramEmail',
  proxy: {
    api:{
      create: '/admin/program_emails/create',
      read: '/admin/program_emails/read',
      update: '/admin/program_emails/edit',
      destroy: '/admin/program_emails/destroy'
    },
    type: 'ajax',
    reader: {
      type: 'json',
      root: 'program_emails'
    },
    writer: {
      allowSingle: false,
      encode: true,
      root: 'program_emails',
      writeAllFields: false
    }
  }
});

/**
 * Variable Declarations
 */
var registrationForm, formBuilder, filingCategories, instructions, emails, navigate, statusBar, states;

states = {
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

/**
 * registrationForm
 */
registrationForm = Ext.create('Ext.form.Panel', {
  height: 406,
  items: [{
    border: 0,
    html: '<h1>Program Details</h1>',
    margin: '0 0 10'
  }, {
    xtype: 'fieldcontainer',
    height: 24,
    width: 476,
    layout: {
      align: 'stretch',
      type: 'vbox'
    },
    items: [{
      xtype: 'textfield',
      allowBlank: false,
      fieldLabel: 'Name',
      labelWidth: 190,
      name: 'name'
    }]
  }, {
    xtype: 'hiddenfield',
    name: 'type',
    value: 'registration'
  }, {
    xtype: 'hiddenfield',
    name: 'form_esign_required',
    value: '0'
  }, {
    xtype: 'fieldcontainer',
    height: 22,
    width: 300,
    layout: {
      align: 'stretch',
      type: 'vbox'
    },
    items: [{
      xtype: 'radiogroup',
      fieldLabel: 'Approval Required?',
      labelWidth: 190,
      items: [{
        boxLabel: 'Yes',
        name: 'approval_required',
        inputValue: '1'
      }, {
        boxLabel: 'No',
        name: 'approval_required',
        inputValue: '0',
        checked: true
      }]
    }]
  }, {
    xtype: 'fieldcontainer',
    height: 22,
    width: 300,
    layout: {
      align: 'stretch',
      type: 'vbox'
    },
    items: [{
      xtype: 'radiogroup',
      fieldLabel: 'User Acknowledgement Required?',
      labelWidth: 190,
      items: [{
        boxLabel: 'Yes',
        name: 'user_acceptance_required',
        inputValue: '1'
      }, {
        boxLabel: 'No',
        name: 'user_acceptance_required',
        inputValue: '0',
        checked: true
      }]
    }]
  }, {
    xtype: 'fieldcontainer',
    height: 22,
    width: 300,
    layout: {
      align: 'stretch',
      type: 'vbox'
    },
    items: [{
      xtype: 'radiogroup',
      fieldLabel: 'Show in Customer Dashboard?',
      labelWidth: 190,
      items: [{
        boxLabel: 'Yes',
        name: 'show_in_dash',
        inputValue: '1',
        checked: true
      }, {
        boxLabel: 'No',
        name: 'show_in_dash',
        inputValue: '0'
      }]
    }]
  }, {
    xtype: 'fieldcontainer',
    height: 24,
    width: 300,
    layout: {
      align: 'stretch',
      type: 'vbox'
    },
    items: [{
      xtype: 'combo',
      allowBlank: false,
      displayField: 'ucase',
      fieldLabel: 'Registration Type',
      labelWidth: 190,
      name: 'atlas_registration_type',
      queryMode: 'local',
      store: Ext.create('Ext.data.Store', {
        fields: ['lcase', 'ucase'],
        data: [{
          lcase: 'child', ucase: 'Child'
        }, {
          lcase: 'normal', ucase: 'Normal'
        }]
      }),
      value: 'normal',
      valueField: 'lcase'
    }]
  }, {
    xtype: 'fieldcontainer',
    height: 22,
    width: 350,
    layout: {
      defaultMargins: {
        top: 0,
        right: 5,
        bottom: 0,
        left: 0
      },
      type: 'hbox'
    },
    items: [{
      xtype: 'numberfield',
      allowBlank: false,
      fieldLabel: 'Responses Expire In',
      labelWidth: 190,
      minValue: 30,
      name: 'response_expires_in',
      value: 30,
      width: 250
    }, {
      xtype: 'displayfield',
      style: {
        color: '#445566'
      },
      value: 'days'
    }]
  }, {
    xtype: 'fieldcontainer',
    height: 24,
    width: 400,
    layout: {
      defaultMargins: {
        top: 0,
        right: 5,
        bottom: 0,
        left: 0
      },
      type: 'hbox'
    },
    items: [{
      xtype: 'combo',
      allowBlank: false,
      displayField: 'ucase',
      fieldLabel: 'Send expiring soon emails',
      labelWidth: 190,
      name: 'send_expiring_soon',
      queryMode: 'local',
      store: Ext.create('Ext.data.Store', {
        fields: ['lcase', 'ucase'],
        data: [{
          lcase: '3', ucase: '3'
        }, {
          lcase: '5', ucase: '5'
        }, {
          lcase: '7', ucase: '7'
        }]
      }),
      value: '3',
      valueField: 'lcase',
      width: 250
    }, {
      xtype: 'displayfield',
      style: {
        color: '#445566'
      },
      value: 'days prior to expiration'
    }]
  }, {
    xtype: 'hiddenfield',
    name: 'confirmation_id_length',
    value: '10'
  }, {
    xtype: 'hiddenfield',
    name: 'in_test',
    value: 1
  }, {
    xtype: 'hiddenfield',
    name: 'disabled',
    value: 1
  }],
  process: function () {
    var form = this.getForm(),
      programStore = Ext.data.StoreManager.lookup('ProgramStore'),
      record,
      vals;

    statusBar.showBusy();

    if (form.isValid()) {
      vals = form.getValues();
      record = programStore.first();

      if (record) {
        record.set(vals);
      } else {
        form.setValues(vals);
        programStore.add(vals);
      }

      statusBar.clearStatus();
      return true;
    }

    statusBar.clearStatus();
  }
});

/**
 * formBuilder
 */
formBuilder = Ext.create('Ext.panel.Panel', {
  bodyPadding: 0,
  height: 406,
  layout: 'border',
  items: [{
    xtype: 'grid',
    frame: false,
    height: 350,
    id: 'formFieldGrid',
    region: 'west',
    store: 'ProgramFormFieldStore',
    width: 660,
    columns: [{
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
    listeners: {
      select: function (rm, rec, index) {
        var formPanel = Ext.getCmp('formPanel'),
          form = formPanel.getForm(),
          deleteFieldBtn = Ext.getCmp('deleteFieldBtn'),
          updateBtn = Ext.getCmp('updateBtn'),
          builderSaveBtn = Ext.getCmp('builderSaveBtn');

        if (form.isDirty()) {
          Ext.Msg.show({
            title: 'Discard Changes?',
            msg: 'You have an unsaved form field, discard changes?',
            buttons: Ext.Msg.YESNO,
            icon: Ext.Msg.QUESTION,
            fn: function (btn) {
              if (btn === 'yes') {
                form.reset();
                form.loadRecord(rec);
                deleteFieldBtn.enable();
                updateBtn.show();
                builderSaveBtn.hide();
              }
            }
          });
        } else {
          form.loadRecord(rec);
          deleteFieldBtn.enable();
          updateBtn.show();
          builderSaveBtn.hide();
        }

      }
    },
    viewConfig: {
      emptyText: 'Please add your form fields',
      listeners: {
        beforedrop: function (node, data, overModel, dropPos, dropFunc, eOpts) {
        },
        drop: function (node, data, overModel, dropPos, eOpts) {
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
            form = formPanel.getForm();

          if (form.isDirty()) {
            Ext.Msg.show({
              title: 'Discard Changes?',
              msg: 'You have an unsaved form field, discard changes?',
              buttons: Ext.Msg.YESNO,
              icon: Ext.Msg.QUESTION,
              fn: function (btn) {
                if (btn === 'yes') {
                  form.reset();
                }
              }
            });
          } else {
            form.reset();
          }

        }
      }, {
        disabled: true,
        icon: '/img/icons/delete.png',
        id: 'deleteFieldBtn',
        text: 'Delete Field',
        handler: function () {
          var store = Ext.data.StoreManager.lookup('ProgramFormFieldStore'),
            formPanel = Ext.getCmp('formPanel'),
            form = formPanel.getForm();

          Ext.Msg.show({
            title: 'Delete Field?',
            msg: 'Are you sure you want to delete this field?',
            buttons: Ext.Msg.YESNO,
            icon: Ext.Msg.QUESTION,
            fn: function (btn) {
              if (btn === 'yes') {
                store.remove(formPanel.getRecord());
                form.reset();
                this.disable();
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
      listeners: {
        change: {
          fn: function (field, newValue, oldValue) {
            var container = Ext.getCmp('fieldOptionsContainer'),
              fieldOptions = Ext.getCmp('fieldOptions');

            if (newValue === 'select') {
              container.setVisible(true);
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
      name: 'required'
    }, {
      xtype: 'checkbox',
      fieldLabel: 'Read only',
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
        var formPanel = this.up('form'),
          form = formPanel.getForm(),
          vals = form.getValues(),
          parseVals,
          attributes = {},
          options = {},
          validation = {},
          programStep = Ext.data.StoreManager.lookup('ProgramStepStore'),
          programStepId = programStep.last().data.id,
          grid = Ext.getCmp('formFieldGrid');

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
            }
          };
        }());

        parseVals[vals.type] && parseVals[vals.type]();

        if (vals.read_only === 'on') {
          attributes.readonly = 'readonly';
        }

        if (vals.required === 'on') {
          validation.rule = 'notEmpty';
        }

        vals.attributes      = encodeObject(attributes);
        vals.options         = encodeObject(options);
        vals.validation      = encodeObject(validation);
        vals.program_step_id = programStepId;
        vals.name            = vals.label.underscore();

        grid.store.add(vals);
        form.reset();
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
          programStepId = programStep.last().data.id,
          grid = Ext.getCmp('formFieldGrid'),
          selectedRecord = grid.getSelectionModel().getSelection()[0];

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
            }
          };
        }());

        parseVals[vals.type] && parseVals[vals.type]();

        if (vals.read_only === 'on') {
          attributes.readonly = 'readonly';
        }

        if (vals.required === 'on') {
          validation.rule = 'notEmpty';
        }

        vals.attributes      = encodeObject(attributes);
        vals.options         = encodeObject(options);
        vals.validation      = encodeObject(validation);
        vals.program_step_id = programStepId;
        vals.name            = vals.label.underscore();

        selectedRecord.set(vals);
        form.reset();
      }
    }]
  }],
  preprocess: function () {
    var programStore = Ext.data.StoreManager.lookup('ProgramStore'),
      programStepStore = Ext.data.StoreManager.lookup('ProgramStepStore'),
      program = programStore.first(),
      programId;

    task = new Ext.util.DelayedTask(function () {
      programId = programStore.first().data.id;
      programStepStore.load({
        params: {
          program_id: programId
        }
      });
    });
    task.delay(1000);
  },
  process: function () {
    var programFormFieldStore = Ext.data.StoreManager.lookup('ProgramFormFieldStore');

    programFormFieldStore.sync();
    return true;
  }
});


/**
 * filingCategories
 */
filingCategories = Ext.create('Ext.form.Panel', {
  height: 406,
  items: [{
    border: 0,
    html: '<h1>Where would you like to file the registration snapshot?</h1>',
    margin: '0 0 10'
  }, {
    xtype: 'combo',
    allowBlank: false,
    displayField: 'name',
    fieldLabel: 'Filing Category 1',
    id: 'cat1Name',
    labelWidth: 150,
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
    name: 'cat_1',
    queryMode: 'local',
    store: 'Cat1Store',
    valueField: 'id',
    value: null
  },{
    xtype: 'combo',
    fieldLabel: 'Filing Category 2',
    name: 'cat_2',
    id: 'cat2Name',
    disabled: true,
    store: 'Cat2Store',
    displayField: 'name',
    valueField: 'id',
    queryMode: 'local',
    value: null,
    labelWidth: 150,
    listConfig: {
        getInnerTpl: function() {
            return '<div>{img}{name}</div>';
        }
    },
    allowBlank: false,
    listeners: {
      select: function(combo, records, Eopts) {
        var store = Ext.data.StoreManager.lookup('Cat3Store');

        if(records[0]) {
          Ext.getCmp('cat3Name').disable();
          Ext.getCmp('cat3Name').reset();
          store.load({params: {parentId: records[0].data.id}});
        }
      }
    }
  },{
    fieldLabel: 'Filing Category 3',
    name: 'cat_3',
    id: 'cat3Name',
    xtype: 'combo',
    store: 'Cat3Store',
    disabled: true,
    displayField: 'name',
    valueField: 'id',
    queryMode: 'local',
    value: null,
    labelWidth: 150,
    listConfig: {
        getInnerTpl: function() {
            return '<div>{img}{name}</div>';
        }
    },
    allowBlank: false
  }],
  process: function () {
    var form = this.getForm(),
      programDocumentStore = Ext.data.StoreManager.lookup('ProgramDocumentStore'),
      programStore = Ext.data.StoreManager.lookup('ProgramStore'),
      programStepStore = Ext.data.StoreManager.lookup('ProgramStepStore'),
      program,
      programStep,
      vals;

    if (form.isValid()) {
      vals = form.getValues();
      program = programStore.first();
      programStep = programStepStore.last();

      vals.name = program.data.name + " registration snapshot";
      vals.type = 'snapshot';
      vals.program_id = program.data.id;
      vals.program_step_id = programStep.data.id;
      programDocumentStore.add(vals);

      return true;
    }
  }
});

/**
 * instructions
 */
instructions = Ext.create('Ext.panel.Panel', {
  bodyPadding: 0,
  height: 406,
  layout: 'border',
  items: [{
    xtype: 'grid',
    frame: false,
    height: 156,
    id: 'instructionsGrid',
    region: 'center',
    store: 'ProgramInstructionStore',
    width: 660,
    columns: [{
      dataIndex: 'type',
      header: 'Type',
      flex: 1,
      renderer: function (value) {
        return value.humanize();
      }
    }],
    listeners: {
      select: function (rm, rec, index) {
        var editor = Ext.getCmp('editor'),
          saveBtn = Ext.getCmp('instructionSaveBtn');

        if (!rec.data.text) {
          rec.data.text = '';
        }

        editor.setValue(rec.data.text);
        saveBtn.enable();
      }
    },
    plugins: [
      Ext.create('Ext.grid.plugin.CellEditing', {
        clicksToEdit: 1
      })
    ]
  }, {
    xtype: 'form',
    layout: 'fit',
    region: 'south',
    height: 250,
    dockedItems: [{
      xtype: 'toolbar',
      dock: 'bottom',
      items: ['->', {
        disabled: true,
        id: 'instructionSaveBtn',
        text: 'Save Instruction',
        handler: function () {
          var grid = Ext.getCmp('instructionsGrid'),
            editor = Ext.getCmp('editor'),
            rec = grid.getSelectionModel().getSelection()[0];

          rec.set({text: editor.getValue()});
        }
      }]
    }],
    items: [{
      xtype: 'htmleditor',
      id: 'editor'
    }]
  }],
  preprocess: function () {
    var programStore = Ext.data.StoreManager.lookup('ProgramStore'),
      programStepStore = Ext.data.StoreManager.lookup('ProgramStepStore'),
      programInstructionStore = Ext.data.StoreManager.lookup('ProgramInstructionStore'),
      program = programStore.first(),
      programId = program.data.id,
      formStep;

    if (!program.data.approval_required) {
      var notApproved,
        pendingApproval;

      notApproved = programInstructionStore.findExact('type', 'not_approved');
      pendingApproval = programInstructionStore.findExact('type', 'pending_approval');

      if (notApproved !== -1) {
        programInstructionStore.removeAt(notApproved);
      }

      if (pendingApproval !== -1) {
        programInstructionStore.removeAt(pendingApproval);
      }
    }

    formStep = programStepStore.findRecord('type', /^form$/gi);

    programInstructionStore.each(function (rec) {
      rec.set({
        program_id: programId
      });
    });
    programInstructionStore.add({
      program_id: programId,
      program_step_id: formStep.data.id,
      text: program.data.name + ' Registration Form Step Instructions',
      type: (program.data.name + ' Registration Form Step Instructions').underscore()
    });
  },
  process: function () {
    var programInstructionStore = Ext.data.StoreManager.lookup('ProgramInstructionStore'),
      editor = Ext.getCmp('editor');

      programInstructionStore.sync();
      return true;
  }
});

/**
 * email
 */
emails = Ext.create('Ext.panel.Panel', {
  bodyPadding: 0,
  height: 500,
  layout: 'border',
  items: [{
    xtype: 'grid',
    frame: false,
    height: 156,
    id: 'emailGrid',
    region: 'center',
    store: 'ProgramEmailStore',
    width: 660,
    columns: [{
      dataIndex: 'type',
      header: 'Type',
      flex: 1,
      renderer: function (value) {
        return value.humanize();
      }
    }],
    listeners: {
      select: function (rm, rec, index) {
        var editor = Ext.getCmp('emailEditor'),
          fromField = Ext.getCmp('fromField'),
          subjectField = Ext.getCmp('subjectField'),
          form = Ext.getCmp('formPanel'),
          saveBtn = Ext.getCmp('emailSaveBtn');

        editor.setValue(rec.data.body);
        fromField.setValue(rec.data.from);
        subjectField.setValue(rec.data.subject);
        saveBtn.enable();
      }
    },
    plugins: [
      Ext.create('Ext.grid.plugin.CellEditing', {
        clicksToEdit: 1
      })
    ]
  }, {
    xtype: 'form',
    bodyPadding: '20 20 20 30',
    fieldDefaults: {
      labelAlign: 'top',
      msgTarget: 'side'
    },
    region: 'south',
    height: 350,
    items: [{
      xtype: 'container',
      anchor: '100%',
      layout: 'column',
      items: [{
        xtype: 'container',
        columnWidth: '.5',
        layout: 'anchor',
        items: [{
          xtype: 'textfield',
          fieldLabel: 'From',
          id: 'fromField',
          name: 'from',
          anchor: '96%'
        }, {
          xtype: 'textfield',
          fieldLabel: 'Subject',
          id: 'subjectField',
          name: 'subject',
          anchor: '96%'
        }]
      }]
    }, {
      xtype: 'htmleditor',
      fieldLabel: 'Body',
      id: 'emailEditor',
      name: 'body',
      margin: '18 0 0 0',
      height: 175,
      width: 878
    }],
    dockedItems: [{
      xtype: 'toolbar',
      dock: 'bottom',
      items: ['->', {
        disabled: true,
        id: 'emailSaveBtn',
        text: 'Save Email',
        handler: function () {
          var grid = Ext.getCmp('emailGrid'),
            from = Ext.getCmp('fromField'),
            subject = Ext.getCmp('subjectField'),
            editor = Ext.getCmp('emailEditor'),
            rec = grid.getSelectionModel().getSelection()[0];

          rec.set({
            body: editor.getValue(),
            subject: subject.getValue(),
            from: from.getValue()
          });
        }
      }]
    }]
  }],
  preprocess: function () {
    var programStore = Ext.data.StoreManager.lookup('ProgramStore'),
      programStepStore = Ext.data.StoreManager.lookup('ProgramStepStore'),
      programEmailStore = Ext.data.StoreManager.lookup('ProgramEmailStore'),
      program = programStore.first(),
      programId = program.data.id,
      formStep;

    if (!program.data.approval_required) {
      var notApproved,
        pendingApproval;

      notApproved = programEmailStore.findExact('type', 'not_approved');
      pendingApproval = programEmailStore.findExact('type', 'pending_approval');

      if (notApproved !== -1) {
        programEmailStore.removeAt(notApproved);
      }

      if (pendingApproval !== -1) {
        programEmailStore.removeAt(pendingApproval);
      }
    }

    formStep = programStepStore.findRecord('type', /^form$/gi);

    programEmailStore.each(function (rec) {
      rec.set({
        program_id: programId
      });
    });
    // add our step emails
    programEmailStore.add({
      program_id: programId,
      program_step_id: formStep.data.id,
      name: program.data.name + ' Registration Form Step Email',
      type: 'step',
      body: 'Your registration form step email',
      subject: 'Registration Form Complete',
      from: null
    });
  },
  process: function () {
    var programEmailStore = Ext.data.StoreManager.lookup('ProgramEmailStore'),
      editor = Ext.getCmp('emailEditor');

      programEmailStore.sync();
      return true;
  }
});

/**
 * navigate
 */
navigate = function (panel, direction) {
  var layout = panel.getLayout(),
    activeItem = layout.activeItem;

  if (direction === 'finish' && activeItem.process()) {
    Ext.Msg.alert('Success', 'Your program has been successfully saved.', function () {
      var task = new Ext.util.DelayedTask(function () {
        window.location = '/admin/programs';
      });

      task.delay(500);
    });
    return;
  }

  if (direction === 'prev' || activeItem.process()) {
    layout[direction]();

    if (typeof layout.activeItem.preprocess === 'function') {
      layout.activeItem.preprocess();
    }

    // Modify the toolbar buttons based on state
    Ext.getCmp('back').setDisabled(!layout.getPrev());
    if (!layout.getNext()) {
      Ext.getCmp('saveAndContinue').setDisabled(true).setVisible(false);
      Ext.getCmp('finish').setDisabled(false).setVisible(true);
    } else {
      Ext.getCmp('finish').setDisabled(true).setVisible(false);
      Ext.getCmp('saveAndContinue').setDisabled(false).setVisible(true);
    }
  }
};

/**
 * statusBar
 */
statusBar = Ext.create('Ext.ux.statusbar.StatusBar', {
  defaultIconCls: '',
  defaultText: '',
  dock: 'bottom',
  id: 'statusBar',
  items: [{
    disabled: true,
    id: 'back',
    text: 'Back',
    handler: function () {
      navigate(this.up('panel'), 'prev');
    }
  }, '-', {
    id: 'saveAndContinue',
    text: 'Save &amp; Continue',
    handler: function () {
      navigate(this.up('panel'), 'next');
    }
  }, {
    hidden: true,
    id: 'finish',
    text: 'Finish',
    handler: function () {
      navigate(this.up('panel'), 'finish');
    }
  }]
});

/**
 * Ext.onReady
 */
Ext.onReady(function () {

  Ext.create('Ext.panel.Panel', {
    defaults: {
      bodyPadding: 10
    },
    dockedItems: [ statusBar ],
    items: [
      registrationForm,
      formBuilder,
      filingCategories,
      instructions,
      emails
    ],
    layout: 'card',
    renderTo: 'registrationForm',
    title: 'New Program Registration'
  });

});
