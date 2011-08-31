var responseId = null, CurrentPage = null, Atlas = {};

var programResponseProxy = new Ext.data.HttpProxy({
	url : '/admin/program_responses/index/' + progId,
	method : 'GET'
});

var programResponseStore = new Ext.data.JsonStore({
	proxy : programResponseProxy,
	storeId : 'programResponseStore',
	remoteSort : true,
	paramNames : {
		start : 'start',
		limit : 'limit',
		sort : 'sort',
		dir : 'direction'
	},
	totalProperty : 'totalCount',
	baseParams : {
		page : 1,
		tab : '',
		dateFrom : '',
		dateTo : '',
		id : '',
		searchType : '',
		search : ''
	},
	root : 'responses',
	idProperty : 'id',
	fields : ['id', 'User-lastname', {
		name : 'created',
		type : 'date',
		dateFormat : 'Y-m-d H:i:s'
	}, {
		name : 'modified',
		type : 'date',
		dateFormat : 'Y-m-d H:i:s'
	}, {
		name : 'expires_on',
		type : 'date',
		dateFormat : 'Y-m-d H:i:s'
	}, 'conformation_id', 'actions', 'notes'],
	listeners : {
		beforeload : function (store) {
			
		}
	}
});

Ext.ns('Atlas.grid');

Atlas.grid.ProgramResponseGrid = Ext.extend(Ext.grid.GridPanel, {
	loadMask : true,
	store : programResponseStore,
	height : 300,
	width : 500,
	frame : true,
	columns : [{
		id : 'id',
		header : 'Id',
		dataIndex : 'id',
		width : 30,
		sortable : true
	}, {
		header : 'Customer',
		dataIndex : 'User-lastname',
		width : 150,
		sortable : true
	}, {
		header : 'Created',
		dataIndex : 'created',
		xtype : 'datecolumn',
		format : 'm/d/Y g:i a',
		sortable : true
	}, {
		header : 'Modified',
		dataIndex : 'modified',
		xtype : 'datecolumn',
		format : 'm/d/Y g:i a',
		sortable : true
	}, {
		header : 'Expires on',
		dataIndex : 'expires_on',
		xtype : 'datecolumn',
		format : 'm/d/Y g:i a',
		sortable : true
	}, {
		header : 'Actions',
		dataIndex : 'actions'
	}],
	viewConfig : {
		emptyText : 'No responses at this time.',
		forceFit : true,
		scrollOffset : 0
	},
	bbar : {
		xtype : 'paging',
		pageSize : 20,
		store : programResponseStore,
		displayInfo : true,
		displayMsg : 'Displaying records {0} - {1} of {2}',
		emptyMsg : "No documents to display",
		listeners : {
			beforechange : function (paging, params) {
				
				var pagingData = paging.getPageData();
				CurrentPage = Math.ceil(params.start / paging.pageSize);
				this.store.setBaseParam('page', CurrentPage + 1);
			}
		}
	},
	initComponent : function () {
		Atlas.grid.ProgramResponseGrid.superclass.initComponent.call(this);
	}
});

Ext.reg('gridpanel', Atlas.grid.ProgramResponseGrid);

var openProgramResponsesGrid = new Atlas.grid.ProgramResponseGrid({
	title : 'Open',
	sm : new Ext.grid.RowSelectionModel({
		singleSelect : true,
		listeners : {
			rowselect : function (sm, rowIdx, r) {
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
	title : 'Closed',
	height : 300,
	columns : [{
		id : 'id',
		header : 'Id',
		dataIndex : 'id',
		width : 30,
		sortable : true
	}, {
		header : 'Customer',
		dataIndex : 'User-lastname',
		width : 150,
		sortable : true
	}, {
		header : 'Conformation Id',
		dataIndex : 'conformation_id',
		sortable : false
	}, {
		header : 'Created',
		dataIndex : 'created',
		xtype : 'datecolumn',
		format : 'm/d/Y g:i a',
		sortable : true
	}, {
		header : 'Modified',
		dataIndex : 'modified',
		xtype : 'datecolumn',
		format : 'm/d/Y g:i a',
		sortable : true
	}, {
		header : 'Actions',
		dataIndex : 'actions'
	}],
	sm : new Ext.grid.RowSelectionModel({
		singleSelect : true,
		listeners : {
			rowselect : function(sm, rowIdx, r) {
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
	title : 'Expired',
	sm : new Ext.grid.RowSelectionModel({
		singleSelect : true,
		listeners : {
			rowselect : function(sm, rowIdx, r) {
				if(!r.data.text) {
					r.data.text = ''
				}
				responseId = r.data.id;
				editor.setValue(r.data.notes);
			}
		}
	})
});

var pendingApprovalProgramResponsesGrid = new Atlas.grid.ProgramResponseGrid({
	title : 'Pending Approval',
	sm : new Ext.grid.RowSelectionModel({
		singleSelect : true,
		listeners : {
			rowselect : function(sm, rowIdx, r) {
				if(!r.data.text) {
					r.data.text = ''
				}
				responseId = r.data.id;
				editor.setValue(r.data.notes);
			}
		}
	})
});

var editor = new Ext.form.HtmlEditor({
	width : 800,
	height : 300,
	region : 'south',
	bodyStyle : {
		background : '#ffffff',
		padding : '7px'
	},
	html : 'Please select a row in the grid above to see program response notes.'
});

var programResponseTabs = new Ext.TabPanel({
	region : 'center',
	width : 800,
	activeTab : 0,
	frame : true,
	items : [openProgramResponsesGrid, closedProgramResponsesGrid, expiredProgramResponsesGrid],
	listeners : {
		tabchange : function(TabPanel, Panel) {
			programResponseSearch.getForm().reset()
			editor.setValue('Please select a row in the grid above to see program response notes.');
			switch (Panel.title) {
			case 'Open':
				programResponseStore.setBaseParam('tab', 'open');			
					break;
				case 'Closed':
					programResponseStore.setBaseParam('tab', 'closed');
					break;
				case 'Expired':
					programResponseStore.setBaseParam('tab', 'expired');
					break;
				case 'Pending Approval':
					programResponseStore.setBaseParam('tab', 'pending_approval');
					break;
			}
			programResponseStore.load();
		},
		beforeadd : function(container, component, index) {
			if(this.items.length == 5) {
				return false;
			}
		},
		beforerender : function() {
			if(approvalPermission) {
				this.add(pendingApprovalProgramResponsesGrid);
			}

		}
	}
});

var dateSearchTb = new Ext.Toolbar({
	items : [{
		text : 'Today',
		handler : function() {
			var dt = new Date();
			Ext.getCmp('fromDate').setValue(dt.format('m/d/Y'));
			Ext.getCmp('toDate').setValue(dt.format('m/d/Y'));
		}
	}, {
		xtype : 'tbseparator'
	}, {
		text : 'Yesterday',
		handler : function() {
			var dt = new Date();
			dt.setDate(dt.getDate() - 1);
			Ext.getCmp('fromDate').setValue(dt.format('m/d/Y'));
			Ext.getCmp('toDate').setValue(dt.format('m/d/Y'));
		}
	}, {
		xtype : 'tbseparator'
	}, {
		text : 'Last Week',
		handler : function() {
			var dt = new Date();
			dt.setDate(dt.getDate() - (dt.format('N') + 6));
			Ext.getCmp('fromDate').setValue(dt.format('m/d/Y'));
			dt.setDate(dt.getDate() + 6);
			Ext.getCmp('toDate').setValue(dt.format('m/d/Y'));
		}
	}, {
		xtype : 'tbseparator'
	}, {
		text : 'Last Month',
		handler : function() {
			var now = new Date(), firstDayPrevMonth = new Date(now.getFullYear(), now.getMonth() - 1, 1), lastDayPrevMonth = firstDayPrevMonth.getLastDateOfMonth();
			Ext.getCmp('fromDate').setValue(firstDayPrevMonth.format('m/d/Y'));
			Ext.getCmp('toDate').setValue(lastDayPrevMonth.format('m/d/Y'));
		}
	}]
});


var searchTypeStore = new Ext.data.ArrayStore({
	fields : ['type', 'label'],
	id : 0,
	data : [
		['firstname', 'First Name'], 
		['lastname', 'Last Name'],
		['last4', 'Last 4 SSN'],
		['fullssn', 'Full SSN']
	]
});

var programResponseSearch = new Ext.form.FormPanel({
	frame : true,
	labelWidth : 50,
	collapsible : true,
	height : 190,
	region : 'north',
	title : 'Filters',
	id : 'programResponseSearch',
	items : [{
		layout : 'column',
		bodyStyle: 'margin: 0 0 0 7px',
		items : [{		
			layout : 'form',
			columnWidth : 0.31,
			height : 115,
			frame : true,
			bodyStyle : 'padding: 0 10px',
			title : 'Dates',
			items : [{
				xtype : 'datefield',
				id : 'fromDate',
				fieldLabel : 'From',
				name : 'fromDate',
				width : 200
			}, {
				xtype : 'datefield',
				fieldLabel : 'To',
				name : 'toDate',
				id : 'toDate',
				width : 200
			}, dateSearchTb]
		}, {
			items : [{
				layout : 'form',
				columnWidth : 0.31,
				height : 115,
				frame : true,
				bodyStyle : 'padding: 0 10px',
				title : 'Response',
				items : [{
					xtype : 'textfield',
					fieldLabel : 'Id',
					name: 'id',
					width : 200
				}]
			}]
		},{
			items : [{
				layout : 'form',
				columnWidth : 0.31,
				height : 115,
				frame : true,
				bodyStyle : 'padding: 0 10px',
				title : 'Customer',
				items : [{
					xtype : 'combo',
					fieldLabel : 'Search Type',
					name : 'searchType',
					store : searchTypeStore,
					hiddenName: 'searchType',
					mode: 'local',
					triggerAction: 'all',
					valueField: 'type',
        			displayField: 'label',
        			width: 250
				},
				{
					xtype : 'textfield',
					fieldLabel : 'Search',
					name : 'search',
					width : 250
				}]
			}]
		}]
	}],
	fbar : [{
		text : 'Search',
		id : 'docSearch',
		icon : '/img/icons/find.png',
		handler : function() {
			var f = programResponseSearch.getForm(), vals = f.getValues()
			Ext.iterate(vals, function (key, value){
				programResponseStore.setBaseParam(key, value);
			}) 
			programResponseStore.load();
		}
	}, {
		text : 'Reset',
		icon : '/img/icons/arrow_redo.png',
		handler : function() {
			var f = programResponseSearch.getForm();
			f.reset();
			var vals = f.getValues();
			Ext.iterate(vals, function (key, value){
				programResponseStore.setBaseParam(key, value);
			}) 			
			programResponseStore.load();
		}
	}]
});

var responsesPanel = new Ext.Panel({
	frame : true,
	width : 950,
	height : 800,
	layout : 'border',
	items : [programResponseTabs, editor, programResponseSearch],
	fbar : [{
		text : 'Save',
		handler : function() {
			Ext.Ajax.request({
				url : '/admin/program_responses/edit',
				success : function(response, opts) {
					var obj = Ext.decode(response.responseText);
					if(obj.success) {
						programResponseStore.load();
						Ext.Msg.alert('Success', obj.message);
					} else {
						opts.failure();
					}
				},
				failure : function(response, opts) {
					var obj = Ext.decode(response.responseText);
					Ext.Msg.alert('Error', obj.message);
				},
				params : {
					'data[ProgramResponse][id]' : responseId,
					'data[ProgramResponse][notes]' : editor.getValue()
				}
			});
		}
	}]
});

Ext.onReady(function() {
	Ext.QuickTips.init()

	responsesPanel.render('programResponseTabs');
	Ext.get('programResponseTabs').on('click', function(e, t) {
		t = Ext.get(t);
		var url = '';
		if(t.hasClass('expire')) {
			Ext.Msg.wait('Please wait', 'Status');
			e.preventDefault();
			Ext.Ajax.request({
				url : t.getAttribute('href'),
				success : function(response, opts) {
					var obj = Ext.decode(response.responseText);
					if(obj.success) {
						Ext.Msg.show({
							title : 'Status',
							msg : obj.message,
							buttons : Ext.Msg.OK,
							fn : function() {
								programResponseStore.reload();
							}
						});
					} else {
						opts.failure(response, opts, obj);
					}
				},
				failure : function(response, opts, obj) {
					Ext.Msg.alert('Status', obj.message)
				}
			});
		}
	});
});