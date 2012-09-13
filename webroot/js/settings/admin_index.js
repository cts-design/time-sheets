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
      {"field":"middle_initital", "label": "Middle Initial"},
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

Ext.create('Ext.data.Store', {
  fields: ['value', 'time'],
  storeId: 'times',
  data: [
    {"value":"10000", "time":"10 Seconds"},
    {"value":"30000", "time":"30 Seconds"},
    {"value":"45000", "time":"45 Seconds"},
    {"value":"60000", "time":"1 Minute"}
  ]
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
        Ext.getCmp('kioskTimeOut').getComponent('cb1').select(records[0].data.value);
        Ext.getCmp('kioskTimeOut').getComponent('cb2').select(records[1].data.value);
      }
    }
  }
});

Ext.create('Ext.form.Panel', {
  title: 'Kiosk Time Out',
  id: 'kioskTimeOut',
  url: '/admin/settings/kiosk_time_out',
  frame: true,
  width: 295,	
  margin: 5,
  items: [{
    xtype: 'combobox',
    itemId: 'cb1',
    fieldLabel: 'Kiosk Time Out',
    queryMode: 'local',
    store: 'times',
    displayField: 'time',
    valueField: 'value',
    name: 'timeout'
  },{
    xtype: 'combobox',
    itemId: 'cb2',
    fieldLabel: 'Kiosk Time Out Reminder Window',
    queryMode: 'local',
    store: 'times',
    displayField: 'time',
    valueField: 'value',
    name: 'reminder'
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

Ext.onReady(function(){
	Ext.create('Ext.tab.Panel', {
		width: 950,
		frame: true,
		renderTo: 'settingsTabs',
		items: {
      layout: 'hbox',
			title: 'Self Sign',
			items: [kioskRegistration, 'kioskConfirmation', 'kioskTimeOut']
		}
	});
});
