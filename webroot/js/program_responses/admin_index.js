var responseId = null;

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
		{name: 'expires_on', type: 'date', dateFormat: 'Y-m-d H:i:s'},
		'conformation_id', 
		'actions',
		'notes'
	]
});

Ext.ns('Atlas.grid');

Atlas.grid.ProgramResponseGrid = Ext.extend(Ext.grid.GridPanel, {	
	loadMask: true,
	store: programResponseStore,
	height: 300,	
	width: 500,
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
		header: 'Expires on',
		dataIndex: 'expires_on',
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
	title: 'Open',
	sm: new Ext.grid.RowSelectionModel({
		singleSelect: true,
		listeners: {
			rowselect: function(sm, rowIdx, r) {
				if(!r.data.text) {
					r.data.text = ''
				}
				responseId = r.data.id;
				editor.setValue(r.data.notes);						
			}
		}
	})
});

var closedProgramResponsesGrid = new Atlas.grid.ProgramResponseGrid({
	title: 'Closed',
	height: 300,
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
	}],
	sm: new Ext.grid.RowSelectionModel({
		singleSelect: true,
		listeners: {
			rowselect: function(sm, rowIdx, r) {
				if(!r.data.text) {
					r.data.text = ''
				}
				responseId = r.data.id;
				editor.setValue(r.data.notes);						
			}
		}
	})
});

var expiredProgramResponsesGrid = new Atlas.grid.ProgramResponseGrid({
	title: 'Expired',
	sm: new Ext.grid.RowSelectionModel({
		singleSelect: true,
		listeners: {
			rowselect: function(sm, rowIdx, r) {
				if(!r.data.text) {
					r.data.text = ''
				}
				responseId = r.data.id;
				editor.setValue(r.data.notes);						
			}
		}
	})
});		

var unapprovedProgramResponsesGrid = new Atlas.grid.ProgramResponseGrid({
	title: 'Un-Approved',
	sm: new Ext.grid.RowSelectionModel({
		singleSelect: true,
		listeners: {
			rowselect: function(sm, rowIdx, r) {
				if(!r.data.text) {
					r.data.text = ''
				}
				responseId = r.data.id;
				editor.setValue(r.data.notes);						
			}
		}
	})
});
	
var programResponseTabs = new Ext.TabPanel({
	region: 'north',
    width: 800,
    activeTab: 0,
    frame: true,
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
    				editor.setValue('Please select a row in the grid above to see program response notes.');
    				break;
    			case 'Closed':
    				programResponseStore.setBaseParam('filter','closed');
    				editor.setValue('Please select a row in the grid above to see program response notes.');
    				break;
    			case 'Expired':
    				programResponseStore.setBaseParam('filter','expired');
    				editor.setValue('Please select a row in the grid above to see program response notes.');
    				break;	    				
    			case 'Un-Approved':
    				programResponseStore.setBaseParam('filter','unapproved');
    				editor.setValue('Please select a row in the grid above to see program response notes.');
    				break;
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

	
var editor = new Ext.form.HtmlEditor({
	width: 800,
	height: 300,
	region: 'center',
	bodyStyle: {
		background: '#ffffff',
		padding: '7px'
	},
	html: 'Please select a row in the grid above to see program response notes.'		
});	
	
	
var responsesPanel = new Ext.Panel({
	frame: true,
	width: 800,
	height: 600,
	layout: 'border',
	items: [
		programResponseTabs,
		editor		
	],
	fbar: [{	
		text: 'Save',
		handler: function() {
			Ext.Ajax.request({
				url: '/admin/program_responses/edit',
		        success: function(response, opts){			        	
		        	var obj = Ext.decode(response.responseText);
		        	if(obj.success) {
		        		programResponseStore.load();   	
						Ext.Msg.alert('Success', obj.message);					        		
		        	}
		        	else {
		        		opts.failure();
		        	}		            
		        },
		        failure: function(response, opts){
		        	var obj = Ext.decode(response.responseText);
		            Ext.Msg.alert('Error', obj.message);
		        },
			    params: { 
					'data[ProgramResponse][id]': responseId, 
					'data[ProgramResponse][notes]': editor.getValue()
			    }
			});			
		}
	}]
});

Ext.onReady(function(){
	Ext.QuickTips.init()
	responsesPanel.render('programResponseTabs');
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