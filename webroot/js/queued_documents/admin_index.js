Ext.create('Ext.data.Store', {
    storeId:'queuedDocumentsStore',
    fields:['name', 'email', 'phone'],
    data:{'items':[
        { 
        	'id': 40, 
        	'filename': 'blabla.pdf', 
        	'queueCat': 'wia', 
        	'scannedLocation': 'citrus', 
        	'lockedBy': 'Daniel Noalan', 
        	'lockedStatus': 'locked',
        	'create': '12/12/12',
        	'modified': '12/12/12'
        }
    ]},
    proxy: {
        type: 'memory',
        reader: {
            type: 'json',
            root: 'items'
        }
    }
});




Ext.define('Atlas.grid.QueuedDocPanel', {
	extend: 'Ext.grid.Panel',
	alias: 'widget.atlasdocqueuegridpanel',
	title: 'Document Queue',
	store: Ext.data.StoreManager.lookup('queuedDocumentsStore'),
    columns: [
        { header: 'Id',  dataIndex: 'id' },
        { header: 'Filename', dataIndex: 'filename' },
        { header: 'Queue Cat', dataIndex: 'queueCat' },
        { header: 'Scanned Location', dataIndex: 'scannedLocation' },
        { header: 'Locked By', dataIndex: 'lockedBy' },
        { header: 'Locked Status', dataIndex: 'scannedLocation' },
        { header: 'Created', dataIndex: 'created' },
        { header: 'Modified', dataIndex: 'modified' }
        
    ]	
});



Ext.onReady(function(){
	Ext.QuickTips.init();
	
	Ext.create('Ext.window.Window', {
	    height: 625,
	    width: 1200,
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
			        items: [{
			        	xtype: 'button',
			        	text: 'Click Me',
			        	handler: function() {
			        		var docId = 40;
			        		Ext.getCmp('pdfFrame').el.dom.src = '/admin/queued_documents/view/'+docId+'/#toolbar=1&statusbar=0&navpanes=0&zoom=50'
			        	}
			        }],
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

