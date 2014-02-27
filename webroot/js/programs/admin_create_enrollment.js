var encodeObject = function (obj) {
  if (Object.keys(obj).length) {
    return Ext.JSON.encode(obj);
  }
  return null;
},
productionURL = function () {
  if (!Ext.isChrome) {
    return window.location.protocol + '//' + window.location.host;
  }

  return window.location.origin;
},
instructionsSaved = false,
emailsSaved = false;

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
    'media_acknowledgement_text',
    { name: 'queue_category_id', type: 'int' },
    { name: 'approval_required', type: 'int' },
    { name: 'form_esign_required', type: 'int' },
    { name: 'user_acceptance_required', type: 'int' },
    { name: 'confirmation_id_length', type: 'int' },
    { name: 'response_expires_in', type: 'int' },
    { name: 'send_expiring_soon', type: 'int' },
    { name: 'rolling_expiration', type: 'int' },
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
    'meta',
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
    'fieldset',
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
    { name: 'disabled', type: 'int' },
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
        var img = '',
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
        var img = '',
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
    { name: 'program_id', type: 'int' },
    'name'
  ]
});

Ext.define('Fieldset', {
  extend: 'Ext.data.Model',
  fields: ['name']
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
  autoLoad: true,
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
      root: 'program_form_fields',
      writeAllFields: false
    }
  }
});

Ext.create('Ext.data.Store', {
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
    listeners: {
      exception: function (proxy, response, op, eOpts) {
        op.records[0].store.remove(op.records);
        Ext.Msg.alert('Error', op.error);
      }
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
  data: [{
    program_id: 0,
    text: 'Default text Main',
    type: 'main',
    created: null,
    modified: null
  }, {
    program_id: 0,
    text: 'Default text Expired',
    type: 'expired',
    created: null,
    modified: null
  }, {
    program_id: 0,
    text: 'Default text Complete',
    type: 'complete',
    created: null,
    modified: null
  }, {
    program_id: 0,
    text: 'Default text Esign',
    type: 'esign',
    created: null,
    modified: null
  }, {
    program_id: 0,
    text: 'Default text User Acceptance',
    type: 'acceptance',
    created: null,
    modified: null
  }, {
    program_id: 0,
    text: 'Default text Pending Approval',
    type: 'pending_approval',
    created: null,
    modified: null
  }, {
    program_id: 0,
    text: 'Default text Pending Document Review',
    type: 'pending_document_review',
    created: null,
    modified: null
  }, {
    program_id: 0,
    text: 'Default text Drop-off Documents',
    type: 'drop_off_documents',
    created: null,
    modified: null
  }, {
    program_id: 0,
    text: 'Default text Upload Documents',
    type: 'upload_documents',
    created: null,
    modified: null
  }],
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
      allowSingle: false,
      encode: true,
      root: 'program_instructions',
      writeAllFields: false
    }
  }
});

Ext.create('Ext.data.Store', {
  data: [{
    program_id: 0,
    name: 'Orientation Main',
    from: ('noreply@' + window.location.hostname),
    subject: 'Main email',
    body: 'Default text Main',
    type: 'main',
    disabled: 0,
    created: null,
    modified: null
  }, {
    program_id: 0,
    name: 'Orientation Expiring Soon',
    from: ('noreply@' + window.location.hostname),
    subject: 'Expiring Soon',
    body: 'Default text Expiring Soon',
    type: 'expiring_soon',
    disabled: 0,
    created: null,
    modified: null
  }, {
    program_id: 0,
    name: 'Orientation Expired',
    from: ('noreply@' + window.location.hostname),
    subject: 'Expired email',
    body: 'Default text Expired',
    type: 'expired',
    disabled: 0,
    created: null,
    modified: null
  }, {
    program_id: 0,
    name: 'Orientation Complete',
    from: ('noreply@' + window.location.hostname),
    subject: 'Complete email',
    body: 'Default text Complete',
    type: 'complete',
    disabled: 0,
    created: null,
    modified: null
  }],
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
      allowSingle: false,
      encode: true,
      root: 'program_emails',
      writeAllFields: false
    }
  }
});

Ext.create('Ext.data.Store', {
  storeId: 'WatchedFilingCatStore',
  model: 'WatchedFilingCat',
  proxy: {
    api:{
      create: '/admin/watched_filing_cats/create',
      read: '/admin/watched_filing_cats/read',
      update: '/admin/watched_filing_cats/edit',
      destroy: '/admin/watched_filing_cats/destroy'
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

Ext.create('Ext.data.Store', {
  storeId: 'FieldsetStore',
  model: 'Fieldset'
});

/**
 * Variable Declarations
 */
var registrationForm, stepTree, customForm, formBuilderContainer, uploadStep, filingCategories, programDocumentation, instructions, emails, navigate, statusBar, states;

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
  height: 560,
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
      id: 'userAcknowledgement',
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
      }],
      listeners: {
        change: function (cbgroup, newVal, oldVal) {
          var esignRequiredCb = this.up('form').down('#esignRequired'),
            esignRequiredValue = esignRequiredCb.getValue().form_esign_required;

          if (typeof newVal.user_acceptance_required !== "string") {
            return;
          } else {
            if (newVal.user_acceptance_required === '1') {
              if (esignRequiredValue) {
                esignRequiredCb.setValue({ form_esign_required: 0 });
              }

              esignRequiredCb.disable();
            } else {
              esignRequiredCb.enable();
            }
          }
        }
      }
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
      id: 'esignRequired',
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
      }],
      listeners: {
        change: function (cbgroup, newVal, oldVal) {
          var userAckCb = this.up('form').down('#userAcknowledgement'),
            userAckValue = userAckCb.getValue().user_acceptance_required;

          if (typeof newVal.form_esign_required !== "string") {
            return;
          } else {
            if (newVal.form_esign_required === '1') {
              if (userAckValue) {
                userAckCb.setValue({ user_acceptance_required: 0 });
              }

              userAckCb.disable();
            } else {
              userAckCb.enable();
            }
          }
        }
      }
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
      minValue: 10,
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
        name: 'rolling_expiration',
        inputValue: '1',
        checked: true
      }, {
        boxLabel: 'No',
        name: 'rolling_expiration',
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
  },{
    xtype: 'textareafield',
    labelWidth: 190,
    fieldLabel: 'Media acknowledgement text',
    name: 'media_acknowledgement_text'
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
              field.allowBlank = false;
              container.getEl().highlight('C9DFEE', { duration: 1000 });
            } else {
              field.allowBlank = true;
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
    value: 0
  }],
  process: function () {
    var form = this.getForm(),
      programStore = Ext.data.StoreManager.lookup('ProgramStore'),
      record,
      vals,
      clearStatusTask = new Ext.util.DelayedTask(function () {
        statusBar.clearStatus();
        statusBar.setText('Program Instructions');
      });

    statusBar.setText('Saving Enrollment Details...');

    if (form.isValid()) {
      vals = form.getValues();
      programStore.add(vals);
    } else {
      return false;
    }

    $(window).bind('beforeunload', function () {
      return 'By leaving this page the program will be unfinished and you will need edit it at a later time.';
    });

    clearStatusTask.delay(500);
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
    region: 'west',
    rootVisible: false,
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
          var grid = Ext.getCmp('gridTreePanel'),
            selectedStep = grid.getSelectionModel().getSelection()[0];


          Ext.Msg.show({
            title: 'Are you sure?',
            msg: 'Are you sure you want to delete this step?',
            buttons: Ext.Msg.YESNO,
            icon: Ext.Msg.QUESTION,
            fn: function (btn) {
              if (btn === 'yes') {
                selectedStep.remove();
              }
            }
          });
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
                urlField = Ext.getCmp('mediaUrlField'),
                fieldsets = Ext.getCmp('defineFieldsets'),
                numOfColumns = Ext.getCmp('numberOfColumns'),
                fieldsetContainer = Ext.getCmp('fieldsetContainer'),
                numOfColumnsContainer = Ext.getCmp('numberOfColumnsContainer');

              if (newValue === 'media') {
                console.log('media!');
                fieldsetContainer.setVisible(false);
                fieldsets.allowBlank = true;
                numOfColumnsContainer.setVisible(false);
                numOfColumns.allowBlank = true;
                container.setVisible(true);
                uploadContainer.setVisible(true);
                container.getEl().highlight('C9DFEE', { duration: 1000 });
                uploadContainer.getEl().highlight('C9DFEE', { duration: 1000 });
                typeField.allowBlank = false;
                typeField.setValue('pdf');
                uploadField.allowBlank = false;
              } else if (newValue === 'custom_form') {
                console.log('custom_form!');
                fieldsetContainer.setVisible(true);
                fieldsets.allowBlank = false;
                numOfColumnsContainer.setVisible(true);
                numOfColumns.allowBlank = false;
                container.setVisible(false);
                typeField.allowBlank = true;
                typeField.setValue('pdf');
                uploadField.allowBlank = true;
                uploadContainer.setVisible(false);
                urlField.allowBlank = true;
                urlContainer.setVisible(false);
              } else {
                console.log('other!!');
                fieldsetContainer.setVisible(false);
                fieldsets.allowBlank = true;
                numOfColumnsContainer.setVisible(false);
                numOfColumns.allowBlank = true;
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
      id: 'numberOfColumnsContainer',
      items: [{
        xtype: 'numberfield',
        fieldLabel: 'Number of Columns',
        id: 'numberOfColumns',
        name: 'number_of_columns',
        minValue: 1,
        maxValue: 3,
        value: 2
      }]
    }, {
      xtype: 'fieldcontainer',
      hidden: true,
      id: 'fieldsetContainer',
      items: [{
        xtype: 'textfield',
        allowBlank: false,
        fieldLabel: 'Define Fieldsets',
        id: 'defineFieldsets',
        name: 'fieldsets',
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
            lcase: 'flv', ucase: 'Flash Video'
          }, {
            lcase: 'pdf', ucase: 'PDF Document'
          }, {
            lcase: 'swf', ucase: 'Shockwave Video'
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
              obj = {
                columns: vals.number_of_columns,
                fieldsets: vals.fieldsets
              };

              vals.meta = Ext.JSON.encode(obj);
              selectedModule.appendChild(vals);
              form.reset();
              break;

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
      id: 'stepUpdateBtn',
      text: 'Update',
      handler: function () {
      }
    }]
  }],
  preprocess: function () {
    var programStore = Ext.data.StoreManager.lookup('ProgramStore'),
      programStepStore = Ext.data.StoreManager.lookup('ProgramStepStore'),
      task = new Ext.util.DelayedTask(function () {
        var program = programStore.first();

        programStepStore.getProxy().extraParams = {
          program_id: program.data.id
        };
      });

    Ext.getCmp('statusProgressBar').updateProgress(0.33, 'Step 2 of 6');

    task.delay(2500);
  },
  process: function () {
    var programStore = Ext.data.StoreManager.lookup('ProgramStore'),
      program = programStore.first(),
      treePanel = this.down('treepanel'),
      lastStep = treePanel.getRootNode().lastChild.lastChild,
      clearStatusTask = new Ext.util.DelayedTask(function () {
        statusBar.clearStatus();
        statusBar.setText('Program Forms');
      });

    if (program.data.upload_docs) {
      if (!lastStep || lastStep.data.type !== 'required_docs') {
        Ext.Msg.alert('Error',
            'You have specified this program requires documents to be uploaded, but have not added the required step');
        return false;
      }
    }

    statusBar.setText('Saving Program Steps...');
    clearStatusTask.delay(500);

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
          programFormFieldStore = Ext.data.StoreManager.lookup('ProgramFormFieldStore'),
          decodedMeta = null,
          fieldsets,
          fsObj = [],
          fieldsetStore;

        if (rec.get('meta') !== "") {
          fieldsetStore = Ext.create('Ext.data.ArrayStore', {
            storeId: 'FIELDSETSTORE',
            fields: ['name'],
          });

          decodedMeta = Ext.JSON.decode(rec.get('meta'));
          fieldsets = decodedMeta.fieldsets.split(', ');
          Ext.Array.each(fieldsets, function (value) {
            fieldsetStore.add({name: value});
          });
        }

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
                selModel: {
                  mode: 'SINGLE',
                  allowDeselect: true
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
                  header: 'Fieldset',
                  dataIndex: 'fieldset',
                  hidden: (Ext.isEmpty(decodedMeta))
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
                      requiredCb = formPanel.down('#requiredCb'),
                      readOnlyCb = formPanel.down('#readOnlyCb'),
                      fieldType = Ext.getCmp('fieldType'),
                      fieldOptionsContainer = Ext.getCmp('fieldOptionsContainer'),
                      fieldOptions = Ext.getCmp('fieldOptions'),
                      deleteFieldBtn = Ext.getCmp('deleteFieldBtn'),
                      updateBtn = Ext.getCmp('updateBtn'),
                      builderSaveBtn = Ext.getCmp('builderSaveBtn'),
                      decodedValidation;

                    form.reset();
                    form.loadRecord(rec);

                    // check the appropriate checkboxes
                    if (rec.data.validation) {
                      if (rec.data.validation.match(/notEmpty/g)) {
                        requiredCb.setValue(true);
                      } else {
                        requiredCb.setValue(true);

                        decodedValidation = Ext.JSON.decode(rec.data.validation);
                        rec.data.answer = decodedValidation.rule[1];
                      }
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
                        form.loadRecord(rec);
                      } else if (rec.data.options.match(/"Yes":"Yes","No":"No"/gi)) {
                        fieldOptions.setValue('');
                        fieldOptionsContainer.setVisible(false);
                        rec.data.options = 'yesno';
                        form.loadRecord(rec);
                      } else if (rec.data.options.match(/"True":"True","False":"False"/gi)) {
                        fieldOptions.setValue('');
                        fieldOptionsContainer.setVisible(false);
                        rec.data.options = 'truefalse';
                        form.loadRecord(rec);
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
                        rec.data.options = vals;
                      }
                    }

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
                  xtype: 'fieldcontainer',
                  hidden: (Ext.isEmpty(decodedMeta)),
                  id: 'fieldsetsOptionsContainer',
                  items: [{
                    xtype: 'combo',
                    displayField: 'name',
                    fieldLabel: 'Fieldset',
                    id: 'fieldstepOptions',
                    name: 'fieldset',
                    queryMode: 'local',
                    store: fieldsetStore,
                    value: '',
                    valueField: 'name'
                  }, {
                    border: false,
                    cls: 'x-text-light',
                    html: '<em>Enter options as comma separated values, or choose from the list</em>',
                    padding: 5
                  }]
                }, {
                  xtype: 'textfield',
                  fieldLabel: 'Correct Answer',
                  name: 'answer'
                }, {
                  xtype: 'checkbox',
                  fieldLabel: 'Number Only',
                  name: 'numeric'
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
                  xtype: 'checkbox',
                  fieldLabel: 'Required',
                  id: 'requiredCb',
                  name: 'required'
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
                      parseVals,
                      attributes = {},
                      options = {},
                      validation = {},
                      programFormFieldStore = sm.lookup('ProgramFormFieldStore'),
                      programStep = sm.lookup('ProgramStepStore'),
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

                    if(vals.number_only === 'on') {
                      validation.rule = 'numeric';
                    }

                    vals.attributes      = encodeObject(attributes);
                    vals.options         = encodeObject(options);
                    vals.validation      = encodeObject(validation);
                    vals.program_step_id = rec.get('id');
                    vals.name            = vals.label.underscore();
                    vals.order           = (programFormFieldStore.count() + 1);

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
                      grid = Ext.getCmp('formFieldGrid'),
                      selectedRecord = grid.getSelectionModel().getSelection()[0],
                      deleteFieldBtn = Ext.getCmp('deleteFieldBtn'),
                      updateBtn = Ext.getCmp('updateBtn'),
                      builderSaveBtn = Ext.getCmp('builderSaveBtn');

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

                    if(vals.number_only === 'on') {
                      validation.rule = 'numeric';
                    }

                    vals.attributes      = encodeObject(attributes);
                    vals.options         = encodeObject(options);
                    vals.validation      = encodeObject(validation);
                    vals.program_step_id = rec.get('id');
                    vals.name            = vals.label.underscore();

                    selectedRecord.set(vals);
                    updateBtn.disable().hide();
                    builderSaveBtn.enable().show();
                    deleteFieldBtn.disable();
                    form.reset();
                    grid.getSelectionModel().deselectAll();
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

    Ext.getCmp('statusProgressBar').updateProgress(0.50, 'Step 3 of 6');

    task.delay(1000);
  },
  process: function () {
    var clearStatusTask = new Ext.util.DelayedTask(function () {
        statusBar.clearStatus();
        statusBar.setText('Program Documents');
      });

      statusBar.setText('Saving Program Forms...');
      clearStatusTask.delay(500);
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
    selModel: {
      allowDeselect: true,
      mode: 'SINGLE'
    },
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
        var deleteBtn = Ext.getCmp('deleteDocumentBtn');

        deleteBtn.enable();
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
          var grid = Ext.getCmp('documentGrid');

          grid.getSelectionModel().deselectAll();
        }
      }, {
        disabled: true,
        icon: '/img/icons/delete.png',
        id: 'deleteDocumentBtn',
        text: 'Delete Document',
        handler: function () {
          var programDocumentStore = Ext.data.StoreManager.lookup('ProgramDocumentStore'),
            grid = Ext.getCmp('documentGrid'),
            selectedRecord = grid.getSelectionModel().getSelection()[0],
            deleteBtn = this;

          Ext.Msg.show({
            title: 'Are you sure?',
            msg: 'Are you sure you want to delete this document?',
            buttons: Ext.Msg.YESNO,
            icon: Ext.Msg.QUESTION,
            fn: function (btn) {
              if (btn === 'yes') {
                deleteBtn.disable();
                grid.getSelectionModel().deselectAll();
                programDocumentStore.remove(selectedRecord);
              }
            }
          });
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
          lcase: 'multi_snapshot', ucase: 'Multi-Snapshot'
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
                  programDocumentStore.sync();
                  programEmailStore.add({
                    program_id: vals.program_id,
                    to: null,
                    from: ('noreply@' + window.location.hostname),
                    subject: vals.name + ' Email',
                    body: 'Email for ' + vals.name,
                    type: vals.name.underscore + '_document',
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
              programDocumentStore.sync();
              if (vals.type === 'upload') {
                programEmailStore.add({
                  program_id: vals.program_id,
                  to: null,
                  from: ('noreply@' + window.location.hostname),
                  subject: vals.name + ' Email',
                  body: 'Email for ' + vals.name,
                  type: vals.name.underscore() + '_document',
                  name: vals.name + ' Document Email'
                });
              }
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
    Ext.getCmp('statusProgressBar').updateProgress(0.66, 'Step 4 of 6');
  },
  process: function () {
    var clearStatusTask = new Ext.util.DelayedTask(function () {
        statusBar.clearStatus();
        statusBar.setText('Program Instructions');
      });

      statusBar.setText('Saving Program Documents...');
      clearStatusTask.delay(500);
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
      rootNode = programStepStore.tree.root;

    Ext.getCmp('statusProgressBar').updateProgress(0.83, 'Step 5 of 6');

    Ext.Ajax.request({
      url: '/admin/program_steps/read',
      params: {
        program_id: program.get('id')
      },
      success: function (response) {
        var res = Ext.JSON.decode(response.responseText);
        Ext.Object.each(res.program_steps, function (key, value, me) {
          if (value.type) {
            programInstructionStore.add({
              program_id: program.get('id'),
              program_step_id: value.id,
              text: 'Instructions for ' + value.name + ' step',
              type: value.type + '_step'
            });
          }
        });
      }
    });

    if (!instructionsSaved) {
      programInstructionStore.each(function (rec) {
        var programInstruction = {
          program_id: program.get('id')
        };

        switch (rec.get('type')) {
          case 'main':
            programInstruction.text = 'Welcome to the ' +
            AtlasInstallationName +
            ' Web Services system. Please proceed with the ' +
            program.get('name').humanize() +
            ' enrollment by completing the steps listed below.';
            break;

          case 'pending_approval':
            programInstruction.text = 'Your submission is currently being' +
            ' reviewed by our staff. You will be notified by email when the' +
            ' status of the submission changes. You may also visit the link' +
            ' provided below to check on the status of your submission.' +
            ' Thank you for using the ' +
            AtlasInstallationName +
            ' Web Services system.<br />' +
            '<br />' +
            '<a href="' +
            window.location.origin +
            '/programs/enrollment/' +
            rec.get('id') +
            '">' +
            program.get('name').humanize() +
            '</a>';
            break;

          case 'pending_document_review':
            programInstruction.text = 'Your documents are currently being' +
            ' reviewed by our staff. You will be notified by email when the' +
            ' status of the documents changes.' +
            ' Thank you for using the ' +
            AtlasInstallationName +
            ' Web Services system.';
            break;

          case 'expired':
            programInstruction.text = 'We\'re sorry but your submission has' +
            ' expired. Please contact the ' +
            AtlasInstallationName +
            ' for further assistance.';
            break;

          case 'not_approved':
            programInstruction.text = 'We\'re sorry but your submission has been' +
            ' marked as "Not Approved." If you feel this is in error please' +
            ' contact the ' +
            AtlasInstallationName +
            ' for further assistance.';
            break;

          case 'complete':
            programInstruction.text = 'We have reviewed your submission and it' +
            ' has been marked as complete. Please visit the following link for' +
            ' submitted is accurate.<br />' +
            '<br />' +
            '<a href="#">ADMIN. PLEASE ADD YOUR LINK</a>';
            break;

          case 'acceptance':
            programInstruction.text = 'By entering your first and last name in' +
            ' the box below you are agreeing that all the information you have' +
            ' submitted is accurate.';
            break;

          case 'esign':
            programInstruction.text = 'By signing your first and last name in' +
            ' the box below you are agreeing that all the information you have' +
            ' submitted is accurate.';
            break;

          case 'upload_docs':
            programInstruction.text = 'Please upload one document at a time using' +
            ' the form below. For example, if you are uploading a driver\'s license' +
            ' and utility bill, you will upload the first document then you will' +
            ' be redirected back to this page to upload the second document. ' +
            ' Once finished uploading all documents, choose "I am finished uploading' +
            ' documents."';
            break;
        }

        rec.set(programInstruction);
      });
    }
  },
  process: function () {
    var programInstructionStore = Ext.data.StoreManager.lookup('ProgramInstructionStore'),
      editor = Ext.getCmp('editor'),
      clearStatusTask = new Ext.util.DelayedTask(function () {
        statusBar.clearStatus();
        statusBar.setText('Program Emails');
      });

      statusBar.setText('Saving Program Instructions...');
      clearStatusTask.delay(500);

      programInstructionStore.sync();
      instructionsSaved = true;
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
      itemcontextmenu: function (view, rec, item, index, e) {
        var menu,
          items = [];

        e.preventDefault();

        if (rec.get('disabled')) {
          items.push({
              icon: '/img/icons/survey.png',
              text: 'Enable',
              handler: function () {
                rec.set('disabled', 0);
              }
          });
        } else {
          items.push({
              icon: '/img/icons/survey.png',
              text: 'Disable',
              handler: function () {
                rec.set('disabled', 1);
              }
          });
        }

        menu = Ext.create('Ext.menu.Menu', {
          items: items
        });

        menu.showAt(e.getXY());
      },
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
    viewConfig: {
      getRowClass: function (rec) {
        return rec.get('disabled') ? 'row-disabled' : 'row-active';
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
    var program = Ext.data.StoreManager.lookup('ProgramStore').first(),
      programEmailStore = Ext.data.StoreManager.lookup('ProgramEmailStore'),
      programStepStore = Ext.data.StoreManager.lookup('ProgramStepStore');

    Ext.getCmp('statusProgressBar').updateProgress(1.0, 'Step 6 of 6');

    if (!emailsSaved) {
      programEmailStore.each(function (rec) {
        var programEmail = {
          program_id: program.get('id')
        };

        switch (rec.get('type')) {
          case 'main':
            programEmail.body = 'Welcome to the ' +
            AtlasInstallationName +
            ' Web Services system. You have begun the submission process for ' +
            program.get('name').humanize() +
            '. <br />' +
            '<br />' +
            '<a href="' +
            window.location.origin +
            '/programs/enrollment/' +
            rec.get('id') +
            '">Click here to return to the ' +
            program.get('name').humanize() +
            ' enrollment</a>';
            break;

          case 'expiring_soon':
            programEmail.body = 'This is an automated notification to inform you' +
            ' that the program you began enrollment for will expire in ' +
            program.get('send_expiring_soon') +
            ' days. Please login to the ' +
            AtlasInstallationName +
            ' Web Services system to finish your enrollment. If you questions' +
            ' please contact the ' +
            AtlasInstallationName +
            ' for assistance.';
            break;

          case 'expired':
            programEmail.body = 'We\'re sorry but your submission has' +
            ' expired. Please contact the ' +
            AtlasInstallationName +
            ' for further assistance.';
            break;

          case 'complete':
            programEmail.body = 'We have reviewed your submission and it' +
            ' has been marked as complete. Please visit the following link for' +
            ' submitted is accurate.<br />' +
            '<br />' +
            '<a href="#">ADMIN. PLEASE ADD YOUR LINK</a>';
            break;
        }

        rec.set(programEmail);
      });
    }
  },
  process: function () {
    var programEmailStore = Ext.data.StoreManager.lookup('ProgramEmailStore'),
      editor = Ext.getCmp('emailEditor'),
      clearStatusTask = new Ext.util.DelayedTask(function () {
        statusBar.clearStatus();
      });

      statusBar.setText('Saving Program Emails...');
      clearStatusTask.delay(500);

      programEmailStore.sync();
      emailsSaved = true;
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
    $(window).unbind('beforeunload');

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
    xtype: 'progressbar',
    id: 'statusProgressBar',
    text: 'Step 1 of 6',
    value: 0.17,
    width: 200
  }, '->', {
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

  statusBar.setText('Enrollment Details');

  Ext.create('Ext.panel.Panel', {
    defaults: {
      bodyPadding: 10
    },
    dockedItems: [ statusBar ],
    items: [
      registrationForm,
      stepTree,
      formBuilderContainer,
      uploadStep,
      instructions,
      emails
    ],
    layout: 'card',
    renderTo: 'registrationForm',
    title: 'New Program Enrollment'
  });

});
