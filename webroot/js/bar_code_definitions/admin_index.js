Ext.onReady(function(){
		
	Ext.define('BarCodeDefinition', {
		extend: 'Ext.data.Model',
	
        fields: [
            {name: 'id', type: 'int' },
            {name: 'name'},
            {name: 'number', type: 'int'},
            {name: 'Cat1-name', serverKey: 'cat_1'},
            {name: 'Cat2-name', serverKey: 'cat_2'},
            {name: 'Cat3-name', serverKey: 'cat_3'},     
            {name: 'DocumentQueueCategory-name', serverKey: 'document_queue_category_id'},        
            {name: 'created', type: 'date', dateFormat: 'n/j h:ia'},
            {name: 'modified', type: 'date', dateFormat: 'n/j h:ia'}
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
	        	root: 'definitions'
	        },
	        writer: {
	        	root: 'data[BarCodeDefinition]',
	        	encode: true,
	        	writeAllFields: false,
	        	nameProperty: 'serverKey'
	        },
	        directionParam: 'direction',
	        simpleSortMode: true  	
        },
        remoteSort: true,      
		autoLoad: true,
		listeners: {
			write: function(store, operation, eOpts) {
				var responseTxt = Ext.JSON.decode(operation.response.responseText);
				if(!responseTxt.success || !operation.success )	{
					var msg = null;
					switch(operation.action) {
						case 'destroy' : 
							msg = 'Unable to delete definition.';
							break;
						case 'create' :
							msg = 'Unable to create definition.';
							break;
						case 'update' :
							msg = 'Unable to update definition.';
							break;	
					}
					Ext.MessageBox.alert('Status', msg);
				}
				if(responseTxt.success) {
					Ext.MessageBox.hide();
					if(operation.action === 'create' || operation.action === 'update') {
						gridForm.getForm().reset();
	        			Ext.getCmp('cat2Name').disable();
	        			Ext.getCmp('cat3Name').disable();
	        			store.load();							
					}
					if(operation.action === 'destroy') {
	            		gridForm.getForm().reset();
	            		Ext.getCmp('cat2Name').disable();
	                	Ext.getCmp('cat3Name').disable(); 
	                	store.load();						
					}					
				}	
			},
			beforesync: function(){
				Ext.MessageBox.wait('Please Wait......');
			}
		}
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
    	proxy: catProxy,
    	listeners: {
    		load: function(store, records, successful, operation, eOpts) {
    			if(records[0]) {
    				Ext.getCmp('cat2Name').enable();
    			}
    		}
    	}
    });
    
    var cat3Store = Ext.create('Ext.data.Store', {
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
    
    Ext.define('DocumentQueueCategory', {
    	extend: 'Ext.data.Model',
		fields: [{name: 'id', type: 'int'}, {name: 'name'}]    	
    });
    
  	var docCatStore = Ext.create('Ext.data.Store', {
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
            columnWidth: 0.7,
            xtype: 'gridpanel',
            id: 'barCodeDefGrid',
            store: store,
            height: 315,
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
            	text: 'Queue Cat',
            	dataIndex: 'DocumentQueueCategory-name',
            	flex: 1          	
            },{
            	text: 'Cat 1',
            	dataIndex: 'Cat1-name',
            	flex: 1
            },{
            	text: 'Cat 2',
            	dataIndex: 'Cat2-name',
            	flex: 1           	
            },{
            	text: 'Cat 3',
            	dataIndex: 'Cat3-name',
            	flex: 1          	
            }],
            tbar: [{xtype: 'tbfill'},{ 
            	xtype: 'button', 
            	text: 'New Definition',
            	icon: '/img/icons/add.png',
            	handler: function() {
		            this.up('form').getForm().reset();
            		Ext.getCmp('cat2Name').disable();
                	Ext.getCmp('cat3Name').disable();
            		this.up('grid').getSelectionModel().deselectAll();
            	} 
            },{
            	xtype: 'button',
            	text: 'Delete Definition',
            	icon: '/img/icons/delete.png',
            	handler: function() {
            		Ext.MessageBox.confirm('Confirm', 'Delete the selected record?', function(id){
            			if(id === 'yes') {	
		            		var record = Ext.getCmp('barCodeDefGrid').getSelectionModel().getLastSelected();
		            		store.remove(record);       		
		            		store.sync();
	            		} 
	            		else {
	            			return false;
	            		}         			
            		});
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
                	Ext.getCmp('cat2Name').disable();
                	Ext.getCmp('cat3Name').disable();  
                    if (records[0]) {
                    	var vals = {
                    		name: records[0].data.name,
                    		number: records[0].data.number
                    	}
                        this.up('form').getForm().loadRecord(records[0]);
                    }
                    this.up('form').getForm().clearInvalid();
                }
            }
        }, {
            columnWidth: 0.3,
            margin: '0 0 0 10',
            padding: 10,
            xtype: 'fieldset',
            frame: true,
            title:'Add / Edit Form',
            defaults: {
                width: 245,
                labelWidth: 50
            },
            defaultType: 'textfield',
            items: [{
            	name: 'id',
            	xtype: 'hidden'
            },{
                fieldLabel: 'Name',
                name: 'name',
                allowBlank: false,
                maxLength: 100,
                enforceMaxLength: true,                
            },{
                fieldLabel: 'Number',
                xtype: 'numberfield',
                width: 100,
                minValue: 0,
                maxValue: 99999,
                maxLength: 5,
                enforceMaxLength: true,
                hideTrigger: true,
                name: 'number',
                allowBlank: false
            },{
                fieldLabel: 'Queue Cat',
                name: 'DocumentQueueCategory-name',
                id: 'queueCat',
                xtype: 'combo',
                store: docCatStore,
                displayField: 'name',
                valueField: 'id',
                queryMode: 'remote',
                value: null,
                allowBlank: false
            },{
                fieldLabel: 'Cat 1',
                name: 'Cat1-name',
                id: 'cat1Name',               
                store: cat1Store,
                displayField: 'name',
                valueField: 'id',        
                queryMode: 'local',
                xtype: 'combo', 
                value: null,
                allowBlank: false,
                listeners: {
                	select: function(combo, records, Eopts) {
                		if(records[0]) {
                			Ext.getCmp('cat2Name').disable();
                			Ext.getCmp('cat2Name').reset();
                			Ext.getCmp('cat3Name').disable();
                			Ext.getCmp('cat3Name').reset(); 
                			cat2Store.load({params: {parentId: records[0].data.id}});
                		}
                		
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
                queryMode: 'local',
                value: null,
                allowBlank: false,
                listeners: {
                	select: function(combo, records, Eopts) {
                		if(records[0]) {
                			Ext.getCmp('cat3Name').disable();
                			Ext.getCmp('cat3Name').reset();
							cat3Store.load({params: {parentId: records[0].data.id}});
                		}            		
                	}
                }                
            },{
                fieldLabel: 'Cat 3',
                name: 'Cat3-name',
                id: 'cat3Name',
                xtype: 'combo',
                store: cat3Store,
                disabled: true,
                displayField: 'name',
                valueField: 'id',
                queryMode: 'local',
                value: null,
                allowBlank: false
            }]
        }],
		buttons: [{
			text: 'Save',
			formBind: true,
			handler: function() {
				var form = this.up('form').getForm();
				var vals = form.getValues();
				if(form.isValid()) {
					if(vals.id != '') {
						var barCodeDefinition = store.getById(parseInt(vals.id, 10));
						barCodeDefinition.beginEdit();
						barCodeDefinition.set(vals);
						barCodeDefinition.endEdit();
					}
					else {
						var barCodeDefinition = Ext.create('BarCodeDefinition', form.getValues());
						store.add(barCodeDefinition);
					}			
					store.sync();				
				}		
			}      	
		}],        
        renderTo: 'barCodeDefinitions'
    });	
});