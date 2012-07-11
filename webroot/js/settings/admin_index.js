Ext.onReady(function(){
	
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
        {"field":"veteran", "label":"Veteran"}
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
		maxselectionsText: 'Only ' + field.maxSelections + ' custom fields allowed.'
	}); 
	
	var fieldsSelect = Ext.create('Ext.ux.form.field.BoxSelect', {
	    store: fields,
	    width: 290,
	    queryMode: 'local',
	    allowBlank: false,
	    displayField: 'label',
	    valueField: 'field',
	    multiSelect: true,
	    submitValue: false,
	    maxSelections: 5,
	    vtype: 'maxselections'
	});	
	
	var kioskRegistration = Ext.create('Ext.form.Panel', {
		title: 'Kiosk Registration',
		url: '/admin/settings/kiosk_registration',
		margin: 10,
		frame: true,
		width: 300,	
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
	
	var settingsTabs = Ext.create('Ext.tab.Panel', {
		height: 500,
		width: 950,
		frame: true,
		renderTo: 'settingsTabs',
		items: {
			title: 'Self Sign',
			items: [kioskRegistration]
		}
	});
});
