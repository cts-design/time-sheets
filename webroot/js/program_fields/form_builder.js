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
		
		// init menu
		this.contextMenu = new Ext.menu.Menu({
			items: [{
				text: 'Edit'
			},{
				text: 'Another'
			}]
		});
	},
	initDataStores: function() {
		this.tempOptionsStore = new Ext.data.ArrayStore({
			idIndex: 0,
			storeId: 'FBOptionsStore',
			fields: [
				'options'
			]
		});
	},
	initPanels: function() {		
		var tb = new Ext.Toolbar({
			items: [{
				id: 'FBSaveBtn',
				text: 'Save Form',
				icon: '/img/icons/save.png',
				tooltip: 'Save the form',
				scope: this,
				handler: function() {
					this.saveForm();
				}				
			},
			'-',
			{
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
			icon: '/img/icons/input_text.png',
			cls: 'accordionbtn',
			overCls: 'x-btn-hover',
			handler: function() {
				this.addField('textfield');
			}
		},{
			xtype: 'button',
			text: 'Textarea',
			scope: this,
			icon: '/img/icons/input_textarea.png',
			cls: 'accordionbtn',
			overCls: 'x-btn-hover',
			scale: 'medium',
			handler: function() {
				this.addField('textarea');
			}
		},{
			xtype: 'button',
			text: 'Dropdown',
			scope: this,
			icon: '/img/icons/input_select.png',
			cls: 'accordionbtn',
			overCls: 'x-btn-hover',
			handler: function() {
				this.addField('combo');
			}
		},{
			xtype: 'button',
			text: 'Radio Group',
			scope: this,
			icon: '/img/icons/input_radio.png',
			cls: 'accordionbtn',
			overCls: 'x-btn-hover',
			scale: 'medium',
			handler: function() {
				this.addField('textarea');
			}
		},{
			xtype: 'button',
			text: 'Checkbox Group',
			scope: this,
			icon: '/img/icons/input_checkbox.png',
			cls: 'accordionbtn',
			overCls: 'x-btn-hover',
			scale: 'medium',
			handler: function() {
				this.addField('textarea');
			}
		},{
			xtype: 'button',
			text: 'Submit Button',
			scope: this,
			icon: '/img/icons/input_submit.png',
			cls: 'accordionbtn',
			overCls: 'x-btn-hover',
			scale: 'medium',
			handler: function() {
				this.addField('textarea');
			}
		}];
		
		var quickButtons = [{
			xtype: 'button',
			text: 'Full Name',
			scope: this,
			icon: '/img/icons/input_fullname.png',
			cls: 'accordionbtn',
			overCls: 'x-btn-hover',
			handler: function() {
				this.addField('textfield');
			}
		},{
			xtype: 'button',
			text: 'Email',
			scope: this,
			icon: '/img/icons/input_email.png',
			cls: 'accordionbtn',
			overCls: 'x-btn-hover',
			scale: 'medium',
			handler: function() {
				this.addField('textarea');
			}
		},{
			xtype: 'button',
			text: 'Address',
			scope: this,
			icon: '/img/icons/input_address.png',
			cls: 'accordionbtn',
			overCls: 'x-btn-hover',
			handler: function() {
				this.addField('combo');
			}
		},{
			xtype: 'button',
			text: 'Datepicker',
			scope: this,
			icon: '/img/icons/input_datepicker.png',
			cls: 'accordionbtn',
			overCls: 'x-btn-hover',
			scale: 'medium',
			handler: function() {
				this.addField('datefield');
			}
		}];
		
		this.panel = new Ext.Panel({
			id: 'FBPanel',
			tbar: tb,
			applyTo: 'form-builder',
			title: 'Form Builder',
			height: 500,
			layout: 'border',
			items: [{
				id: 'FBTools',
				region: 'west',
				width: 200,
				items: [{
					layout: 'accordion',
					border: false,
					height: 250,
					defaults: {
						bodyStyle: 'padding:8px'
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
				   				var store = this.tempOptionsStore;
				   				console.log(e);
				   				
				   				switch (e.record.id) {
				   					case 'Label':
				   						this.selectedElement.label.dom.textContent = e.value + ":";
				   						break;
				   					case 'Default Value':
				   						this.selectedElement.el.dom.defaultValue = e.value;
				   						break;
				   					case 'Options':
				   						var records = [],
				   						values = e.value.split(',');
				   						store.removeAll();
				   						
				   						Ext.each(values, function(item, index) {
				   							store.insert(index, new store.recordType({options: item.trim()}));
				   						}, this);
										break;
				   				}
				   			},
				   			scope: this
				   		},
				   		validateedit: {
				   			fn: function(e) {
				   				if (e.record.id === 'Selected') {
									var options = this.selectedElement.attributes['Options'].split(',');
									
									Ext.each(options, function(item, index) {
										options[index] = item.trim();
									}, this);
									
									if (options.indexOf(e.value) === -1) {
										Ext.Msg.alert('Invalid Value For Selected', 'The selected value must be in your options');
										return false;
									}
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
		}, this);
		
		this.formPanel.el.on('contextmenu', function(e) {
			e.preventDefault();
			this.contextMenu.showAt(e.getXY());
		}, this);
	},
	addField: function(fieldType) {
		var config = {
			xtype: fieldType,
			fieldLabel: 'Field Label'
		};
		
		var attributes = {
			'Label': 'Field Label',
			'Class': ''			
		};
		
		switch (fieldType) {
			case 'textfield':
				attributes['Default Value'] = '';
				attributes['Max Length'] = '';
				attributes['Class'] = '';
				break;
				
			case 'textarea':
				attributes['Cols'] = '';
				attributes['Rows'] = '';
				
				config.height = 75;
				config.width = 250;
				break;
				
			case 'datefield':
				attributes['Date Format'] = ['m/d/y','d/m/y', 'y/m/d'];
				attributes['Min Year'] = '';
				attributes['Max Year'] = '';
				attributes['Class'] = '';
				break;
				
			case 'combo':
				attributes['Selected'] = '';
			   	attributes['Options'] = '';
				
				config.typeAhead = false;
				config.triggerAction = 'all';
				config.mode = 'local';
				config.store = this.tempOptionsStore;
			   	config.valueField = 'options';
			   	config.displayField = 'options';
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
	},
	saveForm: function() {
		Ext.each(this.formPanel.items.items, function(item, index, allItems) {
			console.log(item.attributes);
		}, this)
	}
}

var formBuilder = new FormBuilder;
Ext.onReady(formBuilder.init, formBuilder);
