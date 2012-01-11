Ext.onReady(function(){
	Ext.create('Ext.window.Window', {
	    height: 625,
	    width: 1200,
	    closable: false,
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
			        html: 'Panel content!',
			        height: 300,
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
		    	title: 'Grid',
		        html: 'Panel content!',
		        height: 200,
		        collapsible: true,
				
	        },{
		    	title: 'Document',
		        flex: 1,
		        layout: 'fit',
			    items : [{
			        xtype : 'component',
			        width: 1000,
			        height: 400,
			        autoEl : {
			            tag : 'iframe',
			            src : '/admin/queued_documents/view/40/#toolbar=1&statusbar=0&navpanes=0&zoom=50'
			        }
			    }]		                	
	        }]
	    }]
	}).show();

});

