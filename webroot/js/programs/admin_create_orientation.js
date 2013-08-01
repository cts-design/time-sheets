var encodeObject = function (obj) {
  if (Object.keys(obj).length) {
    return Ext.JSON.encode(obj);
  }
  return null;
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
      create: '/admin/programs/create_orientation',
      update: '/admin/programs/update_orientation'
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
      labelWidth: 175,
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
      fieldLabel: 'Show in Customer Dashboard?',
      labelWidth: 175,
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
    value: 'orientation'
  }, {
    xtype: 'fieldcontainer',
    height: 24,
    width: 250,
    layout: {
      align: 'stretch',
      type: 'vbox'
    },
    items: [{
      xtype: 'combo',
      allowBlank: false,
      displayField: 'ucase',
      fieldLabel: 'Registration Type',
      labelWidth: 175,
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
      labelWidth: 175,
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
    }],
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
      labelWidth: 175,
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
  },{
    xtype: 'textareafield',
    fieldLabel: 'Acknowledge Media Text',
    labelWidth: 175,
    name: 'media_acknowledgement_text'
  }, {
    border: 0,
    html: '<h1>Program Media</h1>',
    margin: '0 0 10'
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
      fieldLabel: 'Media Type',
      id: 'mediaType',
      labelWidth: 175,
      listeners: {
        change: {
          fn: function (field, newValue, oldValue) {
            var container = Ext.getCmp('uploadContainer'),
              uploadField = Ext.getCmp('uploadField'),
              urlContainer = Ext.getCmp('urlContainer'),
              urlField = Ext.getCmp('urlField'),
              uploadButton = Ext.getCmp('uploadMediaButton');

            if (newValue !== 'url') {
              container.setVisible(true);
              uploadField.allowBlank = false;
              urlContainer.setVisible(false);
              urlField.allowBlank = true;
              uploadButton.setVisible(true);
              if (oldValue === 'url') {
                container.getEl().highlight('C9DFEE', { duration: 1000 });
              }
            } else {
              uploadButton.setVisible(false);
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
      value: '',
      valueField: 'lcase',
      width: 300
    }]
  }, {
    xtype: 'fieldcontainer',
    height: 22,
    id: 'uploadContainer',
    width: 500,
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
      xtype: 'filefield',
      allowBlank: false,
      fieldLabel: 'Media',
      id: 'uploadField',
      labelWidth: 175,
      name: 'media',
      width: 400
    }, {
      xtype: 'displayfield',
      style: {
        color: '#445566'
      },
      value: 'E.g. flv, pdf'
    }]
  }, {
    xtype: 'fieldcontainer',
    height: 22,
    hidden: true,
    id: 'urlContainer',
    width: 500,
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
      xtype: 'textfield',
      allowBlank: false,
      fieldLabel: 'Media Url',
      id: 'urlField',
      labelWidth: 175,
      name: 'media_location',
      width: 400
    }]
  }, {
    xtype: 'button',
    id: 'uploadMediaButton',
    text: 'Upload Media',
    handler: function () {
      var formPanel = this.up('form'),
        form = formPanel.getForm(),
        vals = form.getValues(),
        mediaUploadField = formPanel.down('#uploadField'),
        mediaTypeField = formPanel.down('#mediaType'),
        uploadMediaButton = formPanel.down('#uploadMediaButton'),
        programStore = Ext.data.StoreManager.lookup('ProgramStore'),
        allFormFields,
        // this will be an array to hold the index of the items in the mixed
        // collection to set them back to allowBlank: false
        validatedFieldsCache = [];

      if (!mediaTypeField.isValid()) { return; }
      if (!mediaUploadField.isValid()) { return; }

      formFields = form.getFields();

      // enable allowBlank on each field so we can submit the form for upload
      formFields.each(function (field, index, length) {
        if (!field.allowBlank) {
          validatedFieldsCache.push(index);
          field.allowBlank = true;
        }
      });

      form.submit({
        url: '/admin/programs/upload_media',
        waitMsg: 'Uploading Media...',
        scope: this,
        success: function (form, action) {
          // set allowBlank true back on the proper fields in the mixed collection
          var i = validatedFieldsCache.length - 1;
          for (i;  i >= 0; i--){
            var field = formFields.getAt(validatedFieldsCache[i]);
            field.allowBlank = false;
          }

          mediaUploadField.disable().allowBlank = true;
          mediaTypeField.disable().allowBlank = true;
          uploadMediaButton.disable();

          media = {
            location: action.result.url,
            type: vals.media_type
          };

          programStore.getProxy().extraParams = {
            media: Ext.JSON.encode(media)
          };
        },
        failure: function (form, action) {
          switch (action.failureType) {
            case Ext.form.action.Action.CLIENT_INVALID:
              Ext.Msg.alert('Failure', 'Form fields may not be submitted with invalid values');
              break;
            case Ext.form.action.Action.CONNECT_FAILURE:
              Ext.Msg.alert('Failure', 'Ajax communication failed');
              break;
            case Ext.form.action.Action.SERVER_INVALID:
              Ext.Msg.alert('Failure', action.result.msg);
              break;
          }
        }
      });
    }
  }, {
    xtype: 'hiddenfield',
    name: 'confirmation_id_length',
    value: '10'
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
      uploadContainer = Ext.getCmp('uploadContainer'),
      record,
      vals,
      clearStatusTask = new Ext.util.DelayedTask(function () {
        statusBar.clearStatus();
        statusBar.setText('Program Form Fields');
      });

    statusBar.setText('Saving Orientation Details...');

    if (form.isValid()) {
      vals = form.getValues();

      if (uploadContainer.hidden && !vals.media_location.match(/[http|https]:\/\//)) {
          vals.media_location = 'http://' + vals.media_location;
      }

      $(window).bind('beforeunload', function () {
        return 'By leaving this page the program will be unfinished and you will need edit it at a later time.';
      });

      clearStatusTask.delay(500);

      programStore.add(vals);
      return true;
    } else {
      return false;
    }
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
          builderSaveBtn = Ext.getCmp('builderSaveBtn');

        if (rec.data.attributes) {
          if (rec.data.attributes.match(/datepicker/g)) {
            fieldType.setValue('datepicker');
            rec.data.type = 'datepicker';
          }
        }

        form.loadRecord(rec);

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

        programStepId = programStep.findRecord('type', /form/gi).data.id;

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

        validation.rule = ['looseEqualTo', vals.answer];
        validation.message = 'Incorrect';

        vals.attributes = encodeObject(attributes);
        vals.options = encodeObject(options);
        vals.validation = encodeObject(validation);
        vals.program_step_id = programStepId;
        vals.name = vals.label.underscore();
        vals.order = (programFormFieldStore.count() + 1);

        console.log(validation);


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
          programFormFieldStore = Ext.data.StoreManager.lookup('ProgramFormFieldStore'),
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
        vals.name = vals.label.underscore();

        selectedRecord.set(vals);
        form.reset();
      }
    }]
  }],
  preprocess: function () {
    var programStore = Ext.data.StoreManager.lookup('ProgramStore'),
      programStepStore = Ext.data.StoreManager.lookup('ProgramStepStore'),
      programId;

    Ext.getCmp('statusProgressBar').updateProgress(0.4, 'Step 2 of 5');

    task = new Ext.util.DelayedTask(function () {
      programId = programStore.first().data.id;
      programStepStore.load({
        params: {
          program_id: programId
        }
      });
    });
    task.delay(2500);
  },
  process: function () {
    var programFormFieldStore = Ext.data.StoreManager.lookup('ProgramFormFieldStore'),
      clearStatusTask = new Ext.util.DelayedTask(function () {
        statusBar.clearStatus();
        statusBar.setText('Program Filing Categories');
      });

    statusBar.setText('Saving Program Form Fields...');
    clearStatusTask.delay(500);

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
    html: '<h1>Where would you like to file the orientation certificate?</h1>',
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
  preprocess: function () {
    Ext.getCmp('statusProgressBar').updateProgress(0.6, 'Step 3 of 5');
  },
  process: function () {
    var form = this.getForm(),
      programDocumentStore = Ext.data.StoreManager.lookup('ProgramDocumentStore'),
      programStore = Ext.data.StoreManager.lookup('ProgramStore'),
      programStepStore = Ext.data.StoreManager.lookup('ProgramStepStore'),
      program,
      programStep,
      vals,
      clearStatusTask = new Ext.util.DelayedTask(function () {
        statusBar.clearStatus();
        statusBar.setText('Program Instructions');
      });

    if (form.isValid()) {
      vals = form.getValues();
      program = programStore.first();
      programStep = programStepStore.findRecord('type', /form/);

      vals.template = 'atlas_cert.pdf';
      vals.name = program.data.name + " Orientation Certificate";
      vals.type = 'certificate';
      vals.program_id = program.data.id;
      vals.program_step_id = programStep.data.id;
      programDocumentStore.add(vals);

      statusBar.setText('Saving Program Filing Categories...');
      clearStatusTask.delay(500);

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
      mediaStep,
      quizStep;

    Ext.getCmp('statusProgressBar').updateProgress(0.8, 'Step 4 of 5');

    mediaStep = programStepStore.findRecord('type', /^media$/gi);
    quizStep = programStepStore.findRecord('type', /^form$/gi);

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
            ' orientation by completing the steps listed below.';
            break;

          case 'expired':
            programInstruction.text = 'We\'re sorry but your submission has' +
            ' expired. Please contact the ' +
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
        }

        rec.set(programInstruction);
      });

      if (mediaStep.get('type') === 'pdf') {
        mediaText = 'Please read the following media and choose submit when finished.';
      } else {
        mediaText = 'Please watch the following media and choose submit when finished.';
      }

      programInstructionStore.add({
        program_id: program.get('id'),
        program_step_id: mediaStep.get('id'),
        text: mediaText,
        type: 'Orientation Media Step Instructions'.underscore()
      }, {
        program_id: program.get('id'),
        program_step_id: quizStep.get('id'),
        text: 'Please fill out the following form and choose submit when finished.',
        type: 'Orientation Quiz Step Instructions'.underscore()
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
    var programStore = Ext.data.StoreManager.lookup('ProgramStore'),
      programStepStore = Ext.data.StoreManager.lookup('ProgramStepStore'),
      programEmailStore = Ext.data.StoreManager.lookup('ProgramEmailStore'),
      program = programStore.first(),
      mediaStep,
      quizStep;

    Ext.getCmp('statusProgressBar').updateProgress(1.0, 'Step 5 of 5');

    mediaStep = programStepStore.findRecord('type', /^media$/gi);
    quizStep = programStepStore.findRecord('type', /^form$/gi);

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
            '/programs/orientation' +
            rec.get('id') +
            '">Click here to return to the ' +
            program.get('name').humanize() +
            ' orientation</a>';
            break;

          case 'pending_approval':
            programEmail.body = 'Your submission is currently being' +
            ' reviewed by our staff. You will be notified by email when the' +
            ' status of the submission changes. You may also visit the link' +
            ' provided below to check on the status of your submission.' +
            ' Thank you for using the ' +
            AtlasInstallationName +
            ' Web Services system.<br />' +
            '<br />' +
            '<a href="' +
            window.location.origin +
            '/programs/registration' +
            rec.get('id') +
            '">' +
            program.get('name').humanize() +
            '</a>';
            break;

          case 'expiring_soon':
            programEmail.body = 'This is an automated notification to inform you' +
            ' that the program you began enrollment for will expire in ' +
            program.get('send_expiring_soon') +
            ' days. Please login to the ' +
            AtlasInstallationName +
            ' Web Services system to finish your orientation. If you questions' +
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

      programEmailStore.add({
        program_id: program.get('id'),
        program_step_id: mediaStep.get('id'),
        name: program.get('name') + ' Orientation Media Step Email',
        type: 'orientation_media_step',
        body: 'Your Orientation media step email',
        from: ('noreply@' + window.location.hostname)
      }, {
        program_id: program.get('id'),
        program_step_id: quizStep.get('id'),
        name: program.get('name') + ' Orientation Quiz Step Email',
        type: 'orientation_quiz_step',
        body: 'Your Orientation quiz step email',
        from: ('noreply@' + window.location.hostname)
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
    text: 'Step 1 of 5',
    value: 0.2,
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

  statusBar.setText('Orientation Details');

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
    title: 'New Program Orientation'
  });

});
