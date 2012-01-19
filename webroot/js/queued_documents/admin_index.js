
Ext.define('QueuedDocument', {
	extend: 'Ext.data.Model',
	fields:[
		'id', 'queue_cat', 'scanned_location', 'queued_to_customer',
		'locked_by', 'locked_status', 'last_activity_admin', 'created', 'modified'
	],	
	lockDocument: function() {
		var docQueueWindowMask = 
			new Ext.LoadMask(Ext.getCmp('docQueueWindow'), {msg:"Loading Document..."});		
		docQueueWindowMask.show();
		var docStore = Ext.data.StoreManager.lookup('queuedDocumentsStore');
		Ext.Ajax.request({
		    url: '/admin/queued_documents/lock_document',
		    params: {
		        doc_id: this.get('id')
		    },
		    success: function(response, opts){
		        var text = Ext.JSON.decode(response.responseText);
		        if(text.success) {	        	
		        	if(text.unlocked != undefined) {
			        	var unlockedDoc = docStore.getById(text.unlocked);
			        	if(unlockedDoc) {
				        	unlockedDoc.set('locked_status', 'Unlocked');
				        	unlockedDoc.set('locked_by', '');
				        	unlockedDoc.commit();
			        	}
		        	}
		        	this.set('locked_status', 'Locked');
		        	this.set('locked_by', text.admin);
		        	this.set('last_activity_admin', text.admin);
		        	this.commit();
		        	Ext.getCmp('pdfFrame').el.dom.src = 
						'/admin/queued_documents/view/'+text.locked+'/#toolbar=1&statusbar=0&navpanes=0&zoom=50';
					docQueueWindowMask.hide()
		        }
		        else {
		        	opts.failure();
		        }	
		    },
		    failure: function(response, opts) {
		    	Ext.MessageBox.alert(
		    		'Failure', 'Unable to lock document for viewing.<br />'
		    		+'Make sure it is not locked by someone else.<br />'
		    		+'Please use the refresh button in the grid toolbar<br />'
		    		+'to update the grid view if nessesary.'
		    	);
		    	docQueueWindowMask.hide();
		    	docStore.load();
			    Ext.getCmp('pdfFrame').el.dom.src = '';		    		
		    },
		    scope: this
		});
	}
});


Ext.create('Ext.data.Store', {
    storeId:'queuedDocumentsStore',
    pageSize: 5,
	model: QueuedDocument,
    proxy: {
        type: 'ajax',
		url: '/admin/queued_documents',
        reader: {
            type: 'json',
            root: 'docs',
			totalProperty: 'totalCount'
        }
    }
});

var contextMenu = Ext.create('Ext.menu.Menu', {
	items: [{
		text: 'View Doc',
		icon:  '/img/icons/note_add.png',
    	handler: function() {
    		var selectionModel = Ext.getCmp('queuedDocGrid').getView().getSelectionModel();
    		var doc = selectionModel.getLastSelected();
    		doc.lockDocument();
    	}		
	}]
});

Ext.define('Atlas.grid.QueuedDocPanel', {
	extend: 'Ext.grid.Panel',
	alias: 'widget.atlasdocqueuegridpanel',
	title: 'Document Queue',
	store: Ext.data.StoreManager.lookup('queuedDocumentsStore'),
    columns: [{ 
			header: 'Id',
		  	dataIndex: 'id',
	 		width: 75
		},{ 
			header: 'Queue Cat', 
			dataIndex: 'queue_cat',
			width: 75
		},{ 
			header: 'Scanned Location',
			dataIndex: 'scanned_location' 
		},{
			header: 'Queued to Customer',
			dataIndex: 'queued_to_customer',
			width: 150
		},{ 
			header: 'Locked Status', 
			dataIndex: 'locked_status',
			width: 80
		},{ 
			header: 'Locked By',
			dataIndex: 'locked_by'
		},{
			header: 'Last Act. Admin',
			dataIndex: 'last_activity_admin'
		},{
			header: 'Created',
			dataIndex: 'created',
			width: 125
		},{ 
			header: 'Modified', 
			dataIndex: 'modified',
			width: 125
		}],
		viewConfig: {
			singleSelect: true,
			emptyText: 'No records at this time.',
	        listeners: {
	            itemcontextmenu: function(view, rec, node, index, e) {
	                e.stopEvent();
	                contextMenu.showAt(e.getXY());	    			    			
		     		docId = rec.data.id;
		            return false;
		        }
	    	}
		},
	    dockedItems: [{
	        xtype: 'pagingtoolbar',
	        store: Ext.data.StoreManager.lookup('queuedDocumentsStore'),
	        dock: 'bottom',
	        displayInfo: true
	    }]					
});


Ext.define('Location', {
	extend: 'Ext.data.Model',
	fields: ['id', 'name']
});

Ext.create('Ext.data.Store', {
	model: 'Location',
	storeId: 'locationsStore',
	proxy: {
		type: 'ajax',
		url: '/admin/locations/get_location_list',
		reader: {
			type: 'json',
			root: 'locations'
		},
		limitParam: undefined,
		pageParam: undefined,
		startParam: undefined				
	},
	autoLoad: true
});

Ext.define('QueueCategory', {
	extend: 'Ext.data.Model',
	fields: ['id', 'name']
});

Ext.create('Ext.data.Store', {
	model: 'QueueCategory',
	storeId: 'queueCatgoriesStore',
	proxy: {
		type: 'ajax',
		url: '/admin/document_queue_categories/get_cats',
		reader: {
			type: 'json',
			root: 'cats'
		},
		limitParam: undefined,
		pageParam: undefined,
		startParam: undefined				
	},
	autoLoad: true
});

Ext.define('DocumentQueueFilter', {
	extend: 'Ext.data.Model',
	fields: ['id', 'locations', 'queue_cats', 'from_date', 'to_date', 'auto_load_docs']
});

Ext.create('Ext.data.Store', {
	model: 'DocumentQueueFilter',
	storeId: 'documentQueueFiltersStore',
	proxy: {
		type: 'ajax',
		url: '/admin/document_queue_filters/get_filters',
		reader: {
			type: 'json',
			root: 'filters'
		},
		limitParam: undefined,
		pageParam: undefined,
		startParam: undefined				
	},
	listeners: {
		load: function(store, records, successful, operation, eOpts) {
			if(records[0] != undefined) {
				Ext.getCmp('documentQueueFilterForm').loadRecord(records[0]);
			}
		}
	},
	autoLoad: true
});


Ext.define('Atlas.form.DocQueueFilterPanel', {
	extend: 'Ext.form.Panel',
	alias: 'widget.docqueuefilterformpanel',
	id: 'documentQueueFilterForm',
	bodyPadding: 10,
	layout: 'anchor',
	defaults: {
		labelWidth: 90,
		anchor: '100%'
	},
	items:[{
		xtype: 'boxselect',
		id: 'locationsSelect',
		encodeSubmitValue: true,
		fieldLabel: 'Locations',
		name: 'locations',
		emptyText: 'Please Select',
		displayField: 'name',
		valueField: 'id',		
		store: Ext.data.StoreManager.lookup('locationsStore')
	},{
		xtype: 'boxselect',
		fieldLabel: 'Queue Cats',
		id: 'queueCatsSelect',
		encodeSubmitValue: true,
		name: 'queue_cats',
		emptyText: 'Please Select',
		displayField: 'name',
		valueField: 'id',
		store: Ext.data.StoreManager.lookup('queueCatgoriesStore')				
	},{
        xtype: 'datefield',
        fieldLabel: 'From',
        name: 'from_date',
        maxValue: new Date()
    },{
        xtype: 'datefield',
        fieldLabel: 'To',
        name: 'to_date',
        maxValue: new Date()
    },{
    	xtype: 'checkbox',
    	fieldLabel: 'Auto Load Docs',
    	name: 'auto_load_docs',
    	inputValue: 'yes'
    },{
    	xtype: 'hidden',
    	name: 'id'
    }],
    buttonAlign: 'left',
    buttons:[{
    	text: 'Save',
    	icon:  '/img/icons/save.png',
    	formBind: true,
    	disabled: true,
        handler: function() {
            var form = this.up('form').getForm();
            if (form.isValid()) {
                form.submit({
                    success: function(form, action) {
                       Ext.Msg.alert('Success', action.result.msg);
                       Ext.data.StoreManager.lookup('documentQueueFiltersStore').load();
                    },
                    failure: function(form, action) {
                        Ext.Msg.alert('Failed', action.result.msg);
                    }
                });
            }
       }     
    },{
    	text: 'Reset',
    	icon:  '/img/icons/reset.png',
    	handler: function() {
    		var form = this.up('form').getForm();
    		var vals = form.getValues();
    		form.reset();
    		form.setValues({id: vals.id});
    	}
    }]
});

var availableDOMHeight = function() {
	if (typeof window.innerHeight !== 'undefined') {
    	return window.innerHeight - 160;
  	} 
	else if (typeof document.documentElement !== 'undefined' 
			&& typeof document.documentElement.clientHeight !== 'undefined') {
    			return document.documentElement.clientHeight - 160;
	} 
	else {
		return document.getElementsByTagName('body')[0].clientHeight - 160;
    }
};

Ext.onReady(function(){
	Ext.QuickTips.init();
	
	Ext.create('Ext.window.Window', {
	    height: availableDOMHeight(),
	    id: 'docQueueWindow',
	    width: '100%',
	    closable: false,
	    draggable: false,
	    resizable: false,
	    maximizable: true,
	    y: 150,
	    layout: 'border',
	    items:[{
	        region:'west',
	        xtype: 'panel',
	        margins: '5 0 0 5',
	        width: 300,
	        id: 'westContainer',
	        layout: 'accordion',
		    items: [{
		    	xtype: 'panel',
		    	layout: 'vbox',
		    	height: 'auto',
		        title: 'Document Actions',
		        collapsible: true,
		        collapsed: true,
		        items: [{
		        	title: 'File Document',
			        height: 300,
			        html: 'Panel content!',					
			        width: 300
		        },{
		        	title: 'Re-Queue Document',
			        html: 'Panel content!',
			        flex: 1,
			        width: 300		        	
		        },{
		        	title: 'Delete Document',
			        html: 'Panel content!',
			        flex: 1,
			        width: 300		        	
		        }]
		    },{
		        title: 'Queue Filters',
		        xtype: 'docqueuefilterformpanel',
		        url: '/admin/document_queue_filters/set_filters',
		        height: 150,
		        width: 300,
		        collapsible: true,
		        collapsed: false
		    },{
		        title: 'Queue Search',
		        html: 'Panel content!',
		        width: 300,
		        height: 200,
		        collapsible: true,
		        collapsed: true
		    },{
		        title: 'Add Customer',
		        html: 'Panel content!',
		        width: 300,
		        height: 600,
		        collapsible: true,
		        collapsed: true		    	
		    }]	        
	    },{
	        
	        region: 'center',
	        xtype: 'panel',
	        layout: {
	        	align: 'stretch',
	        	type: 'vbox'	
	        },	        
	        items: [{
		        xtype: 'atlasdocqueuegridpanel',
		        id: 'queuedDocGrid',
		        height: 185,
		        collapsible: true,
				
	        },{
		    	title: 'Document',
		        flex: 1,
		        layout: 'fit',
			    items : [{
			        xtype : 'component',
			        id: 'pdfFrame',
			        width: 1000,
			        height: 400,
			        autoEl : {
			            tag : 'iframe'
			        }
			    }]		                	
	        }]
	    }]
	}).show();
	Ext.data.StoreManager.lookup('queuedDocumentsStore').load();
});
