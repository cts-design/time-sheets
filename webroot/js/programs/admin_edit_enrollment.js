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
    { name: 'rolling_registration', type: 'int' },
    { name: 'program_response_count', type: 'int' },
    { name: 'show_in_dash', type: 'int' },
    { name: 'paper_forms', type: 'int' },
    { name: 'download_docs', type: 'int' },
    { name: 'upload_docs', type: 'int' },
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
    { name: 'name', type: 'string', useNull: true },
    { name: 'type', type: 'string', useNull: true },
    { name: 'media_location', type: 'string', useNull: true },
    { name: 'media_type', type: 'string', useNull: true },
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

Ext.define('WatchedFilingCat', {
  extend: 'Ext.data.Model',
  fields: [
    { name: 'id', type: 'int' },
    { name: 'cat_id', type: 'int' },
    { name: 'cat_1', type: 'int' },
    'cat_1_name',
    { name: 'cat_2', type: 'int' },
    'cat_2_name',
    { name: 'cat_3', type: 'int' },
    'cat_3_name',
    { name: 'program_id', type: 'int' },
    { name: 'program_email_id', type: 'int' },
    'name',
  ]
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
        Ext.getCmp('watchedCat2Name').enable();
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
        Ext.getCmp('watchedCat3Name').enable();
      }
    }
  }
});

Ext.create('Ext.data.Store', {
  autoLoad: true,
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
      create: '/admin/programs/create_enrollment',
      read: '/admin/programs/read',
      update: '/admin/programs/update_enrollment'
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

Ext.create('Ext.data.TreeStore', {
  autoSync: true,
  model: 'ProgramStep',
  proxy: {
    type: 'ajax',
    api: {
      create: '/admin/program_steps/create',
      read: '/admin/program_steps/read_tree',
      update: '/admin/program_steps/update',
      destroy: '/admin/program_steps/destroy'
    },
    extraParams: {
      program_id: ProgramId
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
  },
  root: {
    expanded: true
  },
  storeId: 'ProgramStepStore'
});

Ext.create('Ext.data.Store', {
  model: 'ProgramStep',
  proxy: {
    type: 'ajax',
    api: {
      read: '/admin/program_steps/read_forms'
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
  },
  storeId: 'ProgramStepFormStore'
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
  autoSync: true,
  storeId: 'ProgramInstructionStore',
  model: 'ProgramInstruction',
  proxy: {
    api:{
      create: '/admin/program_instructions/create',
      read: '/admin/program_instructions/read',
      update: '/admin/program_instructions/update',
      destroy: '/admin/program_instructions/destroy'
    },
    type: 'ajax',
    reader: {
      type: 'json',
      root: 'program_instructions'
    },
    writer: {
      encode: true,
      root: 'program_instructions',
      writeAllFields: false
    }
  }
});

Ext.create('Ext.data.Store', {
  autoSync: true,
  storeId: 'ProgramEmailStore',
  model: 'ProgramEmail',
  proxy: {
    api:{
      create: '/admin/program_emails/create',
      read: '/admin/program_emails/read',
      update: '/admin/program_emails/update',
      destroy: '/admin/program_emails/destroy'
    },
    type: 'ajax',
    reader: {
      type: 'json',
      root: 'program_emails'
    },
    writer: {
      encode: true,
      root: 'program_emails',
      writeAllFields: false
    }
  }
});

Ext.create('Ext.data.Store', {
  autoSync: true,
  storeId: 'WatchedFilingCatStore',
  model: 'WatchedFilingCat',
  proxy: {
    api:{
      create: '/admin/program_documents/create_watched_cat',
      read: '/admin/program_documents/read_watched_cat',
      update: '/admin/program_documents/update_watched_cat',
      destroy: '/admin/program_documents/destroy_watched_cat'
    },
    type: 'ajax',
    reader: {
      type: 'json',
      root: 'cats'
    },
    writer: {
      allowSingle: false,
      encode: true,
      root: 'cats',
      writeAllFields: false
    }
  }
});

/**
 * Variable Declarations
 */
var registrationForm, stepTree, formBuilderContainer, uploadStep, watchedFilingCats, filingCategories, programDocumentation, instructions, emails, navigate, statusBar, states;

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
  autoScroll: true,
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
      fieldLabel: 'Esign Required?',
      labelWidth: 190,
      items: [{
        boxLabel: 'Yes',
        name: 'form_esign_required',
        inputValue: '1'
      }, {
        boxLabel: 'No',
        name: 'form_esign_required',
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
    xtype: 'hiddenfield',
    name: 'type',
    value: 'enrollment'
  }, {
    xtype: 'hiddenfield',
    name: 'approval_required',
    value: 1
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
    height: 22,
    width: 300,
    layout: {
      align: 'stretch',
      type: 'vbox'
    },
    items: [{
      xtype: 'radiogroup',
      fieldLabel: 'Expiration resets on user login',
      labelWidth: 190,
      items: [{
        boxLabel: 'Yes',
        name: 'rolling_registration',
        inputValue: '1',
        checked: true
      }, {
        boxLabel: 'No',
        name: 'rolling_registration',
        inputValue: '0'
      }]
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
      id: 'sendExpiringSoon',
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
    xtype: 'fieldcontainer',
    height: 24,
    width: 250,
    layout: {
      align: 'stretch',
      type: 'vbox'
    },
    items: [{
      xtype: 'textfield',
      allowBlank: false,
      fieldLabel: 'Confirmation Id Length',
      labelWidth: 190,
      name: 'confirmation_id_length'
    }]
  }, {
    border: 0,
    html: '<h1>Program Documentation</h1>',
    margin: '0 0 10'
  }, {
    xtype: 'fieldcontainer',
    height: 50,
    width: 500,
    layout: {
      align: 'stretch',
      type: 'vbox'
    },
    items: [{
      xtype: 'radiogroup',
      fieldLabel: 'Will this enrollment have paper forms populated with the data collected from the user?',
      labelAlign: 'top',
      labelWidth: 375,
      items: [{
        boxLabel: 'Yes',
        name: 'paper_forms',
        inputValue: '1',
        checked: true
      }, {
        boxLabel: 'No',
        name: 'paper_forms',
        inputValue: '0'
      }]
    }]
  }, {
    xtype: 'fieldcontainer',
    height: 50,
    width: 500,
    layout: {
      align: 'stretch',
      type: 'vbox'
    },
    items: [{
      xtype: 'radiogroup',
      fieldLabel: 'Will this enrollment require users to upload documents?',
      items: [{
        boxLabel: 'Yes',
        id: 'uploadDocsYes',
        name: 'upload_docs',
        inputValue: '1',
        checked: true
      }, {
        boxLabel: 'No',
        name: 'upload_docs',
        inputValue: '0'
      }],
      labelAlign: 'top',
      labelWidth: 375,
      listeners: {
        change: function (cbgroup, newVal, oldVal) {
          var container = this.up('form').down('#documentQueueCategoryContainer'),
            field = this.up('form').down('#documentQueueCategoryField');

          if (typeof newVal.upload_docs !== "string") {
            return;
          } else {
            if (newVal.upload_docs === '1') {
              container.setVisible(true);
              container.getEl().highlight('C9DFEE', { duration: 1000 });
            } else {
              container.setVisible(false);
            }
          }
        }
      }
    }]
  }, {
    xtype: 'fieldcontainer',
    height: 24,
    id: 'documentQueueCategoryContainer',
    width: 350,
    layout: {
      align: 'stretch',
      type: 'vbox'
    },
    items: [{
      xtype: 'combo',
      allowBlank: false,
      displayField: 'name',
      fieldLabel: 'Document Queue Category',
      id: 'documentQueueCategoryField',
      labelWidth: 175,
      name: 'queue_category_id',
      queryMode: 'local',
      store: 'DocumentQueueCategoryStore',
      value: '',
      valueField: 'id',
      width: 200
    }]
  }, {
    xtype: 'hiddenfield',
    name: 'user_acceptance_required',
    value: '0'
  }, {
    xtype: 'hiddenfield',
    name: 'form_esign_required',
    value: '0'
  }, {
    xtype: 'hiddenfield',
    name: 'in_test',
    value: 1
  }, {
    xtype: 'hiddenfield',
    name: 'disabled',
    value: 1
  }],
  listeners: {
    activate: function () {
      var programStore = Ext.data.StoreManager.lookup('ProgramStore'),
        form = this;

      form.getEl().mask('Loading...');

      programStore.load({
        callback: function (recs, op, success) {
          var rec,
            expiringSoonString;

          if (success) {
            rec = recs[0];
            expiringSoonString = rec.data.send_expiring_soon.toString();

            form.loadRecord(rec);
            form.down('#sendExpiringSoon').setValue(expiringSoonString);

            if (rec.data.queue_category_id) {
              form.down('#uploadDocsYes').setValue(1);
            }

            if (!rec.data.in_test) {
              form.down('#approvalRequired').disable();
              form.down('#esignRequired').disable();
              form.down('#registrationType').disable();
              form.down('#responsesExpireIn').disable();
            }

            form.getEl().unmask();
          }
        },
        params: {
          program_id: ProgramId
        }
      });
    }
  },
  process: function () {
    var form = this.getForm(),
      programStore = Ext.data.StoreManager.lookup('ProgramStore'),
      record,
      vals;

    statusBar.showBusy();

    if (form.isValid()) {
      vals = form.getValues();
      record = programStore.first();
      record.set(vals);
    } else {
      return false;
    }

    statusBar.clearStatus();
    return true;
  }
});

/**
 * stepTree
 */
stepTree = Ext.create('Ext.panel.Panel', {
  bodyPadding: 0,
  height: 406,
  layout: 'border',
  items: [{
    xtype: 'treepanel',
    frame: false,
    dockedItems: [{
      xtype: 'toolbar',
      dock: 'top',
      items: [{
        icon: '/img/icons/add.png',
        id: 'addModuleBtn',
        text: 'Add Module',
        handler: function () {
          var programStepStore = Ext.data.StoreManager.lookup('ProgramStepStore'),
            program = Ext.data.StoreManager.lookup('ProgramStore').first(),
            root = programStepStore.getRootNode();

          Ext.Msg.prompt('Module Name', 'What would you like to name this module?', function (btn, text) {
            if (btn === 'ok') {
              root.appendChild({
                expandable: true,
                expanded: true,
                leaf: false,
                name: text,
                program_id: program.data.id
              });
            }
          });
        }
      }, {
        disabled: true,
        icon: '/img/icons/delete.png',
        id: 'deleteModuleBtn',
        text: 'Delete Module',
        handler: function () {
          var selectedModule = this.up('panel').getSelectionModel().getSelection()[0];
          Ext.Msg.show({
            title: 'Are you sure?',
            msg: 'Are you sure you want to delete this module?',
            buttons: Ext.Msg.YESNO,
            icon: Ext.Msg.QUESTION,
            fn: function (btn) {
              if (btn === 'yes') { selectedModule.remove(); }
            }
          });
        }
      }]
    }],
    height: 350,
    id: 'gridTreePanel',
    region: 'west',
    rootVisible: true,
    store: 'ProgramStepStore',
    width: 660,
    columns: [{
      xtype: 'treecolumn',
      dataIndex: 'name',
      text: 'Name',
      flex: 1,
      sortable: false
    }, {
      dataIndex: 'type',
      text: 'Type'
  }],
    listeners: {
      itemcontextmenu: function (view, rec, item, index, e) {
        var programStepStore = Ext.data.StoreManager.lookup('ProgramStepStore'),
          menu;

        e.preventDefault();

        if (!rec.isLeaf()) {
          menu = Ext.create('Ext.menu.Menu', {
            items: [{
              icon: '/img/icons/edit.png',
              text: 'Rename Module',
              handler: function () {
                Ext.Msg.prompt(
                  'Rename Module',
                  'What would you like to rename the module to',
                  function (btn, value) {
                    var root = programStepStore.getRootNode(),
                      oldRec,
                      newRec;

                    oldRec = newRec = rec;

                    newRec.name = value;

                    if (btn === 'ok') {
                      rec.set({ name: value });
                      rec.save();
                      //programStepStore.sync();
                    }
                  }
                );
              }
            }]
          }).showAt(e.getXY());
        }
      },
      select: function (rm, rec, index) {
        var deleteModuleBtn = Ext.getCmp('deleteModuleBtn'),
          addStepBtn = Ext.getCmp('addStepBtn'),
          deleteStepBtn = Ext.getCmp('deleteStepBtn');

        if (!rec.isLeaf()) {
          deleteModuleBtn.enable();
          addStepBtn.enable();
          deleteStepBtn.disable();
        } else {
          deleteStepBtn.enable();
          deleteModuleBtn.disable();
        }
      }
    }
  }, {
    xtype: 'form',
    bodyPadding: 10,
    dockedItems: [{
      xtype: 'toolbar',
      dock: 'top',
      items: [{
        disabled: true,
        icon: '/img/icons/add.png',
        id: 'addStepBtn',
        text: 'Add Step',
        handler: function () {
          var formPanel = this.up('form');
          formPanel.enable();
        }
      }, {
        disabled: true,
        icon: '/img/icons/delete.png',
        id: 'deleteStepBtn',
        text: 'Delete Step',
        handler: function () {
        }
      }]
    }],
    margin: '0 0 15',
    region: 'center',
    items: [{
      xtype: 'textfield',
      allowBlank: false,
      fieldLabel: 'Name',
      name: 'name'
    }, {
      xtype: 'fieldcontainer',
      id: 'stepTypeContainer',
      items: [{
        xtype: 'combo',
        allowBlank: false,
        displayField: 'ucase',
        fieldLabel: 'Step Type',
        id: 'stepType',
        listeners: {
          change: {
            fn: function (field, newValue, oldValue) {
              var container = Ext.getCmp('mediaTypeContainer'),
                typeField = Ext.getCmp('mediaTypeField'),
                uploadContainer = Ext.getCmp('mediaUploadContainer'),
                uploadField = Ext.getCmp('mediaUploadField'),
                urlContainer = Ext.getCmp('mediaUrlContainer'),
                urlField = Ext.getCmp('mediaUrlField');

              if (newValue === 'media') {
                container.setVisible(true);
                uploadContainer.setVisible(true);
                container.getEl().highlight('C9DFEE', { duration: 1000 });
                uploadContainer.getEl().highlight('C9DFEE', { duration: 1000 });
                typeField.allowBlank = false;
                typeField.setValue('pdf');
                uploadField.allowBlank = false;
              } else {
                container.setVisible(false);
                typeField.allowBlank = true;
                typeField.setValue('pdf');
                uploadField.allowBlank = true;
                uploadContainer.setVisible(false);
                urlField.allowBlank = true;
                urlContainer.setVisible(false);
              }
            }
          }
        },
        name: 'type',
        queryMode: 'local',
        store: Ext.create('Ext.data.Store', {
          fields: ['lcase', 'ucase'],
          data: [{
            lcase: 'custom_form', ucase: 'Custom Form'
          }, {
            lcase: 'form', ucase: 'Form'
          }, {
            lcase: 'media', ucase: 'Media'
          }, {
            lcase: 'required_docs', ucase: 'Document Upload'
          }]
        }),
        value: '',
        valueField: 'lcase'
      }]
    }, {
      xtype: 'fieldcontainer',
      hidden: true,
      id: 'mediaTypeContainer',
      items: [{
        xtype: 'combo',
        allowBlank: true,
        displayField: 'ucase',
        fieldLabel: 'Media Type',
        id: 'mediaTypeField',
        listeners: {
          change: {
            fn: function (field, newValue, oldValue) {
              var container = Ext.getCmp('mediaUploadContainer'),
                uploadField = Ext.getCmp('mediaUploadField'),
                urlContainer = Ext.getCmp('mediaUrlContainer'),
                urlField = Ext.getCmp('mediaUrlField');

              if (newValue !== 'url') {
                container.setVisible(true);
                uploadField.allowBlank = false;
                urlContainer.setVisible(false);
                urlField.allowBlank = true;
                if (oldValue === 'url') {
                  container.getEl().highlight('C9DFEE', { duration: 1000 });
                }
              } else {
                container.setVisible(false);
                uploadField.allowBlank = true;
                urlContainer.setVisible(true);
                urlField.allowBlank = false;
                urlContainer.getEl().highlight('C9DFEE', { duration: 1000 });
              }
            }
          }
        },
        name: 'media_type',
        queryMode: 'local',
        store: Ext.create('Ext.data.Store', {
          fields: ['lcase', 'ucase'],
          data: [{
            lcase: 'pdf', ucase: 'PDF Document'
          }, {
            lcase: 'video', ucase: 'Flash Video'
          }, {
            lcase: 'url', ucase: 'Website URL'
          }]
        }),
        value: null,
        valueField: 'lcase'
      }]
    }, {
      xtype: 'fieldcontainer',
      hidden: true,
      id: 'mediaUploadContainer',
      items: [{
        xtype: 'filefield',
        allowBlank: true,
        fieldLabel: 'Media Upload',
        id: 'mediaUploadField',
        name: 'media'
      }]
    }, {
      xtype: 'fieldcontainer',
      hidden: true,
      id: 'mediaUrlContainer',
      items: [{
        xtype: 'textfield',
        allowBlank: true,
        fieldLabel: 'Media URL',
        id: 'mediaUrlField',
        name: 'media_location'
      }]
    }],
    buttons: [{
      id: 'stepSaveBtn',
      text: 'Save',
      handler: function () {
        var formPanel = this.up('form'),
          form = formPanel.getForm(),
          treePanel = formPanel.up('panel').down('treepanel'),
          selectedModule = treePanel.getSelectionModel().getSelection()[0],
          sm = Ext.data.StoreManager,
          programStepStore = sm.lookup('ProgramStepStore'),
          program = sm.lookup('ProgramStore').first(),
          vals,
          processStepType;

        if (form.isValid()) {
          vals = form.getValues();
          vals.leaf = true;
          vals.program_id = program.data.id;

          if (!selectedModule) {
            Ext.Msg.alert('Please select a module',
                'Please select a module to add steps to');
            return false;
          }

          if (selectedModule.isLeaf()) { selectedModule = selectedModule.parentNode; }

          switch (vals.type) {
            case 'custom_form':
            case 'form':
              selectedModule.appendChild(vals);
              form.reset();
              break;

            case 'media':
              if (vals.media_type === 'url' && vals.media_location) {
                selectedModule.appendChild(vals);
                form.reset();
              } else {
                form.submit({
                  url: '/admin/programs/upload_media',
                  waitMsg: 'Uploading Media...',
                  scope: this,
                  success: function (form, action) {
                    vals.media_location = action.result.url;
                    selectedModule.appendChild(vals);
                    form.reset();
                  },
                  failure: function (form, action) {
                    Ext.Msg.alert('Could not upload file', action.result.msg);
                  }
                });
              }
              break;

            case 'required_docs':
              treePanel.getRootNode()
                       .lastChild
                       .appendChild(vals);
              break;

            default:
              Ext.Msg.alert('Something Went Wrong',
                  'Your media type is invalid. Please check it and try again');
              break;
          }
        }
      }
    }, {
      disabled: true,
      formBind: true,
      hidden: true,
      id: 'updateBtn',
      text: 'Update',
      handler: function () {
      }
    }]
  }],
  preprocess: function () {
    var programStepStore = Ext.data.StoreManager.lookup('ProgramStepStore'),
      me = this;

    Ext.Ajax.request({
      url: '/admin/program_steps/read',
      params: {
        program_id: ProgramId
      },
      success: function (response) {
        var programSteps = Ext.JSON.decode(response.responseText).program_steps,
          rootNode = programStepStore.getRootNode(),
          i;

        for (i = 0, l = programSteps.length; i < l; i++) {
          var rec = programSteps[i],
            parent;

          if (!rec.type) {
            rootNode.appendChild(rec);
          } else {
            parent = rootNode.findChild('id', rec.parent_id);
            rec.parentId = rec.parent_id;
            rec.leaf = true;
            parent.appendChild(rec);
          }
        }
      }
    });
  },
  process: function () {
    var programStore = Ext.data.StoreManager.lookup('ProgramStore'),
      program = programStore.first(),
      treePanel = this.down('treepanel'),
      lastStep = treePanel.getRootNode().lastChild.lastChild;

    if (program.data.upload_docs) {
      if (!lastStep || lastStep.data.type !== 'required_docs') {
        Ext.Msg.alert('Error',
            'You have specified this program requires documents to be uploaded, but have not added the required step');
        return false;
      }
    }

    Ext.data.StoreManager.lookup('ProgramStepStore').sync();
    return true;
  }
});

/**
 * formBuilderContainer
 */
formBuilderContainer = Ext.create('Ext.panel.Panel', {
  bodyPadding: 0,
  height: 406,
  layout: 'fit',
  items: [{
    xtype: 'grid',
    frame: false,
    height: 350,
    id: 'ProgramFormStepGrid',
    region: 'west',
    store: 'ProgramStepFormStore',
    columns: [{
      header: 'Name',
      dataIndex: 'name',
      flex: 1
    }],
    listeners: {
      itemdblclick: function (view, rec) {
        var window,
          programFormFieldStore = Ext.data.StoreManager.lookup('ProgramFormFieldStore');

        programFormFieldStore.load({
          params: {
            program_step_id: rec.data.id
          }
        });

        if (!window) {
          window = Ext.widget('window', {
            height: 466,
            dockedItems: [{
              xtype: 'toolbar',
              dock: 'bottom',
              items: [{
                xtype: 'button',
                text: 'Save & Close',
                handler: function () {
                  var win = this.up('window');

                  programFormFieldStore.sync();
                  win.close();
                }
              }]
            }],
            items: [{
              xtype: 'panel',
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
                listeners: {
                  select: function (rm, rec, index) {
                    var formPanel = Ext.getCmp('formPanel'),
                      form = formPanel.getForm(),
                      deleteFieldBtn = Ext.getCmp('deleteFieldBtn'),
                      updateBtn = Ext.getCmp('updateBtn'),
                      builderSaveBtn = Ext.getCmp('builderSaveBtn');

                    form.loadRecord(rec);
                    deleteFieldBtn.enable();
                    updateBtn.show();
                    builderSaveBtn.hide();
                  }
                },
                viewConfig: {
                  emptyText: 'Please add your form fields',
                  listeners: {
                    drop: function (node, data, overModel, dropPos, eOpts) {
                      var programFormFieldStore = Ext.data.StoreManager.lookup('ProgramFormFieldStore'),
                        grid = data.view.up('#formFieldGrid'),
                        gridEl = grid.getEl(),
                        selectedRec = data.records[0],
                        parseDrop,
                        i;

                      gridEl.mask('Reordering fields...');

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

                            selectedRec.set('order', (overModelOrder));
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

                      gridEl.unmask();
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
                      lcase: 'textarea', ucase: 'Textarea'
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
                  xtype: 'textfield',
                  allowBlank: false,
                  fieldLabel: 'Correct Answer',
                  name: 'answer'
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
                      programStepId,
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

                    vals.attributes      = encodeObject(attributes);
                    vals.options         = encodeObject(options);
                    vals.validation      = encodeObject(validation);
                    vals.program_step_id = rec.get('id');
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

                    vals.attributes      = encodeObject(attributes);
                    vals.options         = encodeObject(options);
                    vals.validation      = encodeObject(validation);
                    vals.program_step_id = programStepId;
                    vals.name            = vals.label.underscore();

                    selectedRecord.set(vals);
                    form.reset();
                  }
                }]
              }]
            }],
            modal: true,
            title: 'Edit Form Step: ' + rec.get('name'),
            width: 970
          }).show();
        } else {
          window.show();
        }
      }
    }
  }],
  preprocess: function () {
    var me = this,
      program = Ext.data.StoreManager.lookup('ProgramStore').first(),
      programStepFormStore = Ext.data.StoreManager.lookup('ProgramStepFormStore'),
      grid = Ext.getCmp('ProgramFormStepGrid'),
      task = new Ext.util.DelayedTask(function () {
        programStepFormStore.load({
          callback: function (recs, ops, success) {
            if (!recs) {
              navigate(me.up('panel'), 'next');
            }
          },
          params: {
            program_id: program.data.id
          }
        });
      });

    task.delay(1000);
  },
  process: function () {
    return true;
  }
});

/**
 * uploadStep
 */
uploadStep = Ext.create('Ext.panel.Panel', {
  bodyPadding: 0,
  height: 406,
  layout: 'border',
  items: [{
    xtype: 'grid',
    frame: false,
    height: 350,
    id: 'documentGrid',
    region: 'west',
    store: 'ProgramDocumentStore',
    width: 660,
    columns: [{
      header: 'Program Step Id',
      dataIndex: 'program_step_id',
      hidden: true
    }, {
      header: 'Name',
      dataIndex: 'name',
      flex: 1,
      renderer: function (val) {
        return val.humanize();
      }
    }, {
      header: 'Type',
      dataIndex: 'type',
      flex: 1,
      renderer: function (value) {
        return value.humanize();
      }
    }, {
      header: 'Template',
      dataIndex: 'template'
    }, {
      header: 'Category 1',
      dataIndex: 'cat_1'
    }, {
      header: 'Category 2',
      dataIndex: 'cat_2'
    }, {
      header: 'Category 3',
      dataIndex: 'cat_3'
    }, {
      header: 'Type',
      dataIndex: 'type',
      hidden: true
    }],
    listeners: {
      select: function (rm, rec, index) {
        console.log('test');
      }
    },
    viewConfig: {
      emptyText: 'Please add your documents',
    }
  }, {
    xtype: 'form',
    bodyPadding: 10,
    dockedItems: [{
      xtype: 'toolbar',
      dock: 'top',
      items: [{
        icon: '/img/icons/add.png',
        id: 'addDocumentBtn',
        text: 'Add Document',
        handler: function () {
        }
      }, {
        disabled: true,
        icon: '/img/icons/delete.png',
        id: 'deleteDocumentBtn',
        text: 'Delete Document',
        handler: function () {
        }
      }]
    }],
    id: 'uploadStepForm',
    margin: '0 0 15',
    region: 'center',
    items: [{
      xtype: 'textfield',
      allowBlank: false,
      fieldLabel: 'Name',
      name: 'name'
    }, {
      xtype: 'combo',
      allowBlank: false,
      displayField: 'ucase',
      fieldLabel: 'Type',
      id: 'documentType',
      listeners: {
        select: function(combo, records, Eopts) {
        }
      },
      name: 'type',
      queryMode: 'local',
      store: Ext.create('Ext.data.Store', {
        fields: ['lcase', 'ucase'],
        data: [{
          lcase: 'certificate', ucase: 'Certificate'
        }, {
          lcase: 'download', ucase: 'Document Download'
        }, {
          lcase: 'upload', ucase: 'Document Upload'
        }, {
          lcase: 'pdf', ucase: 'Enrollment Form'
        }, {
          lcase: 'multisnapshot', ucase: 'Multi-Snapshot'
        }]
      }),
      valueField: 'lcase',
      value: null
    }, {
      xtype: 'filefield',
      allowBlank: true,
      fieldLabel: 'Document',
      id: 'documentUploadField',
      name: 'document'
    }, {
      xtype: 'combo',
      allowBlank: false,
      displayField: 'name',
      fieldLabel: 'Filing Category 1',
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
      listConfig: {
          getInnerTpl: function() {
              return '<div>{img}{name}</div>';
          }
      },
      allowBlank: false
    }],
    buttons: [{
        id: 'documentSaveBtn',
        text: 'Save',
        handler: function () {
          var formPanel = Ext.getCmp('uploadStepForm'),
            form = formPanel.getForm(),
            uploadField = formPanel.down('#documentUploadField'),
            sm = Ext.data.StoreManager,
            programDocumentStore = sm.lookup('ProgramDocumentStore'),
            programEmailStore = sm.lookup('ProgramEmailStore'),
            program = sm.lookup('ProgramStore').first();

          if (form.isValid()) {
            vals = form.getValues();
            vals.program_id = program.data.id;

            if (uploadField.getValue()) {
              form.submit({
                url: '/admin/program_documents/upload',
                waitMsg: 'Uploading Document...',
                success: function (form, action) {
                  form.reset();
                  vals.template = action.result.url;
                  programDocumentStore.add(vals);
                  programEmailStore.add({
                    program_id: vals.program_id,
                    to: null,
                    from: null,
                    subject: vals.name + ' Email',
                    body: 'Email for ' + vals.name,
                    type: 'document',
                    name: vals.name + ' Document Email'
                  });
                },
                failure: function (form, action) {
                  Ext.Msg.alert('Could not upload file', action.result.msg);
                }
              });
            } else {
              form.reset();
              programDocumentStore.add(vals);
              programEmailStore.add({
                program_id: vals.program_id,
                to: null,
                from: null,
                subject: vals.name + ' Email',
                body: 'Email for ' + vals.name,
                type: 'document',
                name: vals.name + ' Document Email'
              });
            }
          }
      }
    }, {
      disabled: true,
      formBind: true,
      hidden: true,
      id: 'documentUpdateBtn',
      text: 'Update',
      handler: function () {
      }
    }]
  }],
  preprocess: function () {
    var storeMgr = Ext.data.StoreManager,
      program = storeMgr.lookup('ProgramStore').first(),
      programDocumentStore = storeMgr.lookup('ProgramDocumentStore');

      programDocumentStore.load({
        callback: function (recs, ops, success) {

        },
        params: {
          program_id: program.data.id
        }
      });
  },
  process: function () {
    return true;
  }
});

/**
 * watchedFilingCats step
 */
watchedFilingCats = Ext.create('Ext.panel.Panel', {
  bodyPadding: 0,
  height: 406,
  layout: 'border',
  items: [{
    xtype: 'grid',
    frame: false,
    height: 350,
    id: 'watchedFilingCatsGrid',
    region: 'west',
    store: 'WatchedFilingCatStore',
    width: 660,
    columns: [{
      header: 'Name',
      dataIndex: 'name',
      flex: 1,
      renderer: function (val) {
        return val.humanize();
      }
    }, {
      header: 'Category',
      dataIndex: 'cat_3_name',
      flex: 1,
      renderer: function (val, tdStyle, rec) {
        console.log(rec);
        if (rec.data.cat_3) {
          return rec.data.cat_3_name;
        } else if (rec.data.cat_2) {
          return rec.data.cat_2_name;
        } else if (rec.data.cat_1) {
          return rec.data.cat_1_name;
        }
      }
    }, {
      header: 'Program Email Id',
      dataIndex: 'program_email_id',
      renderer: function (val) {
        if (val) {
          return val;
        } else {
          return 'N/A';
        }
      }
    }],
    listeners: {
      select: function (rm, rec, index) {
        var form = Ext.getCmp('watchedFilingCatsForm'),
          Cat1Store = Ext.data.StoreManager.lookup('Cat1Store'),
          Cat2Store = Ext.data.StoreManager.lookup('Cat2Store'),
          Cat3Store = Ext.data.StoreManager.lookup('Cat3Store'),
          watchedCat1Name = Ext.getCmp('watchedCat1Name'),
          watchedCat2Name = Ext.getCmp('watchedCat2Name'),
          watchedCat3Name = Ext.getCmp('watchedCat3Name'),
          deleteWatchedFilingCatBtn = Ext.getCmp('deleteWatchedFilingCatBtn');

        form.getEl().mask('Loading...');

        Cat2Store.load({
          params: {
            parentId: rec.data.cat_1
          }
        });

        Cat3Store.load({
          params: {
            parentId: rec.data.cat_2
          }
        });

        form.loadRecord(rec);
        form.getEl().unmask();
        deleteWatchedFilingCatBtn.enable();
      }
    },
    selModel: {
      allowDeselect: true,
      mode: 'SINGLE'
    },
    viewConfig: {
      emptyText: 'Please add your watched filing categories',
    }
  }, {
    xtype: 'form',
    bodyPadding: 10,
    dockedItems: [{
      xtype: 'toolbar',
      dock: 'top',
      items: [{
        icon: '/img/icons/add.png',
        id: 'addWatchedFilingCatBtn',
        text: 'Add Watched Category',
        handler: function () {
          var grid = Ext.getCmp('watchedFilingCatsGrid'),
            formPanel = Ext.getCmp('watchedFilingCatsForm'),
            form = formPanel.getForm();

          form.reset();
          grid.getSelectionModel().deselectAll();
        }
      }, {
        disabled: true,
        icon: '/img/icons/delete.png',
        id: 'deleteWatchedFilingCatBtn',
        text: 'Delete Watched Category',
        handler: function () {
          var grid = Ext.getCmp('watchedFilingCatsGrid'),
            selectedRecord = grid.getSelectionModel().getSelection()[0],
            store = Ext.data.StoreManager.lookup('WatchedFilingCatStore');

          Ext.Msg.show({
            title: 'Are you sure?',
            msg: 'Are you sure you want to delete this watched filing category?',
            buttons: Ext.Msg.YESNO,
            icon: Ext.Msg.QUESTION,
            fn: function (btn) {
              if (btn === 'yes') { store.remove(selectedRecord); }
            }
          });

        }
      }]
    }],
    id: 'watchedFilingCatsForm',
    margin: '0 0 15',
    region: 'center',
    items: [{
      xtype: 'textfield',
      allowBlank: false,
      fieldLabel: 'Name',
      name: 'name'
    }, {
      xtype: 'combo',
      allowBlank: false,
      displayField: 'name',
      fieldLabel: 'Filing Category 1',
      id: 'watchedCat1Name',
      listConfig: {
          getInnerTpl: function() {
              return '<div>{img}{name}</div>';
          }
      },
      listeners: {
        select: function(combo, records, Eopts) {
          var store = Ext.data.StoreManager.lookup('Cat2Store');

          if(records[0]) {
            Ext.getCmp('watchedCat2Name').disable();
            Ext.getCmp('watchedCat2Name').reset();
            Ext.getCmp('watchedCat3Name').disable();
            Ext.getCmp('watchedCat3Name').reset();
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
      id: 'watchedCat2Name',
      disabled: true,
      store: 'Cat2Store',
      displayField: 'name',
      valueField: 'id',
      queryMode: 'local',
      value: null,
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
            Ext.getCmp('watchedCat3Name').disable();
            Ext.getCmp('watchedCat3Name').reset();
            store.load({params: {parentId: records[0].data.id}});
          }
        }
      }
    },{
      fieldLabel: 'Filing Category 3',
      name: 'cat_3',
      id: 'watchedCat3Name',
      xtype: 'combo',
      store: 'Cat3Store',
      disabled: true,
      displayField: 'name',
      valueField: 'id',
      queryMode: 'local',
      value: null,
      listConfig: {
          getInnerTpl: function() {
              return '<div>{img}{name}</div>';
          }
      },
      allowBlank: false
    }],
    buttons: [{
        id: 'watchedFilingCatSaveBtn',
        text: 'Save',
        handler: function () {
          var formPanel = Ext.getCmp('watchedFilingCatsForm'),
            form = formPanel.getForm(),
            watchedFilingCatStore = Ext.data.StoreManager.lookup('WatchedFilingCatStore');

          if (form.isValid()) {
            vals = form.getValues();
            vals.program_id = ProgramId;

            if (vals.cat_3) {
              vals.cat_id = vals.cat_3;
              delete(vals.cat_3);
            } else if (vals.cat_2) {
              vals.cat_id = vals.cat_2;
            } else {
              vals.cat_id = vals.cat_1;
            }

            form.reset();
            watchedFilingCatStore.add(vals);
          }
      }
    }, {
      disabled: true,
      formBind: true,
      hidden: true,
      id: 'documentUpdateBtn',
      text: 'Update',
      handler: function () {
      }
    }]
  }],
  preprocess: function () {
    var storeMgr = Ext.data.StoreManager,
      program = storeMgr.lookup('ProgramStore').first(),
      watchedFilingCatStore = storeMgr.lookup('WatchedFilingCatStore');

    watchedFilingCatStore.load({
      callback: function (recs, ops, success) {

      },
      params: {
        program_id: program.data.id
      }
    });
  },
  process: function () {
    return true;
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
          instructionSaveBtn = Ext.getCmp('instructionSaveBtn');

        if (!rec.data.text) { rec.data.text = ''; }

        editor.setValue(rec.data.text);
        instructionSaveBtn.enable();
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
  listeners: {
    activate: function () {
      Ext.data.StoreManager.lookup('ProgramInstructionStore').load({
        params: {
          program_id: ProgramId
        }
      });
    }
  },
  process: function () {
    Ext.data.StoreManager.lookup('ProgramInstructionStore').sync();
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
    autoScroll: true,
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

        if (!rec.data.body) {
          rec.data.text = '';
        }

        if (!rec.data.from) {
          rec.data.text = '';
        }

        if (!rec.data.subject) {
          rec.data.subject = '';
        }

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
    Ext.data.StoreManager.lookup('ProgramEmailStore').load({
      params: {
        program_id: ProgramId
      }
    });
  },
  process: function () {
    Ext.data.StoreManager.lookup('ProgramEmailStore').sync();
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
  text: 'Step 1 of 5',
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
  var items;

  if (RoleId === '2') {
    items = [
      registrationForm,
      stepTree,
      formBuilderContainer,
      uploadStep,
      watchedFilingCats,
      instructions,
      emails
    ];
  } else {
    items = [
      instructions,
      emails
    ];
  }

  Ext.create('Ext.panel.Panel', {
    defaults: {
      bodyPadding: 10
    },
    dockedItems: [ statusBar ],
    items: items,
    layout: 'card',
    renderTo: 'editPanel',
    title: 'New Program Enrollment'
  });

});
