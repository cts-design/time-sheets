Ext.onReady(function(){
	Ext.QuickTips.init()
		
	var programResponseProxy = new Ext.data.HttpProxy({
		url: '/admin/program_responses/index/'+ progId,
		method: 'GET'
	});
	
	var programResponseStore = new Ext.data.JsonStore({
		proxy: programResponseProxy,
		storeId: 'programResponseStore',
		remoteSort: true,
		paramNames: {
			start: 'start',
			limit: 'limit',
			sort: 'sort',
			dir: 'direction'
		},
		totalProperty: 'totalCount',
		baseParams:{page:1, filter: ''},		
		root: 'responses',
		idProperty: 'id',
		fields: [
			'id', 
			'User-lastname', 
			{name: 'created', type: 'date', dateFormat: 'Y-m-d H:i:s'}, 
			{name: 'modified', type: 'date', dateFormat: 'Y-m-d H:i:s'},
			'conformation_id', 
			'actions'
		]
	});
	
	Ext.ns('Atlas.grid');
	
	Atlas.grid.ProgramResponseGrid = Ext.extend(Ext.grid.GridPanel, {	
		loadMask: true,
		store: programResponseStore,
		height: 300,	
		width: 375,
		frame: true,
		columns: [{
			id: 'id',
			header: 'Id',
			dataIndex: 'id',
			width: 30,
			sortable: true	
		},{
			header: 'Customer',
			dataIndex: 'User-lastname',
			width: 150,
			sortable: true
		},{
			header: 'Created',
			dataIndex: 'created',
			xtype: 'datecolumn',
			format: 'm/d/Y g:i a',
			sortable: true
		},{
			header: 'Modified',
			dataIndex: 'modified',
			xtype: 'datecolumn',
			format: 'm/d/Y g:i a',
			sortable: true
		},{
			header: 'Actions',
			dataIndex: 'actions'
		}],
		viewConfig: {
			emptyText: 'No responses at this time.',
			forceFit: true,
			scrollOffset: 0
		},
		bbar: {
			xtype: 'paging',
	        pageSize: 20,
	        store: programResponseStore,
	        displayInfo: true,
	        displayMsg: 'Displaying records {0} - {1} of {2}',
	        emptyMsg: "No documents to display",
	        listeners: {
			 	beforechange: function(paging , params) {
					var pagingData = paging.getPageData();
					CurrentPage = Math.ceil(params.start / paging.pageSize);
					this.store.setBaseParam('page',CurrentPage+1);
				}
			}
		},	
		initComponent:function() {
			Atlas.grid.ProgramResponseGrid.superclass.initComponent.call(this);
		}
	});
	
	Ext.reg('gridpanel', Atlas.grid.ProgramResponseGrid);
	
	var openProgramResponsesGrid = new Atlas.grid.ProgramResponseGrid({
		title: 'Open'		
	});
	
	var closedProgramResponsesGrid = new Atlas.grid.ProgramResponseGrid({
		title: 'Closed',
		columns: [{
			id: 'id',
			header: 'Id',
			dataIndex: 'id',
			width: 30,
			sortable: true	
		},{
			header: 'Customer',
			dataIndex: 'User-lastname',
			width: 150,
			sortable: true
		},{
			header: 'Conformation Id',
			dataIndex: 'conformation_id',
			sortable: false
		},{
			header: 'Created',
			dataIndex: 'created',
			xtype: 'datecolumn',
			format: 'm/d/Y g:i a',
			sortable: true			
		},{
			header: 'Modified',
			dataIndex: 'modified',
			xtype: 'datecolumn',
			format: 'm/d/Y g:i a',
			sortable: true
		},{
			header: 'Actions',
			dataIndex: 'actions'
		}]		
	});
	
	var expiredProgramResponsesGrid = new Atlas.grid.ProgramResponseGrid({
		title: 'Expired'		
	});		
	
	var unapprovedProgramResponsesGrid = new Atlas.grid.ProgramResponseGrid({
		title: 'Un-Approved'		
	});
		
	var programResponseTabs = new Ext.TabPanel({
		renderTo: 'programResponseTabs',
	    width: 700,
	    activeTab: 0,
	    frame: true,
	    defaults: {autoHeight: true},
	    items: [
	    	openProgramResponsesGrid, 
	    	closedProgramResponsesGrid,
	    	expiredProgramResponsesGrid
	    ],
	    listeners: {
	    	tabchange: function(TabPanel, Panel) {
	    		switch(Panel.title) {
	    			case 'Open':
	    				programResponseStore.setBaseParam('filter','open');
	    				break;
	    			case 'Closed':
	    				programResponseStore.setBaseParam('filter','closed');
	    				break;
	    			case 'Expired':
	    				programResponseStore.setBaseParam('filter','expired');
	    				break;	    				
	    			case 'Un-Approved':
	    				programResponseStore.setBaseParam('filter','unapproved');
	    		}
	    		programResponseStore.load();	
	    	},
	    	beforeadd: function(container, component, index) {
	    		if(this.items.length == 5) {
	    			return false;
	    		}
	    	},
	    	beforerender: function() {
	    		if(approvalPermission) {
	    			this.add(unapprovedProgramResponsesGrid);
	    		}
	    		
	    	}
	    }
	});
	Ext.get('programResponseTabs').on('click', function(e, t){
		t = Ext.get(t);
		var url = '';
		if(t.hasClass('expire')) {
			Ext.Msg.wait('Please wait', 'Status');
			e.preventDefault();
			Ext.Ajax.request({
				url: t.getAttribute('href'),
				success: function(response, opts) {
					var obj = Ext.decode(response.responseText);
					if(obj.success) {
						Ext.Msg.show({
							title: 'Status',
							msg: obj.message,
							buttons: Ext.Msg.OK,
							fn: function() {
								programResponseStore.reload();
							}						
						});
					}	
					else {
						opts.failure(response, opts, obj);
					}								
				},
				failure: function(response, opts, obj) {
					Ext.Msg.alert('Status', obj.message)
				}
			});
		}
	});
});    