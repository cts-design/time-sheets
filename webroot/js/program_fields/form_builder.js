/**
 * @author Brandon Cordell
 * @copyright Complete Technology Solutions 2011
 * @link http://ctsfla.com
 * @package ATLAS V3
 */

if (typeof console == "undefined") {
    window.console = {
        log: function () {}
    };
}

FormBuilder = function() {};

FormBuilder.prototype = {
	init: function() {
		Ext.QuickTips.init();
		Ext.BLANK_IMAGE_URL = '/img/ext/default/s.gif';
		
		this.initDataStores();
		this.initPanels();
	},
	initDataStores: function() {
		
	},
	initPanels: function() {		
		var tb = new Ext.Toolbar({
			items: [{
				id: 'FBResetBtn',
				text: 'Reset Form',
				icon: '/img/icons/reset.png',
				tooltip: 'Reset the form fields',
				scope: this,
				handler: function() {
					this.resetForm();
				}
			}]
		});
		
		var buttons = [{
			xtype: 'button',
			text: 'Textfield',
			scope: this,
			handler: function() {
				this.addField('textfield');
			}
		},{
			xtype: 'button',
			text: 'Dropdown',
			scope: this,
			handler: function() {
				this.addField('combo');
			}
		}];
		
		var quickButtons = [{
			xtype: 'button',
			text: 'Datefield',
			scope: this,
			handler: function() {
				this.addField('datefield');
			}
		}];
		
		this.panel = new Ext.Panel({
			id: 'FBPanel',
			tbar: tb,
			applyTo: 'form-builder',
			title: 'Form Builder',
			height: 400,
			layout: 'border',
			items: [{
				id: 'FBTools',
				region: 'west',
				width: 200,
				items: [{
					layout: 'accordion',
					border: false,
					height: 150,
					defaults: {
						bodyStyle: 'padding: 8px'
					},
					items: [{
						title: 'Form Tools',
						items: buttons
					},{
						title: 'Quick Tools',
						items: quickButtons
					}]
				},{
					id: 'FBProperties',
					xtype: 'propertygrid',
					border: false,
					autoHeight: true,
					autoScroll: true,
					view: new Ext.grid.GridView({
				   		forceFit: true
				   	}),
				   	listeners: {
				   		afteredit: {
				   			fn: function(e) {
				   				console.log(e);
				   				switch (e.record.id) {
				   					case 'Label':
				   						this.selectedElement.label.dom.textContent = e.value + ":";
				   						break;
				   					case 'Default Value':
				   						this.selectedElement.el.dom.defaultValue = e.value;
				   						break;
				   				}
				   			},
				   			scope: this
				   		}
				   }
				}]
			},{
				id: 'FBFormPanel',
				region: 'center',
				xtype: 'form',
				bodyStyle: 'padding: 20px',
				height: 400,
				autoScroll: true
			}]
		});
		
		this.formPanel = Ext.getCmp('FBFormPanel');
		this.properties = Ext.getCmp('FBProperties');
		this.formPanel.el.on('click', function(e, el) {
			var formElement = Ext.getCmp(e.target.id);
			
			if (formElement) {				
				this.selectedElement = formElement;
				this.properties.setSource(formElement.attributes);
				this.selectedElement.on('change', function() {
					console.log('changed');
				}, this);
				console.log(formElement);
			}
		}, this)
	},
	addField: function(fieldType) {
		var config = {
			xtype: fieldType,
			fieldLabel: 'Field Label'
		};
		
		var attributes = {
			'Label': 'Field Label',
			'Default Value': '',
			'Class': ''			
		};
		
		switch (fieldType) {
			case 'textfield':
				attributes['Max Length'] = '';
				attributes['Min Length'] = '';
				attributes['Class'] = 'text';
				break;
				
			case 'datefield':
				attributes['Date Format'] = ['m/d/y','d/m/y', 'y/m/d'];
				attributes['Min Year'] = '';
				attributes['Max Year'] = '';
				attributes['Class'] = 'date';
				break;
				
			case 'combo':
				config.typeAhead = false;
				config.triggerAction = 'all';
				config.mode = 'local';
				config.store = new Ext.data.ArrayStore({
			        id: 0,
			        fields: [
						'options'
			        ],
			        data: [['item1'], ['item2']]
			   	});
			   	config.valueField = 'myId';
			   	config.displayField = 'displayText';
			   	
			   	attributes['Selected'] = '';
			   	attributes['Options'] = config.store.data.join(',');
				break;
		}
		
		var newField = this.formPanel.add(config);
		newField.attributes = attributes;
		
		this.formPanel.doLayout();
	},
	resetForm: function() {
		Ext.Msg.show({
			title: 'Are you sure?',
			msg: 'Resetting the form will cause all unsaved form fields to be lost',
			buttons: Ext.Msg.YESNO,
			icon: Ext.Msg.WARNING,
			animEl: 'FBResetBtn',
			scope: this,
			fn: function(btn) {
				if (btn === 'yes') {
					this.formPanel.removeAll();
				}
			}
		});
	}
}

var formBuilder = new FormBuilder;
Ext.onReady(formBuilder.init, formBuilder);
