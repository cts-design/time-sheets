Ext.onReady(function(){
	Ext.QuickTips.init()
	
	Ext.ns('Atlas.grid');
	
	Atlas.grid.ProgramResponseGrid = Ext.extend(Ext.grid.GridPanel, {	
		loadMask: true,
		height: 300,	
		width: 375,
		frame: true,
		columns: [{
			id: 'id',
			header: 'Id',
			dataIndex: 'id',
			width: 30	
		},{
			header: 'Customer',
			dataIndex: 'customer',
			width: 150
		},{
			header: 'Created',
			dataIndex: 'created',
			xtype: 'datecolumn',
			format: 'm/d/Y g:i a'
		},{
			header: 'Modified',
			dataIndex: 'modified',
			xtype: 'datecolumn',
			format: 'm/d/Y g:i a'
		},{
			header: 'Status', 
			dataIndex: 'status',
			width: 70	
		},{
			header: 'Actions',
			dataIndex: 'actions'
		}],
		viewConfig: {
			emptyText: 'No responses at this time.',
			forceFit: true
		},		
		initComponent:function() {
			Atlas.grid.ProgramResponseGrid.superclass.initComponent.call(this);
		}
	});
	Ext.reg('gridpanel', Atlas.grid.ProgramResponseGrid);
	
	var programResponseProxy = new Ext.data.HttpProxy({
		url: '/admin/program_responses/index/'+ progId,
		method: 'GET'
	});
	
	var programResponseStore = new Ext.data.JsonStore({
		proxy: programResponseProxy,
		storeId: 'programResponseStore',
		root: 'responses',
		idProperty: 'id',
		fields: [
			'id', 
			'customer', 
			{name: 'created', type: 'date', dateFormat: 'Y-m-d H:i:s'}, 
			{name: 'modified', type: 'date', dateFormat: 'Y-m-d H:i:s'},
			'status', 
			'actions'
		]
	});
	
	var allProgramResponsesGrid = new Atlas.grid.ProgramResponseGrid({
		title: 'All',
		store: programResponseStore
	});
	
	var openProgramResponsesGrid = new Atlas.grid.ProgramResponseGrid({
		store: programResponseStore,
		title: 'Open'		
	});
	
	var closedProgramResponsesGrid = new Atlas.grid.ProgramResponseGrid({
		store: programResponseStore,
		title: 'Closed'
	});
	
	var unapprovedProgramResponsesGrid = new Atlas.grid.ProgramResponseGrid({
		store: programResponseStore,
		title: 'Un-Approved'		
	});		
	
	var programResponseTabs = new Ext.TabPanel({
	    renderTo: 'programResponseTabs',
	    width:700,
	    activeTab: 0,
	    frame:true,
	    defaults:{autoHeight: true},
	    items:[
	    	allProgramResponsesGrid, 
	    	openProgramResponsesGrid, 
	    	closedProgramResponsesGrid, 
	    	unapprovedProgramResponsesGrid
	    ],
	    listeners: {
	    	tabchange: function(TabPanel, Panel) {
	    		switch(Panel.title) {
	    			case 'All':
	    				programResponseStore.load();
	    				break;
	    			case 'Open':
	    				programResponseStore.load({params: {filter: 'open'}});
	    				break;
	    			case 'Closed':
	    				programResponseStore.load({params: {filter: 'closed'}});
	    				break;
	    			case 'Un-Approved':
	    				programResponseStore.load({params: {filter: 'unapproved'}});	
	    		}
	    	}
	    }
	});
});    