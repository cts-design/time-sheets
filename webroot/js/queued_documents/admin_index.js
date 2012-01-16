var docId;

Ext.define('QueuedDocument', {
	extend: 'Ext.data.Model',
	fields:[
		'id', 'queueCat', 'scannedLocation', 'queuedToCustomer',
		'lockedBy', 'lockedStatus', 'lastActivityAdmin', 'created', 'modified'
	]
});

Ext.create('Ext.data.Store', {
    storeId:'queuedDocumentsStore',
	model: QueuedDocument,
    proxy: {
        type: 'ajax',
		url: '/admin/queued_documents',
        reader: {
            type: 'json',
            root: 'docs'
        }
    },
	autoLoad: true
});

var contextMenu = Ext.create('Ext.menu.Menu', {
	items: [{
		text: 'View Doc',
		icon:  '/img/icons/note_add.png',
    	handler: function() {	
    		Ext.getCmp('pdfFrame').el.dom.src = 
				'/admin/queued_documents/view/'+docId+'/#toolbar=1&statusbar=0&navpanes=0&zoom=50'
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
			loadMask: true,
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
});
