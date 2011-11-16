Ext.onReady(function(){
	Ext.QuickTips.init();
	Ext.define('FiledDocuments', {
		extend: 'Ext.data.Model',
		fields: [
			'id', 
			'User-lastname', 
			'Location-name', 
			'Admin-lastname', 
			'Cat1-name', 
			'Cat2-name', 
			'Cat3-name',
			'description', 
			'created', 
			'modified', 
			'LastActAdmin-lastname',
			'view'
		]
	});
	
	var allFiledDocsStore = Ext.create('Ext.data.Store', {
		pageSize: 25,
		model: 'FiledDocuments',
		storeId: 'allFiledDocsStore',
		remoteSort: true,
		proxy: {
			type: 'ajax',
			url: '/admin/filed_documents/view_all_docs',
			reader: {
				type: 'json',
				root: 'docs',
				idProperty: 'id',
				totalProperty: 'totalCount'
			},
			simpleSortMode: true,
			directionParam: 'direction'
		}
	});
	
	var allDocsGrid = Ext.create('Ext.grid.Panel', {
		store: allFiledDocsStore,
		renderTo: 'allDocsGrid',
		title: 'Documents',
		frame: true,
		height: 300,
		columns: [{
			text: 'Id',
			dataIndex: 'id',
			sortable: true,
			width: 30	
		}, {
			text: 'Customer',
			dataIndex: "User-lastname",
			sortable: true,	
			width: 140
		}, {
			text: 'Location',
			dataIndex: 'Location-name',
			sortable: true,	
			width: 50	
		}, {
			text: 'Filed by Admin',
			dataIndex: 'Admin-lastname',
			sortable: true,	
			width: 80
		}, {
			text: 'Main Cat',
			dataIndex: 'Cat1-name',
			sortable: true,
			width: 70
		}, {
			text: 'Second Cat',
			dataIndex: 'Cat2-name',
			sortable: true,
			width: 70
		}, {
			text: 'Third Cat',
			dataIndex: 'Cat3-name',
			sortable: true,
			width: 70
		}, {
			text: 'Description',
			dataIndex: 'description',
			sortable: true,
			width: 150
		}, {
			text: 'Last Act. Admin',
			dataIndex: 'LastActAdmin-lastname',
			sortable: true,	
			width: 85
		}, {
			text: 'Created',
			dataIndex: 'created',
			sortable: true,
			width: 110,
			hidden: true
		},{
			text: 'Modified',
			dataIndex: 'modified',
			sortable: true,
			width: 115
		}, {
			text: 'Actions',
			dataIndex: 'view',
			width: 45
		}],
		viewConfig: {
			
		},
		bbar: Ext.create('Ext.PagingToolbar', {
		  store: allFiledDocsStore,
		  displayInfo: true,
		  displayMsg: 'Displaying documents {0} - {1} of {2}',
		  emptyMsg: "No documents to display"
		})
	});
	
	Ext.define('SearchType', {
		extend: 'Ext.data.Model',
		fields: ['type', 'label']
	});
	
	var searchTypeStore = Ext.create('Ext.data.Store', {
		model: 'SearchType',
		data:[
			{'type' : 'firstname', 'label' : 'First Name'},
			{'type' : 'lastname', 'label' : 'Last Name'},
			{'type' : 'last4', 'label' : 'Last 4 SSN'},
			{'type' : 'fullssn', 'label' : 'Full SSN'}
		]
	});
	
	Ext.define('Admin', {
		extend: 'Ext.data.Model',
		fields: ['id', 'name']
	});
	
	var adminStore = Ext.create('Ext.data.Store', {
		model: 'Admin',
		storeId: 'adminStore',	
		proxy: {
			type: 'ajax',
			url: '/admin/filed_documents/get_all_admins',
			reader: {
				type: 'json',
				root: 'admins'
			}
		}	
	});
	
	Ext.define('Location', {
		extend: 'Ext.data.Model',
		fields: ['id', 'name']
	});
	
	var locationStore = Ext.create('Ext.data.Store', {
		model: 'Location',
		proxy: {
			type: 'ajax',
			url: '/admin/locations/get_location_list',
			reader: {
				type: 'json',
				root: 'locations'
			}
		},
		storeId: 'locationStore'
	});
	
	Ext.define('DocumentFilingCategory', {
		extend: 'Ext.data.Model',
		fields: ['id', 'name']
	});
	
	var docFilingCatStore = Ext.create('Ext.data.Store', {
		model: 'DocumentFilingCategory',
		storeId: 'docFilingCatStore',
		proxy: {
			type: 'ajax',
			url: '/admin/document_filing_categories/get_cats',
			reader: {
				type: 'json',
				root: 'cats'
			},
			extraParams: {
				parentId: 'parent'
			}		
		}
	});
	
	var docFilingChildCatStore = Ext.create('Ext.data.Store', {
		model: 'DocumentFilingCategory',
		storeId: 'docFilingChildCatStore',
		proxy: {
			type: 'ajax',
			url: '/admin/document_filing_categories/get_cats',
			reader: {
				type: 'json',
				root: 'cats'
			}
		}		
	});
	
	var docFilingGrandChildCatStore = Ext.create('Ext.data.Store', {
		model: 'DocumentFilingCategory',
		storeId: 'docFilingGrandChildCatStore',	
		proxy: {
			type: 'ajax',
			url: '/admin/document_filing_categories/get_cats',
			reader: {
				type: 'json',
				root: 'cats'
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
				dt.setDate(dt.getDate() - (parseInt(Ext.Date.format(dt, 'N')) + 6));
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
	
	Ext.define('Scope', {
		extend: 'Ext.data.Model',
		fields: ['scope', 'label']
	});
	
	var scopeStore = Ext.create('Ext.data.Store', {
		model: 'Scope',
		data: [
			{'scope' : 'containing', 'label' : 'Containing'},
			{'scope' : 'matching exactly', 'label' : 'Matching Exactly'}
		]
	});
	
	var allDocsSearch = Ext.create('Ext.form.Panel', {
		frame: true,
		collapsible: true,
		fieldDefaults:{
			labelWidth: 50,
		},
		title: 'Filters',
		id: 'allDocsSearchForm',
		renderTo: 'allDocsSearch',
		items: [{
			layout: 'column',
			xtype: 'container',
			items: [{
				layout: 'anchor',
				columnWidth: 0.3333,
				height: 125,
				frame: true,
	     		bodyStyle: 'padding: 0 10px',
				title: 'Dates',
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
				layout: 'anchor',
	      		bodyStyle: 'padding: 0 10px',
				title: 'Filing Categories',
				frame: true,
				height: 125,
				defaults: {
					width: 200
				},
				columnWidth: 0.3333,
				items: [{
					xtype: 'combo',
					fieldLabel: 'Cat 1',
					id: 'cat_1',
					store: docFilingCatStore,
					queryMode: 'remote',
					valueField: 'id',
					displayField: 'name',
					name: 'cat_1',
					listeners: {
						select: function(combo, records, index) {
							docFilingChildCatStore.load({params: {parentId: records[0].data.id }});
							docFilingChildCatStore.on('load', function(store, records, options) {
								var catIds = ['cat_2', 'cat_3'];
								if (store.data.length > 0) {
									enableCatDropDown(['cat_2']);
									resetCatDropDown(catIds);
									disableCatDropDown(['cat_3']);
								}	else {							
									resetCatDropDown(catIds);
									disableCatDropDown(catIds);
								}
							});
						}
					}
				}, {
					xtype: 'combo',
					fieldLabel: 'Cat 2',
					disabled: 'true',
					store: docFilingChildCatStore,
					id: 'cat_2',
					queryMode: 'local',
					valueField: 'id',
					displayField: 'name',
					name: 'cat_2',
					listeners: {
						select: function(combo, records, index) {
							docFilingGrandChildCatStore.load({ params: { parentId: records[0].data.id }});
							docFilingGrandChildCatStore.on('load', function(store, records, options) {
								var catId = ['cat_3'];
								if(store.data.length > 0) {							
									enableCatDropDown(catId);
									resetCatDropDown(catId);							
								}	else {
									disableCatDropDown(catId);
									resetCatDropDown(catId);								
								}
							});
						}
					}
				}, {
					xtype: 'combo',
					fieldLabel: 'Cat 3',
					disabled: 'true',
					queryMode: 'local',
					store: docFilingGrandChildCatStore,
					id: 'cat_3',
					name: 'cat_3',
					valueField: 'id',
					displayField: 'name'				
				}]
			}, {
				layout: 'anchor',
				title: 'Additional Filters',
	      		bodyStyle: 'padding: 0 10px',
				frame: true,
				height: 125,
	     		anchor: '90%',
				columnWidth: 0.3333,
				items: [{
					xtype: 'combo',
					name: 'admin_id',
					id: 'admin',
					queryMode: 'remote',
					fieldLabel: 'Admin',
					store: adminStore,
					valueField: 'id',
					displayField: 'name'
				}, {
					xtype: 'combo',
					id: 'location',
					name: 'filed_location_id',
					queryMode: 'remote',
					fieldLabel: 'Location',
					store: locationStore,
					valueField: 'id',
					displayField: 'name'
				}]
			}]
		}, {
			layout: 'column',
			xtype: 'container',
	    items: [{
	      layout: 'anchor',
	      columnWidth: 0.333,
	      id: 'cusSearch1',
	      title: 'Customer Search Filter 1',
	      bodyStyle: 'padding: 0 10px',
	      width: 290,
	      frame: true,
	      height: 115,		
	      items: [{
	        xtype: 'combo',
	        fieldLabel: 'Type',
	        store: searchTypeStore,
	        queryMode: 'local',
	        valueField: 'type',
	        displayField: 'label',
	        name: 'cusSearchType1',
	        width: 200,
	        listeners: {
	          select: function(combo, records, index) {
	            if (records[0].data.type === 'lastname' || records[0].data.type === 'firstname') {
	              Ext.getCmp('cusLastname').enable();
	              Ext.getCmp('cusLastname').show();
	              Ext.getCmp('cusLast4').disable();
	              Ext.getCmp('cusLast4').hide();
	              Ext.getCmp('cusFullSsn').disable();
	              Ext.getCmp('cusFullSsn').hide();
	            }
	            if (records[0].data.type === 'last4') {
	              Ext.getCmp('cusLast4').enable();
	              Ext.getCmp('cusLast4').show();
	              Ext.getCmp('cusLastname').disable();
	              Ext.getCmp('cusLastname').hide();							
	              Ext.getCmp('cusFullSsn').disable();
	              Ext.getCmp('cusFullSsn').hide();
	            }
	            if (records[0].data.type === 'fullssn') {
	              Ext.getCmp('cusFullSsn').enable();
	              Ext.getCmp('cusFullSsn').show();
	              Ext.getCmp('cusLastname').disable();
	              Ext.getCmp('cusLastname').hide();							
	              Ext.getCmp('cusLast4').disable();
	              Ext.getCmp('cusLast4').hide();
	            }
	          }
	        }
	      }, {
	        xtype: 'combo',
	        fieldLabel: 'Scope',
	        store: scopeStore,
	        queryMode: 'local',
	        name: 'cusScope1',
			valueField: 'scope',
			displayField: 'label',        
	        width: 200,
	        id: 'cusScope',
	        listeners: {
	          select: function(combo, records, index) {
	            if (records[0].data.scope === 'containing') {
	              Ext.getCmp('cusLast4').minLength = 1;
	              Ext.getCmp('cusFullSsn').minLength = 3;
	            }
	            if (records[0].data.scope === 'matching exactly') {
	              Ext.getCmp('cusLast4').minLength = 4;
	              Ext.getCmp('cusFullSsn').minLength = 9;
	            }
	          }
	        }
	      }, {
	        xtype: 'textfield',
	        fieldLabel: 'Search',
	        name: 'cusSearch1',
	        id: 'cusLast4',
	        maxLength: 4,
	        width: 200
	      }, {
	        xtype: 'textfield',
	        fieldLabel: 'Search',
	        hidden: true,
	        disabled: true,
	        name: 'cusSearch1',
	        id: 'cusFullSsn',
	        maxLength: 9,
	        width: 200
	      }, {
	        xtype: 'textfield',
	        fieldLabel: 'Search',
	        hidden: true,
	        disabled: true,
	        name: 'cusSearch1',
	        id: 'cusLastname',
	        width: 200
	      }]
	    }, {
	      layout: 'anchor',
	      title: 'Customer Search Filter 2',
	      id: 'cusSearch2',
	      bodyStyle: 'padding: 0 10px',
	      width: 290,
	      columnWidth: 0.333,
	      frame: true,
	      items: [{
	        xtype: 'combo',
	        fieldLabel: 'Type',
	        store: searchTypeStore,
	        queryMode: 'local',
	        valueField: 'type',
	        displayField: 'label',
	        name: 'cusSearchType2',
	        width: 200,
	        listeners: {
	          select: function(combo, records, index) {
	            if (records[0].data.type === 'lastname' || records[0].data.type === 'firstname') {
	              Ext.getCmp('cusLastname2').enable();
	              Ext.getCmp('cusLastname2').show();
	              Ext.getCmp('cusLast4_2').disable();
	              Ext.getCmp('cusLast4_2').hide();
	              Ext.getCmp('cusFullSsn_2').disable();
	              Ext.getCmp('cusFullSsn_2').hide();
	            }
	            if (records[0].data.type === 'last4') {
	              Ext.getCmp('cusLast4_2').enable();
	              Ext.getCmp('cusLast4_2').show();
	              Ext.getCmp('cusLastname2').disable();
	              Ext.getCmp('cusLastname2').hide();							
	              Ext.getCmp('cusFullSsn_2').disable();
	              Ext.getCmp('cusFullSsn_2').hide();
	            }
	            if (records[0].data.type === 'fullssn') {
	              Ext.getCmp('cusFullSsn_2').enable();
	              Ext.getCmp('cusFullSsn_2').show();
	              Ext.getCmp('cusLast4_2').disable();
	              Ext.getCmp('cusLast4_2').hide();
	              Ext.getCmp('cusLastname2').disable();
	              Ext.getCmp('cusLastname2').hide();							
	            }
	          }
	        }
	      }, {
	        xtype: 'combo',
	        fieldLabel: 'Scope',
	        queryMode: 'local',
			store: scopeStore,
			name: 'cusScope2',
			width: 200,
			id: 'cusScope1',
			valueField: 'scope',
			displayField: 'label',  		
	        listeners: {
	          select: function(combo, records, index) {
	            if (records[0].data.scope === 'containing') {
	              Ext.getCmp('cusLast4_2').minLength = 1;
	              Ext.getCmp('cusFullSsn_2').minLength = 3;
	            }
	            if (records[0].data.scope === 'matching exactly') {
	              Ext.getCmp('cusLast4_2').minLength = 4;
	              Ext.getCmp('cusFullSsn_2').minLength = 9;
	            }
	          }
	        }
				}, {
					xtype: 'textfield',
					fieldLabel: 'Search',
					name: 'cusSearch2',
					id: 'cusLast4_2',
					maxLength: 4,
					width: 200
				}, {
					xtype: 'textfield',
					fieldLabel: 'Search',
					name: 'cusSearch2',
					id: 'cusFullSsn_2',
					hidden: true,
					disabled: true,
					maxLength: 9,
					width: 200
	      }, {
					xtype: 'textfield',
					fieldLabel: 'Search',
					hidden: true,
					disabled: true,
					name: 'cusSearch2',
					id: 'cusLastname2',
					width: 200
				}]
			}]
		}],
		fbar: [{
			text: 'Search',
			id: 'docSearch',
			icon:  '/img/icons/find.png',
			handler: function(){
				var f = allDocsSearch.getForm();
				var vals = f.getValues();
				vals = Ext.JSON.encode(vals);
				allFiledDocsStore.proxy.extraParams = {filters : vals};
				allFiledDocsStore.loadPage(1, {limit: 25, start: 0});				
			}
		}, {
			text: 'Reset',
			icon:  '/img/icons/arrow_redo.png',
			handler: function() {
				var f = allDocsSearch.getForm();
				f.reset();
				allFiledDocsStore.proxy.extraParams = {filters : ''};
				allFiledDocsStore.loadPage(1, {limit: 25, start: 0});
				var catIds = ['cat_2', 'cat_3'];
				disableCatDropDown(catIds);		
			}
		}, {
			text: 'Report',
			icon:  '/img/icons/excel.png',
			handler: function(){
				var f = allDocsSearch.getForm();
				var vals = f.getValues();
				vals = Ext.JSON.encode(vals);
				allFiledDocsStore.proxy.extraParams = {filters : vals};	
				window.location = '/admin/filed_documents/report?filters='+ vals
			}
		}]
	});
	
	function resetCatDropDown(catIds){
		if(catIds){
			Ext.each(catIds, function(catId, index){
				Ext.getCmp(catId).reset();
			})
		}
	}
	
	function disableCatDropDown(catIds){
		if(catIds){
			Ext.each(catIds, function(catId, index){
				Ext.getCmp(catId).disable();
			})
		}
	}
	
	function enableCatDropDown(catIds){
		if(catIds){
			Ext.each(catIds, function(catId, index){
				Ext.getCmp(catId).enable();
			})
		}
	}
	
	allFiledDocsStore.load();
});