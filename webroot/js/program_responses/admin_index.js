Ext.onReady(function() {
	Ext.QuickTips.init();

    Ext.state.Manager.setProvider(new Ext.state.CookieProvider({
        expires: new Date(new Date().getTime()+(1000*60*60*24*365)) // 1 year
    }));

	var responseId = null, loaded = null, itemsPerPage = 10;
	Ext.define('ProgramResponse', {
		extend: 'Ext.data.Model',
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
		}, 'confirmation_id', 'actions', 'notes']
	});
	
	var programResponseProxy = Ext.create('Ext.data.proxy.Ajax', {
		url : '/admin/program_responses/index/' + progId,
		reader: {
			type: 'json',
			root : 'responses',
			totalProperty: 'totalCount'
		},
		extraParams : {
			status: 'incomplete',
			fromDate : '',
			toDate : '',
			id : '',
			searchType : '',
			search : ''
		},
		directionParam : 'direction',
		simpleSortMode: true
	});
	
	var store = Ext.create('Ext.data.Store', {
			model: 'ProgramResponse',
			proxy: programResponseProxy,
			pageSize: itemsPerPage,
			remoteSort: true,
			listeners: {
				load: function() {
					Ext.state.Manager.set('programResponseGrid'+progId, {currentPage: this.currentPage});
				}
			}
	});
	
	
	
	Ext.define('Atlas.grid.ProgramResponsePanel', {
		extend: 'Ext.grid.Panel',
		forceFit : true,
		height : 300,
		frame : true,
		store: store,
		columns: [{
			text : 'Id',
			dataIndex : 'id',
			width : 30,
			sortable : true
		}, {
			text : 'Customer',
			dataIndex : 'User-lastname',
			width : 150,
			sortable : true
		}, {
			text : 'Created',
			dataIndex : 'created',
			xtype : 'datecolumn',
			format : 'm/d/Y g:i a',
			sortable : true
		}, {
			text : 'Modified',
			dataIndex : 'modified',
			xtype : 'datecolumn',
			format : 'm/d/Y g:i a',
			sortable : true
		}, {
			text : 'Expires on',
			dataIndex : 'expires_on',
			xtype : 'datecolumn',
			format : 'm/d/Y g:i a',
			sortable : true
		}, {
			text : 'Actions',
			dataIndex : 'actions',
			width: 150
		}],
		viewConfig : {
			deferEmptyText: false,
			loadMask: true,
			emptyText : 'No responses at this time.'
		},
		selType: 'rowmodel',
		listeners: {
			select: function(sm, record, index, eOpts) {
				if(!record.data.text) {
					record.data.text = '';
				}
				responseId = record.data.id;
				editor.setValue(record.data.notes);
				Ext.getCmp('save').enable();
			}
		}
	});
		
	var openProgramResponsesGrid = Ext.create('Atlas.grid.ProgramResponsePanel', {
		title : 'Open',
		id: 'open',
		bbar: Ext.create('Ext.PagingToolbar', {
			store: store,
			displayInfo: true,
			displayMsg: 'Displaying responses {0} - {1} of {2}',
			emptyMsg: "No responses to display"
		})
	});
	
	var closedProgramResponsesGrid = Ext.create('Atlas.grid.ProgramResponsePanel', {
		title : 'Closed',
		id: 'closed',
		columns: [{
			text : 'Id',
			dataIndex : 'id',
			width : 30,
			sortable : true
		}, {
			text : 'Customer',
			dataIndex : 'User-lastname',
			width : 150,
			sortable : true
		}, {
			text : 'Confirmation Id',
			dataIndex : 'confirmation_id',
			sortable : false
		}, {
			text : 'Created',
			dataIndex : 'created',
			xtype : 'datecolumn',
			format : 'm/d/Y g:i a',
			sortable : true
		}, {
			text : 'Modified',
			dataIndex : 'modified',
			xtype : 'datecolumn',
			format : 'm/d/Y g:i a',
			sortable : true
		}, {
			text : 'Actions',
			dataIndex : 'actions',
			width: 150
		}],
		bbar: Ext.create('Ext.PagingToolbar', {
			store: store,
			displayInfo: true,
			displayMsg: 'Displaying responses {0} - {1} of {2}',
			emptyMsg: "No responses to display"
		})
	});
		
	var expiredProgramResponsesGrid = Ext.create('Atlas.grid.ProgramResponsePanel', {
		title : 'Expired',
		id: 'expired',
		bbar: Ext.create('Ext.PagingToolbar', {
			store: store,
			displayInfo: true,
			displayMsg: 'Displaying responses {0} - {1} of {2}',
			emptyMsg: "No responses to display"
		})
	});
	
	var pendingApprovalProgramResponsesGrid = Ext.create('Atlas.grid.ProgramResponsePanel', {
		title : 'Pending Approval',
		id: 'pendingApproval',
		bbar: Ext.create('Ext.PagingToolbar', {
			store: store,
			displayInfo: true,
			displayMsg: 'Displaying responses {0} - {1} of {2}',
			emptyMsg: "No responses to display"
		})
	});
		
	var notApprovedProgramResponsesGrid = Ext.create('Atlas.grid.ProgramResponsePanel', {
		title : 'Not Approved',
		id: 'notApproved',
		bbar: Ext.create('Ext.PagingToolbar', {
			store: store,
			displayInfo: true,
			displayMsg: 'Displaying responses {0} - {1} of {2}',
			emptyMsg: "No responses to display"
		})
	});
	
	var editor = Ext.create('Ext.form.HtmlEditor', {
		width : 800,
		height : 225,
		region : 'south',
		bodyStyle : {
			padding : '7px'
		},
		value : 'Please select a row in the grid above to see program response notes.'
	});
		
	var programResponseTabs = Ext.create('Ext.tab.Panel', {
		region : 'center',
		width : 800,
		activeTab : 0,
		frame : true,
		stateful: true,
		stateId: 'programResponseTabs' + progId,
		stateEvents: ['tabchange'],
		items : [
			openProgramResponsesGrid,
			closedProgramResponsesGrid,
			expiredProgramResponsesGrid,
			pendingApprovalProgramResponsesGrid,
			notApprovedProgramResponsesGrid
		],
		getState: function() {
           return { activeTab: this.getActiveTab().id };
		},
		applyState: function(state) {
			if(state.activeTab !== undefined) {
				this.setActiveTab(state.activeTab);
			}
		},
		listeners: {
			tabchange: function(tabPanel, newCard, oldCard, eOpts) {
				var save = Ext.getCmp('save');
				if(save) {
					save.disable();
				}
				editor.reset();
				switch (newCard.title) {
					case 'Open':
						programResponseProxy.extraParams.status= 'incomplete';
						break;
					case 'Closed':
						programResponseProxy.extraParams.status= 'complete';
						break;
					case 'Expired':
						programResponseProxy.extraParams.status= 'expired';
						break;
					case 'Pending Approval':
						programResponseProxy.extraParams.status= 'pending_approval';
						break;
					case 'Not Approved':
						programResponseProxy.extraParams.status= 'not_approved';
						break;
				}
				if(loaded) {
					newCard.getStore().loadPage(1, {start: 0, limit: 10});
				}
			},
			beforeadd: function(container, component, index) {
				if(this.items.length == 5) {
					return false;
				}
			},
			beforerender: function() {
				if(! approvalPermission) {
					this.remove(notApprovedProgramResponsesGrid);
					this.remove(pendingApprovalProgramResponsesGrid);
				}
			}
		}
	});
	
	var dateSearchTb = Ext.create('Ext.Toolbar', {
		width: 275,
		items: [{
			text: 'Today',
			handler: function() {
				var dt = new Date();
				Ext.getCmp('fromDate').setValue(Ext.Date.format(dt, 'm/d/Y'));
				Ext.getCmp('toDate').setValue(Ext.Date.format(dt, 'm/d/Y'));
			}
		}, {
			xtype: 'tbseparator'
		}, {
			text: 'Yesterday',
			handler: function() {
				var dt = new Date();
				dt.setDate(dt.getDate() - 1);
				Ext.getCmp('fromDate').setValue(Ext.Date.format(dt, 'm/d/Y'));
				Ext.getCmp('toDate').setValue(Ext.Date.format(dt, 'm/d/Y'));
			}
		}, {
			xtype: 'tbseparator'
		}, {
			text: 'Last Week',
			handler: function() {
				var dt = new Date();
				dt.setDate(dt.getDate() - (parseInt(Ext.Date.format(dt, 'N'), 10) + 6));
				Ext.getCmp('fromDate').setValue(Ext.Date.format(dt, 'm/d/Y'));
				dt.setDate(dt.getDate() + 6);
				Ext.getCmp('toDate').setValue(Ext.Date.format(dt, 'm/d/Y'));
			}
		}, {
			xtype: 'tbseparator'
		}, {
			text: 'Last Month',
			handler: function() {
				var now = new Date(),
					firstDayPrevMonth = new Date(now.getFullYear(), now.getMonth() - 1, 1),
					lastDayPrevMonth = Ext.Date.getLastDateOfMonth(firstDayPrevMonth);
				Ext.getCmp('fromDate').setValue(Ext.Date.format(firstDayPrevMonth, 'm/d/Y'));
				Ext.getCmp('toDate').setValue(Ext.Date.format(lastDayPrevMonth, 'm/d/Y'));
			}
		}]
	});
	
	Ext.define('SearchType', {
		extend: 'Ext.data.Model',
		fields : ['type', 'label']
	});
		
	var searchTypeStore = Ext.create('Ext.data.Store', {
		model: 'SearchType',
		data : [
			{type: 'firstname', label: 'First Name'},
			{type: 'lastname', label : 'Last Name'},
			{type: 'surname', label: 'Surname'},
			{type: 'last4', label: 'Last 4 SSN'},
			{type: 'fullssn', label: 'Full SSN'}
		]
	});
	
	var programResponseSearch = Ext.create('Ext.form.Panel', {
		frame : true,
		fieldDefaults:{
			labelWidth: 50
		},
		collapsible : true,
		height : 190,
		region : 'north',
		title : 'Filters',
		id : 'programResponseSearch',
		items : [{
			layout : 'column',
			xtype: 'container',
			bodyStyle: 'margin: 0 0 0 7px',
			items : [{
				layout : 'anchor',
				columnWidth : 0.333,
				height : 120,
				frame : true,
				bodyStyle : 'padding: 0 10px',
				title : 'Dates',
				items: [{
					xtype: 'datefield',
					id: 'fromDate',
					fieldLabel: 'From',
					name: 'fromDate',
					width: 200
				}, {
					xtype: 'datefield',
					fieldLabel: 'To',
					name: 'toDate',
					id: 'toDate',
					width: 200
				}, dateSearchTb]
			}, {
				layout : 'anchor',
				columnWidth : 0.333,
				height : 120,
				frame : true,
				bodyStyle : 'padding: 0 10px',
				title : 'Response',
				items : [{
					xtype : 'textfield',
					fieldLabel : 'Id',
					name: 'id',
					width : 200
				}]
			},{
				layout : 'anchor',
				columnWidth : 0.333,
				height : 120,
				frame : true,
				bodyStyle : 'padding: 0 10px',
				title : 'Customer',
				items : [{
					xtype : 'combo',
					fieldLabel : 'Search Type',
					name : 'searchType',
					store : searchTypeStore,
					queryMode: 'local',
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
		}],
		fbar : [{
			text : 'Search',
			id : 'docSearch',
			icon : '/img/icons/find.png',
			handler : function() {
				var f = programResponseSearch.getForm(), vals = f.getValues();
				Ext.iterate(vals, function (key, value){
					programResponseProxy.extraParams[key] = value;
				});
				programResponseTabs.getActiveTab().getStore().loadPage(1, {start: 0, limit: 10});
			}
		}, {
			text : 'Reset',
			icon : '/img/icons/arrow_redo.png',
			handler : function() {
				var f = programResponseSearch.getForm();
				f.reset();
        programResponseProxy.extraParams.id = undefined;
        programResponseProxy.extraParams.search = undefined;
        programResponseProxy.extraParams.toDate = undefined;
        programResponseProxy.extraParams.fromDate = undefined;
				programResponseTabs.getActiveTab().getStore().loadPage(1, {start: 0, limit: 10});
			}
		},{
			text: 'Report',
			icon:  '/img/icons/excel.png',
      xtype: 'splitbutton',
			handler: function(){
				var f = programResponseSearch.getForm();
				var vals = f.getValues();
        vals.status = programResponseProxy.extraParams.status;
        vals.progId = progId;
				vals = Ext.urlEncode(vals);
				window.location = '/admin/program_responses/report?'+ vals;
			},
      menu: new Ext.menu.Menu({
        items: [{
          icon:  '/img/icons/excel.png',
          text: 'Report With Form Answers?',
          handler: function() {
            var f = programResponseSearch.getForm();
            var vals = f.getValues();
            vals.status = programResponseProxy.extraParams.status;
            vals.includeData = true;
            vals.progId = progId;
            vals = Ext.urlEncode(vals);
            window.location = '/admin/program_responses/report?'+ vals;
          }
        }] 
       })
		}]
	});
	
	var responsesPanel = Ext.create('Ext.panel.Panel', {
		frame : true,
		renderTo: 'programResponseTabs',
		title: progName,
		width : 950,
		height : 800,
		layout : 'border',
		items : [programResponseTabs, editor, programResponseSearch],
		fbar : [{
			text : 'Save',
			id: 'save',
			disabled: true,
			icon : '/img/icons/save.png',
			handler : function() {
				Ext.Msg.wait('Please wait', 'Status');
				Ext.Ajax.request({
					url : '/admin/program_responses/edit',
					success : function(response, opts) {
						
						var obj = Ext.decode(response.responseText);
						if(obj.success) {
							programResponseTabs.getActiveTab().getStore().loadPage(1, {start: 0, limit: 10});
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

	Ext.get('programResponseTabs').on('click', function(e, t) {
		t = Ext.get(t);
		var url = '';
		if(t.hasCls('expire') || t.hasCls('reset') || t.hasCls('allow-new') ) {
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
								programResponseTabs.getActiveTab().getStore().load();
							}
						});
					} else {
						opts.failure(response, opts, obj);
					}
				},
				failure : function(response, opts, obj) {
					Ext.Msg.alert('Status', obj.message);
				}
			});
		}
	});
	var state = Ext.state.Manager.get('programResponseGrid'+progId),
		page = 1;
	if(state) {
		page = state.currentPage;
	}
	if(!loaded) {
		programResponseTabs.getActiveTab().getStore().loadPage(page, {start: 0, limit: 10});
		loaded = 1;
	}
});
