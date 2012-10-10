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
    { name: 'bar_code_definition_id', type: 'int' },
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

Ext.define('BarCodeDefinition', {
  extend: 'Ext.data.Model',
  fields: [
    { name: 'id', type: 'int' },
    { name: 'name' },
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
      create: '/admin/programs/create_esign',
      update: '/admin/programs/update_esign'
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
    { program_id: 0, text: 'Default text Pending Document Review', type: 'pending_document_review', created: null, modified: null },
    { program_id: 0, text: 'Default text Expired', type: 'expired', created: null, modified: null },
    { program_id: 0, text: 'Default text Not Approved', type: 'not_approved', created: null, modified: null },
    { program_id: 0, text: 'Default text Complete', type: 'complete', created: null, modified: null }
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
    { program_id: 0, name: 'Registration Main', from: ('noreply@' + window.location.hostname), subject: 'Main', body: 'Default text Main', type: 'main', created: null, modified: null },
    { program_id: 0, name: 'Registration Pending Approval', from: ('noreply@' + window.location.hostname), subject: 'Pending Approval', body: 'Default text Pending Approval', type: 'pending_approval', created: null, modified: null },
    { program_id: 0, name: 'Registration Expiring Soon', from: ('noreply@' + window.location.hostname), subject: 'Expiring Soon', body: 'Default text Expiring Soon', type: 'expiring_soon', created: null, modified: null },
    { program_id: 0, name: 'Registration Expired', from: ('noreply@' + window.location.hostname), subject: 'Expired', body: 'Default text Expired', type: 'expired', created: null, modified: null },
    { program_id: 0, name: 'Registration Not Approved', from: ('noreply@' + window.location.hostname), subject: 'Not Approved', body: 'Default text Main', type: 'not_approved', created: null, modified: null },
    { program_id: 0, name: 'Registration Complete', from: ('noreply@' + window.location.hostname), subject: 'Complete', body: 'Default text Complete', type: 'complete', created: null, modified: null }
  ],
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
  autoLoad: true,
  model: 'BarCodeDefinition',
  proxy: {
    api:{
      read: '/admin/bar_code_definitions/index'
    },
    type: 'ajax',
    reader: {
      type: 'json',
      root: 'definitions'
    }
  },
  storeId: 'BarCodeDefinitionStore',
});

/**
 * Variable Declarations
 */
var registrationForm, instructions, emails, navigate, statusBar;

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
      name: 'name',
      value: 'Esign'
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
    xtype: 'hiddenfield',
    name: 'type',
    value: 'esign'
  }, {
    xtype: 'hiddenfield',
    name: 'approval_required',
    value: '1'
  }, {
    xtype: 'hiddenfield',
    name: 'show_in_dash',
    value: '1'
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
  }, {
    border: 0,
    html: '<h1>Esign Document</h1>',
    margin: '0 0 10'
  }, {
    xtype: 'fieldcontainer',
    height: 24,
    width: 400,
    layout: {
      align: 'stretch',
      type: 'vbox'
    },
    items: [{
      xtype: 'filefield',
      allowBlank: false,
      fieldLabel: 'Esign Template',
      labelWidth: 175,
      name: 'document',
      value: ''
    }]
  }, {
    xtype: 'fieldcontainer',
    height: 24,
    width: 400,
    layout: {
      align: 'stretch',
      type: 'vbox'
    },
    items: [{
      xtype: 'combo',
      allowBlank: false,
      displayField: 'name',
      fieldLabel: 'Bar Code Definition',
      id: 'barCodeDefinition',
      labelWidth: 175,
      name: 'bar_code_definition_id',
      queryMode: 'local',
      store: 'BarCodeDefinitionStore',
      valueField: 'id',
      value: null
    }]
  }, {
    xtype: 'fieldcontainer',
    height: 24,
    width: 400,
    layout: {
      align: 'stretch',
      type: 'vbox'
    },
    items: [{
      xtype: 'combo',
      allowBlank: false,
      displayField: 'name',
      fieldLabel: 'Filing Category 1',
      id: 'cat1Name',
      labelWidth: 175,
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
            store.load({params: {parentId: records[0].data.id}});
          }

        }
      },
      name: 'cat_1',
      queryMode: 'local',
      store: 'Cat1Store',
      valueField: 'id',
      value: null
    }]
  }, {
    xtype: 'fieldcontainer',
    height: 24,
    width: 400,
    layout: {
      align: 'stretch',
      type: 'vbox'
    },
    items: [{
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
      labelWidth: 175,
      listConfig: {
          getInnerTpl: function() {
              return '<div>{img}{name}</div>';
          }
      },
      allowBlank: false
    }]
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

    statusBar.setText('Saving Esign Details...');

    if (form.isValid()) {
      vals = form.getValues();

      form.submit({
        url: '/admin/program_documents/upload',
        waitMsg: 'Uploading Document...',
        scope: this,
        success: function (form, action) {
          form.reset();

          programDoc = {
            name: 'Esign Enrollment Form',
            template: action.result.url,
            type: 'download',
            cat_1: vals.cat_1,
            cat_2: vals.cat_2
          };

          programStore.getProxy().extraParams = {
            program_document: Ext.JSON.encode(programDoc)
          };

          programStore.add(vals);
        },
        failure: function (form, action) {
          Ext.Msg.alert('Could not upload esign document', action.result.msg);
        }
      });
    }

    $(window).bind('beforeunload', function () {
      return 'By leaving this page the program will be unfinished and you will need edit it at a later time.';
    });

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
      grid = Ext.getCmp('instructionsGrid'),
      program,
      downloadStep,
      task;

    Ext.getCmp('statusProgressBar').updateProgress(0.66, 'Step 2 of 3');

    grid.getEl().mask('Loading...');

    task = new Ext.util.DelayedTask(function () {
      program = programStore.first();

      programStepStore.load({
        params: {
          program_id: program.data.id
        },
        callback: function (recs, op, success) {
          downloadStep = programStepStore.findRecord('type', /^form_download$/gi);

          programInstructionStore.each(function (rec) {
            rec.set({
              program_id: program.data.id
            });
          });

          programInstructionStore.add({
            program_id: program.data.id,
            program_step_id: downloadStep.data.id,
            text: 'Esign Enrollment Form Step Instructions',
            type: 'Esign Enrollment Form Step Instructions'.underscore()
          });

          grid.getEl().unmask();
        }
      });
    });

    task.delay(1000);
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

    Ext.getCmp('statusProgressBar').updateProgress(1.0, 'Step 3 of 3');

    programEmailStore.each(function (rec) {
      rec.set({
        program_id: programId
      });
    });
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
    // ticket #64
    // Unbind the beforeunload event on completion
    //
    // time: 3 minutes      
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
    text: 'Step 1 of 3',
    value: 0.33,
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

  statusBar.setText('Esign Details');

  Ext.create('Ext.panel.Panel', {
    defaults: {
      bodyPadding: 10
    },
    dockedItems: [ statusBar ],
    items: [
      registrationForm,
      instructions,
      emails
    ],
    layout: 'card',
    renderTo: 'registrationForm',
    title: 'New Program Registration'
  });

});
