Ext.define('EnabledField', {
  extend: 'Ext.data.Model',
  fields: ['field']
});

var enabledFields = Ext.create('Ext.data.Store', {
  model: 'EnabledField',
  proxy: {
    type: 'ajax',
    url: '/admin/settings/kiosk_registration',
    reader: {
      type: 'json',
      root: 'fields'
    },
    pageParam: undefined,
    limitParam: undefined,
    startParam: undefined			
  },
  autoLoad: true,
  listeners: {
    load: function(store, records, successful, operation, eOpts) {
      var selected = [];
      var i = 0;
      Ext.Array.each(records, function(name, index) {
        selected[i] = name.data.field; 
        i++;
      });
      fieldsSelect.select(selected);
    }
  }
});

var fields = Ext.create('Ext.data.Store', {
    fields: ['field', 'label'],
    data : [
      {"field":"middle_initial", "label": "Middle Initial"},
      {"field":"surname", "label": "Surname"},
      {"field":"address_1", "label": "Address"},
      {"field":"city", "label":"City"},
      {"field":"county", "label":"County"},
      {"field":"state", "label":"State"},	
      {"field":"zip", "label":"Zip Code"},
      {"field":"phone", "label":"Phone"},
      {"field":"gender", "label":"Gender"},
      {"field":"dob", "label": "Birth Date"},	
      {"field":"email", "label": "Email Address"},	        
      {"field":"language", "label":"Language"},
      {"field":"race", "label":"Race"},
      {"field":"ethnicity", "label":"Ethnicity"},
      {"field":"veteran", "label":"Veteran"},
      {"field":"disability", "label":"Disability"}
    ]
});

// Max Selection validation to only allow x number of selections in a boxselect menu
Ext.apply(Ext.form.field.VTypes, {
  maxselections: function(val, field)	{
    if(field.getValue().length > field.maxSelections) {
      return false;
    }
    return true;
  },
  maxselectionsText: 'Only 7 custom fields allowed.'
}); 

var fieldsSelect = Ext.create('Ext.ux.form.field.BoxSelect', {
    store: fields,
    width: 280,
    queryMode: 'local',
    allowBlank: false,
    displayField: 'label',
    valueField: 'field',
    multiSelect: true,
    submitValue: false,
    maxSelections: 7,
    vtype: 'maxselections'
});	

var kioskRegistration = Ext.create('Ext.form.Panel', {
  title: 'Kiosk Registration',
  url: '/admin/settings/kiosk_registration',
  margin: 5,
  frame: true,
  width: 295,
  flex: 1,
  items: [fieldsSelect],
  buttons: [{
    text: 'Update',
    formBind: true,
    handler: function() {
      var vals = Ext.util.Format.htmlEncode(fieldsSelect.getValue());
      var form = this.up('form').getForm();
      if(form.isValid()) {
        form.submit({
          waitMsg: 'Updating Settings',
          params: {fields: vals},
          success: function(form, action) {
             Ext.Msg.alert('Success', action.result.message);
          },
          failure: function(form, action) {
              Ext.Msg.alert('Failed', action.result.message);
          }
        });
      }
    }
  }]
});

Ext.define('CustomerInfo', {
  extend: 'Ext.data.Model',
  fields: ['value']
});

Ext.create('Ext.data.Store', {
  model: 'CustomerInfo',
  id: 'customerInfo',
  proxy: {
    type: 'ajax',
    url: '/admin/settings/kiosk_confirmation',
    reader: {
      type: 'json',
      root: 'confirmation'
    },
    pageParam: undefined,
    limitParam: undefined,
    startParam: undefined			
  },
  autoLoad: true,
  listeners: {
    load: function(store, records, successful, operation, eOpts) {
      if(records[0] !== undefined) {
       Ext.getCmp('kioskConfirmation').getComponent('cb1').setValue(records[0].data.value);
      }
    }
  }
});

Ext.create('Ext.form.Panel', {
  title: 'Kiosk Customer Info Confirmation',
  url: '/admin/settings/kiosk_confirmation',
  id: 'kioskConfirmation',
  frame: true,
  margin: 5,
  width: 295,
  flex: 1,
  items: [{
    xtype: 'checkboxfield',
    fieldLabel: 'Customer Info Confirmation',
    name: 'confirmation',
    itemId: 'cb1',
    uncheckedValue: 'off'
  }],
  buttons: [{
    text: 'Update',
    formBind: true,
    handler: function() {
      var form = this.up('form').getForm();
      if(form.isValid()) {
        form.submit({
          waitMsg: 'Updating Settings',
          success: function(form, action) {
             Ext.Msg.alert('Success', action.result.message);
          },
          failure: function(form, action) {
              Ext.Msg.alert('Failed', action.result.message);
          }
        });
      }
    }
  }]
});

Ext.define('TimeOut', {
  extend: 'Ext.data.Model',
  fields: ['value']
});

Ext.create('Ext.data.Store', {
  model: 'TimeOut',
  id: 'kioskTimeOut',
  proxy: {
    type: 'ajax',
    url: '/admin/settings/kiosk_time_out',
    reader: {
      type: 'json',
      root: 'timeout'
    },
    pageParam: undefined,
    limitParam: undefined,
    startParam: undefined			
  },
  autoLoad: true,
  listeners: {
    load: function(store, records, successful, operation, eOpts) {
      if(records[0] !== undefined && records[1] !== undefined) {
        Ext.getCmp('kioskTimeOut').getComponent('cb1').setValue(records[0].data.value);
        Ext.getCmp('kioskTimeOut').getComponent('cb2').setValue(records[1].data.value);
      }
    }
  }
});

var kiosk_survey_combo = Ext.create('Ext.data.Store', {
  fields : ['value', 'name'],
  data : [{
    'value' : 'prompt',
    'name' : 'Prompt'
  }, {
    'value' : 'force',
    'name' : 'Force'
  }]
});

Ext.create('Ext.form.Panel', {
  title: 'Kiosk Time Out',
  id: 'kioskTimeOut',
  url: '/admin/settings/kiosk_time_out',
  frame: true,
  width: 295,	
  margin: 5,
  items: [{
    xtype: 'numberfield',
    itemId: 'cb1',
    fieldLabel: 'Kiosk Time Out (secs)',
    name: 'timeout',
    minValue: 5,
    maxValue: 60
  },{
    xtype: 'numberfield',
    itemId: 'cb2',
    fieldLabel: 'Kiosk Time Out Reminder Window (secs)',
    name: 'reminder',
    minValue: 5,
    maxValue: 60
 }],
  buttons: [{
    text: 'Update',
    formBind: true,
    handler: function() {
      var form = this.up('form').getForm();
      if(form.isValid()) {
        form.submit({
          waitMsg: 'Updating Settings',
          success: function(form, action) {
             Ext.Msg.alert('Success', action.result.message);
          },
          failure: function(form, action) {
              Ext.Msg.alert('Failed', action.result.message);
          }
        });
      }
    }
  }]
});

Ext.define('KioskSurvey', {
  extend: 'Ext.data.Model',
  fields: ['value']
});

Ext.create('Ext.data.Store', {
  model: 'KioskSurvey',
  id: 'kioskRequiredStore',
  proxy: {
    type: 'ajax',
    url: '/admin/settings/survey_required',
    reader: {
      type: 'json',
      root: 'kiosk_survey'
    },
    pageParam: undefined,
    limitParam: undefined,
    startParam: undefined     
  },
  autoLoad: true,
  listeners: {
    load: function(store, records, successful, operation, eOpts) {
      if(records[0] !== undefined) {
       Ext.getCmp('KioskSurveyPanel').getComponent('kiosk_survey').setValue(records[0].data.value);
      }
    }
  }
});

Ext.create('Ext.form.Panel', {
  title: 'Kiosk Survey',
  id: 'KioskSurveyPanel',
  url: '/admin/settings/survey_required',
  frame: true,
  margin: 5,
  flex: 1,
  items: [{
    xtype: 'combo',
    fieldLabel: 'Survey Prompt',
    displayField: 'name',
    valueField: 'value',
    store: kiosk_survey_combo,
    name: 'kiosk_survey',
    id : 'kiosk_survey'
  }],
  buttons: [{
    text: 'Update',
    formBind: true,
    handler: function() {
      var form = this.up('form').getForm();
      if(form.isValid()) {
        form.submit({
          waitMsg: 'Updating Settings',
          success: function(form, action) {
             Ext.Msg.alert('Success', action.result.message);
          },
          failure: function(form, action) {
              Ext.Msg.alert('Failed', action.result.message);
          }
        });
      }
    }
  }]
});



Ext.define('KioskSurveyExpiration', {
  extend: 'Ext.data.Model',
  fields: ['value']
});

Ext.create('Ext.data.Store', {
  model: 'KioskSurveyExpiration',
  id: 'kioskSurveyExpiration',
  proxy: {
    type: 'ajax',
    url: '/admin/settings/survey_numeric',
    reader: {
      type: 'json',
      root: 'survey_expiration'
    },
    pageParam: undefined,
    limitParam: undefined,
    startParam: undefined     
  },
  autoLoad: true,
  listeners: {
    load: function(store, records, successful, operation, eOpts) {
      console.log(records);
      if(records[0] !== undefined) {
        Ext.getCmp('KioskSurveyExpirationPanel').getComponent('numeric').setValue(records[0].data.value);
        Ext.getCmp('KioskSurveyExpirationPanel').getComponent('label').setValue(records[1].data.value);
      }
    }
  }
});

Ext.create('Ext.form.Panel', {
  title: 'Kiosk Survey Expiration',
  id: 'KioskSurveyExpirationPanel',
  url: '/admin/settings/kiosk_survey_expiration',
  frame: true,
  margin: 5,
  flex: 1,
  items: [{
    xtype: 'numberfield',
    fieldLabel: '',
    name: 'numeric',
    id : 'numeric'
  }, {
    xtype: 'combo',
    fieldLabel: '',
    displayField: 'name',
    valueField: 'value',
    name: 'label',
    id : 'label',
    store: Ext.create('Ext.data.Store', {
      fields : ['value', 'name'],
      data : [
        { name: 'day(s)', value: 'days' },
        { name: 'month(s)', value: 'months' }
      ]})
  }],
  buttons: [{
    text: 'Update',
    formBind: true,
    handler: function() {
      var form = this.up('form').getForm();
      if(form.isValid()) {
        form.submit({
          waitMsg: 'Updating Settings',
          success: function(form, action) {
             Ext.Msg.alert('Success', action.result.message);
          },
          failure: function(form, action) {
              Ext.Msg.alert('Failed', action.result.message);
          }
        });
      }
    }
  }]
});

Ext.define('LoginText', {
  extend: 'Ext.data.Model',
  fields: ['value']
});

Ext.create('Ext.data.Store', {
  model: 'LoginText',
  id: 'loginTextStore',
  proxy: {
    type: 'ajax',
    url: '/admin/settings/login_text',
    reader: {
      type: 'json',
      root: 'login_additional_text'
    },
    pageParam: undefined,
    limitParam: undefined,
    startParam: undefined			
  },
  autoLoad: true,
  listeners: {
    load: function(store, records, successful, operation, eOpts) {
      if(records[0] !== undefined) {
       Ext.getCmp('loginTextForm').getComponent('loginText').setValue(records[0].data.value);
       Ext.getCmp('loginTextForm').getComponent('childLoginText').setValue(records[1].data.value);
      }
    }
  }
});

Ext.create('Ext.form.Panel', {
  title: 'Customer Login Additional Text',
  url: '/admin/settings/login_text',
  id: 'loginTextForm',
  frame: true,
  margin: 5,
  width: 500,	
  items: [{
    xtype: 'htmleditor',
    fieldLabel: 'Customer Login Additional Text',
    name: 'login_text',
    width: 450,
    height: 200,
    itemId: 'loginText'
  },{
    xtype: 'htmleditor',
    fieldLabel: 'Child Login Additional Text',
    width: 450,
    height: 200,
    name: 'child_login_text',
    itemId: 'childLoginText'
  }],
  buttons: [{
    text: 'Update',
    formBind: true,
    handler: function() {
      var form = this.up('form').getForm();
      if(form.isValid()) {
        form.submit({
          waitMsg: 'Updating Settings',
          success: function(form, action) {
             Ext.Msg.alert('Success', action.result.message);
          },
          failure: function(form, action) {
              Ext.Msg.alert('Failed', action.result.message);
          }
        });
      }
    }
  }]
});

var esign_versions = Ext.create('Ext.data.Store', {
  fields : ['value', 'name'],
  data : [{
    'value' : 'v1.0',
    'name' : 'Print Out'
  }, {
    'value' : 'v2.0',
    'name' : 'Electronic Signature (recommended)'
  }]
});

Ext.create('Ext.form.Panel', {
  title: 'Esignature Options',
  url: '/admin/settings/esign_options',
  id: 'esignatureOptions',
  frame: true,
  margin: 5,
  width: 500,
  items: [{
    fieldLabel: 'Esign Version',
    name: 'esign_version',
    xtype: 'combo',
    emptyText: 'Please Select',
    editable: false,
    displayField: 'name',
    valueField: 'value',
    store: esign_versions,
    queryMode: 'local',
    allowBlank: false,
    listeners: {
      change: function(combo, newValue, oldValue, eOpts) {
        $.ajax({
          url : '/admin/settings/esign_options',
          data : { 'value' : newValue },
          type : 'GET',
          dataType : 'json',
          success : function(result){
            if(!result.success)
            {
              alert('Could not save the');
            }
          }
        });
      }
    }
  }]
});

/*
*   TRANSLATION SETTING TAB
*/

var translation = Ext.create('Ext.data.Store', {
  fields : ['value', 'name'],
  data : [{
    'value' : '0',
    'name' : 'Off'
  }, {
    'value' : '1',
    'name' : 'On'
  }]
});

var translationMode = Ext.create('Ext.data.Store', {
  fields : ['value', 'name'],
  data : [{
    'value' : 'spanish',
    'name' : 'Spanish'
  }, {
    'value' : 'french',
    'name' : 'French'
  }]
});

Ext.create('Ext.form.Panel', {
  title: 'Translation Options',
  url: '/admin/settings/translation_options',
  id: 'translation',
  frame: true,
  margin: 5,
  width: 500,
  items: [{
    fieldLabel: 'Translation On/Off',
    name: 'translation_name',
    xtype: 'combo',
    emptyText: 'Please Select',
    editable: false,
    displayField: 'name',
    valueField: 'value',
    store: translation,
    queryMode: 'local',
    allowBlank: false,
    listeners: {
      afterRender : function(combo) {
        $.ajax({
          url : '/admin/settings/index/translation/active',
          data : { 'action' : 'get' },
          type : 'GET',
          dataType : 'json',
          success : function(result){
            if(result.success)
            {
              combo.setValue(result.output['Setting'].value);
            }
          }
        });
      },
      change: function(combo, newValue, oldValue, eOpts) {
        $.ajax({
          url : '/admin/settings/index/translation/active',
          data : { 'value' : newValue, 'action' : 'set' },
          type : 'GET',
          dataType : 'json',
          success : function(result){
            if(!result.success)
            {
              console.log(result.output);
            }
            else
            {
              console.log(result.output);
            }
          }
        });
      }
    }
  }, {
    fieldLabel: 'Translation Mode',
    name: 'translation_mode',
    xtype: 'combo',
    emptyText: 'Please Select',
    editable: false,
    displayField: 'name',
    valueField: 'value',
    store: translationMode,
    queryMode: 'local',
    allowBlank: false,
    listeners: {
      afterRender : function(combo) {
        $.ajax({
          url : '/admin/settings/index/translation/mode',
          data : { 'action' : 'get' },
          type : 'GET',
          dataType : 'json',
          success : function(result){
            if(result.success)
            {
              combo.setValue(result.output['Setting'].value);
            }
          },
          error : function(result, error)
          {
            console.log(result);
            console.log(error);
          }
        });
      },
      change: function(combo, newValue, oldValue, eOpts) {
        $.ajax({
          url : '/admin/settings/index/translation/mode',
          data : { 'value' : newValue, 'action' : 'set' },
          type : 'GET',
          dataType : 'json',
          success : function(result){
            if(!result.success)
            {
              console.log(result.output);
            }
            else
            {
              console.log(result.output);
            }
          }
        });
      }
    }
  }]
});
/*
*   END OF TRANSLATION SETTING TAB
*/


/*
* SELF SCAN SETTINGS TAB
*/
var self_scan_mode = Ext.create('Ext.data.Store', {
  fields : ['value', 'name'],
  data : [{
    'value' : '0',
    'name' : 'Single Page'
  }, {
    'value' : '1',
    'name' : 'Multiple Pages'
  }]
});

Ext.create('Ext.form.Panel', {
  title: 'Self Scan',
  url: '/admin/settings/translation_options',
  id: 'selfscan',
  frame: true,
  margin: 5,
  width: 500,
  items: [{
    fieldLabel: 'Multiple Page Scan',
    name: 'self_scan_mode',
    xtype: 'combo',
    emptyText: 'Please Select',
    editable: false,
    displayField: 'name',
    valueField: 'value',
    store: self_scan_mode,
    queryMode: 'local',
    allowBlank: false,
    listeners: {
      afterRender : function(combo) {
        $.ajax({
          url : '/admin/settings/index/SelfScan/multiple_pages',
          data : { 'action' : 'get' },
          type : 'GET',
          dataType : 'json',
          success : function(result){
            if(result.success)
            {
              combo.setValue(result.output['Setting'].value);
            }
          }
        });
      },
      change: function(combo, newValue, oldValue, eOpts) {
        $.ajax({
          url : '/admin/settings/index/SelfScan/multiple_pages',
          data : { 'value' : newValue, 'action' : 'set' },
          type : 'GET',
          dataType : 'json',
          success : function(result){
            if(!result.success)
            {
              console.log(result.output);
            }
            else
            {
              console.log(result.output);
            }
          }
        });
      }
    }
  }]
});
/*
* END OF SELF SCAN SETTINGS TAB
*/

/*
* EMAIL CC
*/
Ext.create('Ext.form.Panel', {
  title: 'Email CC (seperated by comma)',
  id: 'emailcc',
  margin: 5,
  frame: true,
  items: [{
    xtype: 'textarea',
    width: 300,
    name: 'ccemails',
    listeners: {
      afterRender: function(field) {
        console.log(field);
        $.ajax({
          url: '/admin/settings/index/Email/cc/?action=get',
          type: 'GET',
          dataType: 'json',
          data : { action : 'get' },
          success: function(resp) {
            field.setValue(resp.output.Setting.value);
          }
        });
      }
    }
  }],
  buttons: [{
    text: 'Update',
    formBind: true,
    handler: function() {
      var form = this.up('form').getForm();

      $.ajax({
        url: '/admin/settings/index/Email/cc',
        data: { action : 'set', value : form.findField('ccemails').value },
        dataType: 'json',
        type: 'GET',
        success : function(resp) {
          console.log(resp);
        }
      });
    }
  }]
});
/*
* END OF EMAIL CC
*/



Ext.onReady(function(){
	Ext.create('Ext.tab.Panel', {
		width: 950,
		frame: true,
		renderTo: 'settingsTabs',
		items: [{
      layout: 'hbox',
			title: 'Self Sign',
			items: [kioskRegistration, 'kioskConfirmation', 'kioskTimeOut']
		},{
      layout: 'hbox',
      title: 'Users',
      items: ['loginTextForm']
    }, {
      layout : 'hbox',
      title: 'Esign',
      items: ['esignatureOptions']
    }, {
      layout : 'hbox',
      title: 'Translate',
      items: ['translation']
    }, {
      layout: 'hbox',
      title: 'Self Scan',
      items: ['selfscan']
    }, {
      layout: 'hbox',
      title: 'Email CC',
      items: ['emailcc']
    }, {
      layout: 'hbox',
      title: 'Survey',
      items: ['KioskSurveyPanel', 'KioskSurveyExpirationPanel']
    }]
	});
});
