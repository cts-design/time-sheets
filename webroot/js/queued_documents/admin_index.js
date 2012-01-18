var docQueueWindowMask = 
	new Ext.LoadMask(Ext.getCmp('docQueueWindow'), {msg:"Loading Document..."});

Ext.define('QueuedDocument', {
	extend: 'Ext.data.Model',
	fields:[
		'id', 'queueCat', 'scannedLocation', 'queuedToCustomer',
		'lockedBy', 'lockedStatus', 'lastActivityAdmin', 'created', 'modified'
	],	
	lockDocument: function() {
		docQueueWindowMask.show();
		var docStore = Ext.data.StoreManager.lookup('queuedDocumentsStore');
		Ext.Ajax.request({
		    url: '/admin/queued_documents/lock_document',
		    params: {
		        docId: this.get('id')
		    },
		    success: function(response, opts){
		        var text = Ext.JSON.decode(response.responseText);
		        if(text.success) {	        	
		        	if(text.unlocked != undefined) {
			        	var unlockedDoc = docStore.getById(text.unlocked);
			        	if(unlockedDoc) {
				        	unlockedDoc.set('lockedStatus', 'Unlocked');
				        	unlockedDoc.set('lockedBy', '');
				        	unlockedDoc.commit();
			        	}
		        	}
		        	this.set('lockedStatus', 'Locked');
		        	this.set('lockedBy', text.admin);
		        	this.set('lastActivityAdmin', text.admin);
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
    pageSize: 10,
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
			dataIndex: 'queueCat',
			width: 75
		},{ 
			header: 'Scanned Location',
			dataIndex: 'scannedLocation' 
		},{
			header: 'Queued to Customer',
			dataIndex: 'queuedToCustomer',
			width: 150
		},{ 
			header: 'Locked Status', 
			dataIndex: 'lockedStatus',
			width: 80
		},{ 
			header: 'Locked By',
			dataIndex: 'lockedBy'
		},{
			header: 'Last Act. Admin',
			dataIndex: 'lastActivityAdmin'
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
	        id: 'west-region-container',
	        layout: 'accordion',
	        activeItem: '3',
		    items: [{
		    	xtype: 'panel',
		    	layout: 'vbox',
		    	height: 'auto',
		        title: 'Document Actions',
		        collapsible: true,
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
		        html: 'Panel content!',
		        height: 150,
		        width: 300,
		        collapsible: true,
		        collapsed: true
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
		        height: 200,
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
