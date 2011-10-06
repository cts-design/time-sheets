Ext.onReady(function(){
		
	Ext.define('BarCodeDefinition', {
		extend: 'Ext.data.Model',
	
        fields: [
            {name: 'id', type: 'int' },
            {name: 'name'},
            {name: 'number', type: 'int'},
            {name: 'Cat1-name'},
            {name: 'Cat2-name'},
            {name: 'Cat3-name'},
            {name: 'cat_1'},
            {name: 'cat_2'},
            {name: 'cat_3'},            
            {name: 'created', type: 'date', dateFormat: 'n/j h:ia'},
            {name: 'modified', type: 'date', dateFormat: 'n/j h:ia'},
        ]		
	});

    var store = Ext.create('Ext.data.Store', {
        model: 'BarCodeDefinition',
        pageSize: itemsPerPage,
        proxy: {
        	type: 'ajax',
        	api: {
        		create: '/admin/bar_code_definitions/add/',
        		read: '/admin/bar_code_definitions',
        		update: '/admin/bar_code_definitions/edit/',
        		destroy: '/admin/bar_code_definitions/delete/'
        	},
	        reader: {
	        	type: 'json',
	        	root: 'definitions',
	        },
	        writer: {
	        	root: 'data[BarCodeDefinition]',
	        	encode: true,
	        	writeAllFields: false,
	        	nameProperty: 'mapping'
	        },
	        directionParam: 'direction',
	        simpleSortMode: true  	
        },
        remoteSort: true,      
		autoLoad: true
    });	
    
    Ext.define('DocumentFilingCategory', {
    	extend: 'Ext.data.Model',
    	fields: [{name: 'id', type: 'int'}, {name: 'name'}]
    });   
    
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
    
  	var cat1Store = Ext.create('Ext.data.Store', {
    	model: 'DocumentFilingCategory',
    	proxy: catProxy,
    	autoLoad: true
    });
    
  	var cat2Store = Ext.create('Ext.data.Store', {
    	model: 'DocumentFilingCategory',
    	proxy: catProxy
    });
    
    var cat3Store = Ext.create('Ext.data.Store', {
    	model: 'DocumentFilingCategory',
    	proxy: catProxy
    });
	
    var gridForm = Ext.create('Ext.form.Panel', {
        id: 'barCodeDefinitionsForm',
        frame: true,
        title: 'Bar Code Definitions',
        bodyPadding: 5,
        width: 950,
        layout: 'column',
        fieldDefaults: {
            labelAlign: 'left',
            msgTarget: 'side'
        },
        items: [{
            columnWidth: 0.60,
            xtype: 'gridpanel',
            store: store,
            height: 300,
            title:'Definitions',
            columns: [{
            	text: 'id',
            	dataIndex: 'id',
            	hidden: true
            },{
            	text: 'Name',
            	dataIndex: 'name'
            },{
            	text: 'Number',
            	dataIndex: 'number'
            },{
            	text: 'Cat 1',
            	dataIndex: 'Cat1-name'
            },{
            	text: 'Cat 2',
            	dataIndex: 'Cat2-name'           	
            },{
            	text: 'Cat 3',
            	dataIndex: 'Cat3-name'          	
            }],
            tbar: [{ 
            	xtype: 'button', 
            	text: 'New Definition',
            	icon: '/img/icons/add.png',
            	handler: function() {
            		this.up('form').getForm().reset();
            		this.up('grid').getSelectionModel().deselectAll();
            	} 
            }],
		    dockedItems: [{
		        xtype: 'pagingtoolbar',
		        store: store,
		        dock: 'bottom',
		        displayInfo: true
		    }],            
            listeners: {
                selectionchange: function(model, records) {
                    if (records[0]) {
                    	console.log(records);
                    	var vals = {
                    		name: records[0].data.name,
                    		number: records[0].data.number
                    	}
                        this.up('form').getForm().loadRecord(records[0]);

                        
                        if(records[0].data.cat_2) {
                        	Ext.getCmp('cat2Name').enable();
                        }
                        else {
                        	Ext.getCmp('cat2Name').disable();
                        }
                        if(records[0].data.cat_3) {
                        	Ext.getCmp('cat3Name').enable();
                        }
                        else {
                        	Ext.getCmp('cat3Name').disable();
                        }                        
                    }
                }
            }
        }, {
            columnWidth: 0.4,
            margin: '0 0 0 10',
            xtype: 'fieldset',
            frame: true,
            title:'Add / Edit Form',
            defaults: {
                width: 240,
                labelWidth: 90
            },
            defaultType: 'textfield',
            items: [{
            	name: 'id',
            	xtype: 'hidden'
            },{
                fieldLabel: 'Name',
                name: 'name',
                allowBlank: false
            },{
                fieldLabel: 'Number',
                name: 'number',
                allowBlank: false
            },{
                fieldLabel: 'Cat 1',
                name: 'Cat1-name',
                id: 'cat1Name',
                store: cat1Store,
                displayField: 'name',
                valueField: 'id',        
                queryMode: 'remote',
                xtype: 'combo', 
                value: null,
                listeners: {
                	select: function(combo, records, Eopts) {
                	}
                }
            },{
                fieldLabel: 'Cat 2',
                name: 'Cat2-name',
                id: 'cat2Name',
                xtype: 'combo',
                disabled: true,
                store: cat2Store,
                displayField: 'name',
                valueField: 'id',
                queryMode: 'remote',
                value: null
            },{
                fieldLabel: 'Cat 3',
                name: 'Cat3-name',
                id: 'cat3Name',
                xtype: 'combo',
                store: cat3Store,
                disabled: true,
                displayField: 'name',
                valueField: 'id',
                queryMode: 'remote',
                value: null
            }]
        }],
		buttons: [{
			text: 'Save',
			handler: function() {
				var form = this.up('form').getForm();
				var vals = form.getValues();
				if(form.isValid()) {
					if(vals.id != '') {
						var barCodeDefinition = store.getById(parseInt(vals.id, 10));
						barCodeDefinition.set(vals)
					}
					else {
						var barCodeDefinition = Ext.create('BarCodeDefinition', form.getValues());
						store.add(barCodeDefinition);
					}			
					store.sync();
					form.reset();
				}		
			}      	
		}],        
        renderTo: 'barCodeDefinitions'
    });	
});