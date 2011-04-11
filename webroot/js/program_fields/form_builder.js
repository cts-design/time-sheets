/**
 * @author Brandon Cordell
 * @copyright Complete Technology Solutions 2011
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
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
		// this will hold the temporary data of Combo boxes, Checkbox groups, and Radio groups
		this.tempOptionsStore = new Ext.data.ArrayStore({
			idIndex: 0,
			storeId: 'FBOptionsStore',
			fields: [
				'options'
			]
		});
		
		var ProgramField = Ext.data.Record.create([
			{ name: 'id', type: 'int' },
			{ name: 'program_id', type: 'int' },
			'label',
			'type',
			'name',
			'attributes',
			'options',
			'validation',
			{ name: 'created', type: 'date', dateFormat: 'Y-m-d H:i:s' },
			{ name: 'modified', type: 'date', dateFormat: 'Y-m-d H:i:s' }
		]);
		
		var proxy = new Ext.data.HttpProxy({
			api: {
				create:  { url: '/admin/program_fields/create',  method: 'POST' },
				read:    { url: '/admin/program_fields/read',    method: 'GET'  },
				update:  { url: '/admin/program_fields/update',  method: 'POST' },
				destroy: { url: '/admin/program_fields/destroy', method: 'POST' }
			}
		});
		
		var reader = new Ext.data.JsonReader({
			root: 'ProgramFields'
		}, ProgramField);
		
		var writer = new Ext.data.JsonWriter({
			encode: true
		});
		
		this.fieldStore = new Ext.data.Store({
			autoSave: false,
			proxy: proxy,
			reader: reader,
			writer: writer
		});	
	},
	initPanels: function() {		
		var tb = new Ext.Toolbar({
			items: [{
				id: 'FBSaveBtn',
				text: 'Save Form',
				icon: '/img/icons/save.png',
				tooltip: 'Save the form',
				disabled: true,
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
				disabled: true,
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
				this.addField('radiogroup');
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
				this.addField('checkboxgroup');
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
				this.addField('button');
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
					propertyNames: {
						'class': 'Class',
						cols: 'Cols',
						dateFormat: 'Date Format',
						'default': 'Default Value',
						label: 'Label',
						maxLength: 'Max Length',
						maxYear: 'Max Year',
						minYear: 'Min Year',
						multiple: 'Multiple',
						options: 'Options',
						rows: 'Rows',
						selected: 'Selected'
					},
					view: new Ext.grid.GridView({
						forceFit: true
					}),
					listeners: {
						afteredit: {
							fn: function(e) {
								var store = this.tempOptionsStore;
								
								switch (e.record.id) {
									case 'label':
										this.selectedElement.label.dom.textContent = e.value + ":";
										break;
									case 'defaultValue':
										this.selectedElement.el.dom.defaultValue = e.value;
										break;
									case 'options':
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
								if (e.record.id === 'selected') {
									var options = this.selectedElement.exportData.options.options.split(',');

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
				region: 'center',
				layout: 'border',
				items: [{
					id: 'FBFormPanel',
					region: 'center',
					xtype: 'form',
					bodyStyle: 'padding: 20px',
					height: 400,
					autoScroll: true
				},{
					region: 'south',
					xtype: 'form',
					height: 100,
					items: [{
						xtype: 'textfield',
						fieldLabel: 'Label'
					}]
				}]
			}]
		});
		
		this.formPanel = Ext.getCmp('FBFormPanel');
		this.properties = Ext.getCmp('FBProperties');
		this.formPanel.el.on('click', function(e, el) {
			var formElement = Ext.getCmp(e.target.id);
			
			console.log(e);
			
			if (formElement) {				
				this.selectedElement = formElement;
				this.properties.setSource(formElement.exportData.attributes);
				this.properties.setSource(formElement.exportData.options);
			} else {
				this.selectedElement = null;
				this.properties.setSource({});
			}
		}, this);
		
		this.formPanel.el.on('contextmenu', function(e) {
			e.preventDefault();
			console.log(e);
			this.contextMenu.showAt(e.getXY());
		}, this);
	},
	addField: function(fieldType) {
		var resetbtn = Ext.getCmp('FBResetBtn');
		var savebtn = Ext.getCmp('FBSaveBtn');
		
		if (resetbtn.disabled) {
			resetbtn.enable();
		}
		
		if (savebtn.disabled) {
			savebtn.enable();
		}
		
		var config = {
			xtype: fieldType,
			fieldLabel: 'Field Label'
		};
		
		var exportData = {
			label: 'Field Label',
			attributes: {
				'class': ''
			},
			options: {},
			validation: {}
		};
		
		switch (fieldType) {
			case 'textfield':
				exportData.options['default'] = '';
				exportData.options.maxLength = '';
				break;
				
			case 'textarea':
				exportData.options.cols = '';
				exportData.options.rows = '';
				
				config.height = 75;
				config.width = 250;
				break;
				
			case 'combo':
				exportData.options.selected = '';
				exportData.options.options = '';
				
				config.typeAhead = false;
				config.triggerAction = 'all';
				config.mode = 'local';
				config.store = this.tempOptionsStore;
				config.valueField = 'options';
				config.displayField = 'options';
				break;
				
			case 'radiogroup':
				config.columns = 1;
				config.items = [{
					boxLabel: 'Item 1',
					name: 'radio'
				},{
					boxLabel: 'Item 2',
					name: 'radio'
				}];
				break;
			
			case 'checkboxgroup':
				config.itemCls = 'x-check-group-alt';
				config.columns = 1;
				config.items = [
					{ boxLabel: 'Item 1', name: 'cb-col-1' },
					{ boxLabel: 'Item 2', name: 'cb-col-1' }
				];
				break;
			
			case 'datefield':
				exportData.options.dateFormat = ['m/d/y','d/m/y', 'y/m/d'];
				exportData.options.minYear = '';
				exportData.options.maxYear = '';
				break;
			
			case 'button':
				delete config.fieldLabel;
				config.text = 'Submit';
				break;

		}
		
		var newField = this.formPanel.add(config);
		newField.exportData = exportData;
		
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
					Ext.getCmp('FBSaveBtn').disable();
					Ext.getCmp('FBResetBtn').disable();
					this.properties.setSource({});
				}
			}
		});
	},
	saveForm: function() {
		var newRecord;
		this.selectedRecord = null;
		this.properties.setSource({});
		
		Ext.each(this.formPanel.items.items, function(item, index, allItems) {
			newRecord = new this.fieldStore.recordType({
				program_id: 1,
				label: item.exportData.label,
				type: 'select',
				name: item.exportData.label.underscore().lowercase(),
				attributes: Ext.util.JSON.encode(item.exportData.attributes),
				options: Ext.util.JSON.encode(item.exportData.options),
				validation: Ext.util.JSON.encode(item.exportData.validation)
			});
			
			this.fieldStore.add(newRecord);
		}, this);
		
		this.fieldStore.save();
		Ext.getCmp('FBSaveBtn').disable();
	}
};

var formBuilder = new FormBuilder();
Ext.onReady(formBuilder.init, formBuilder);
