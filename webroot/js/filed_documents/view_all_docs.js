var allFiledDocsProxy = new Ext.data.HttpProxy({
	method: 'GET',
	prettyUrls: true,
	url: '/admin/filed_documents/view_all_docs'
});

var allFiledDocsStore = new Ext.data.JsonStore({
	proxy: allFiledDocsProxy,
	storeId: 'allFiledDocsStore',
	remoteSort: true,
	paramNames: {
		start: 'start',
		limit: 'limit',
		sort: 'sort',
		dir: 'direction'
	},
	totalProperty: 'totalCount',
	baseParams:{page:1, filters: ''},
	root: 'docs',
	idProperty: 'id',
	fields: [
		'id', 
		'User-lastname', 
		'Location-name', 
		'Admin-lastname', 
		'Cat1-name', 
		'Cat2-name', 
		'Cat3-name', 
		'created', 
		'LastActAdmin-lastname',
		'view'
	]
});

var allDocsGrid = new Ext.grid.GridPanel({
	store: allFiledDocsStore,
	title: 'Documents',
	frame: true,
	height: 300,
	columns:[{
		header: 'Id',
		dataIndex: 'id',
		sortable: true,
		width: 30	
	},{
		header: 'Customer',
		dataIndex: "User-lastname",
		sortable: true,	
		width: 130
	},{
		header: 'Location',
		dataIndex: 'Location-name',
		sortable: true,	
		width: 80	
	},{
		header: 'Filed by Admin',
		dataIndex: 'Admin-lastname',
		sortable: true,	
		width: 105
	},{
		header: 'Main Cat',
		dataIndex: 'Cat1-name',
		sortable: true
	},{
		header: 'Second Cat',
		dataIndex: 'Cat2-name',
		sortable: true
	},{
		header: 'Third Cat',
		dataIndex: 'Cat3-name',
		sortable: true
	},{
		header: 'Last Activity Admin',
		dataIndex: 'LastActAdmin-lastname',
		sortable: true,	
		width: 105
	},{
		header: 'Created',
		dataIndex: 'created',
		sortable: true,
		width: 115
	},{
		header: 'Actions',
		dataIndex: 'view',
		width: 50
	}],
	viewConfig: {
		
	},
	 bbar: new Ext.PagingToolbar({
	            pageSize: 25,
	            store: allFiledDocsStore,
	            displayInfo: true,
	            displayMsg: 'Displaying documents {0} - {1} of {2}',
	            emptyMsg: "No documents to display",
	            listeners: {
				 	beforechange: function(paging , params) {
						var pagingData = paging.getPageData();
						CurrentPage = Math.ceil(params.start / paging.pageSize);
						this.store.setBaseParam('page',CurrentPage+1);
					}
				}
	        })
});

var searchTypeStore = new Ext.data.ArrayStore({
	fields: ['type', 'label'],
	id: 0,
	data:[[
		'lastname', 'Last Name'
	],[
		'last4', 'Last 4 SSN'
	]]
});

var adminProxy = new Ext.data.HttpProxy({
	url: '/admin/filed_documents/get_all_admins',
	method: 'GET'
});

var adminStore = new Ext.data.JsonStore({
	proxy: adminProxy,
	storeId: 'adminStore',
	root: 'admins',
	fields: ['id', 'name']
});

var locationProxy = new Ext.data.HttpProxy({
	url: '/admin/locations/get_location_list',
	method: 'GET'
});

var locationStore = new Ext.data.JsonStore({
	method: 'GET',
	proxy: locationProxy,
	storeId: 'locationStore',
	root: 'locations',
	fields: ['id', 'name']
});

var docFilingCatProxy = new Ext.data.HttpProxy({
	url: '/admin/document_filing_categories/get_cats',
	method: 'GET'
});

var docFilingCatStore = new Ext.data.JsonStore({
	method: 'GET',
	proxy: docFilingCatProxy,
	storeId: 'docFilingCatStore',
	root: 'cats',
	baseParams: {
		parentId: 'parent'
	},
	fields: ['id', 'name']	
});

var docFilingChildCatStore = new Ext.data.JsonStore({
	method: 'GET',
	proxy: docFilingCatProxy,
	storeId: 'docFilingChildCatStore',
	root: 'cats',	
	fields: ['id', 'name']	
});

var docFilingGrandChildCatStore = new Ext.data.JsonStore({
	method: 'GET',
	proxy: docFilingCatProxy,
	storeId: 'docFilingGrandChildCatStore',
	root: 'cats',	
	fields: ['id', 'name']	
});

var dateSearchTb = new Ext.Toolbar({
	items: [{
		text: 'Today',
		handler: function(){
			var dt = new Date();		
			Ext.getCmp('fromDate').setValue(dt.format('m/d/Y'));
			Ext.getCmp('toDate').setValue(dt.format('m/d/Y'));		
		}
	},{
		xtype: 'tbseparator'
	},{
		text: 'Yesterday',
		handler: function(){
			var dt = new Date();
			dt.setDate(dt.getDate() - 1);
			Ext.getCmp('fromDate').setValue(dt.format('m/d/Y'));
			Ext.getCmp('toDate').setValue(dt.format('m/d/Y'));	
		}
	},{
		xtype: 'tbseparator'
	},{
		text: 'Last Week',
		handler: function() {
			var dt = new Date();
			dt.setDate(dt.getDate() - (dt.format('N') + 6));
			Ext.getCmp('fromDate').setValue(dt.format('m/d/Y'));
			dt.setDate(dt.getDate() + 6);		
			Ext.getCmp('toDate').setValue(dt.format('m/d/Y'));
		}
	},{
		xtype: 'tbseparator'
	},{
		text: 'Last Month',
		handler: function(){
		var now = new Date();
			var firstDayPrevMonth = new Date(now.getFullYear(), now.getMonth() - 1, 1);
			var lastDayPrevMonth = firstDayPrevMonth.getLastDateOfMonth();
			Ext.getCmp('fromDate').setValue(firstDayPrevMonth.format('m/d/Y'));
			Ext.getCmp('toDate').setValue(lastDayPrevMonth.format('m/d/Y'));		
		}
	}]
});

var allDocsSearch = new Ext.form.FormPanel({
	frame: true,
	collapsible: true,
	labelWidth: 50,
	title: 'Filters',
	id: 'allDocsSearchForm',
	items:[{
		layout: 'column',
		items: [{
			layout: 'form',
			columnWidth: 0.29,
			height: 115,
			frame: true,
			title: 'Dates',
			items: [{
				xtype: 'datefield',
				id: 'fromDate',
				fieldLabel: 'From',
				name: 'fromDate'
			},{
				xtype: 'datefield',
				fieldLabel: 'To',
				name: 'toDate',
				id: 'toDate'
			}, dateSearchTb]
		},{
			layout: 'form',
			title: 'Customer',
			frame: true,
			columnWidth: 0.25,
			height: 115,		
			items: [{
				xtype: 'combo',
				fieldLabel: 'Search Type',
				triggerAction: 'all',
				store: searchTypeStore,
				mode: 'local',
				hiddenName: 'searchType',
				valueField: 'type',
				displayField: 'label',
				name: 'cusSearchType',
				listeners: {
					select: function(combo, record, index) {
						if(record.id == 'lastname'){
							Ext.getCmp('cusLastname').enable();
							Ext.getCmp('cusLastname').show();
							Ext.getCmp('cusLast4').disable();
							Ext.getCmp('cusLast4').hide();
						}
						if(record.id ==  'last4'){
							Ext.getCmp('cusLast4').enable();
							Ext.getCmp('cusLast4').show();
							Ext.getCmp('cusLastname').disable();
							Ext.getCmp('cusLastname').hide();							
						}
					}
				}
			},{
				xtype: 'textfield',
				fieldLabel: 'Search',
				name: 'cusSearch',
				id: 'cusLast4',
				maxLength: 4,
			},{
				xtype: 'textfield',
				fieldLabel: 'Search',
				hidden: true,
				disabled: true,
				name: 'cusSearch',
				id: 'cusLastname'				
			}]
		},{
			layout: 'form',
			title: 'Filing Categories',
			frame: true,
			height: 115,
			defaults: {
				width: 100
			},
			columnWidth: 0.21,
			items: [{
				xtype: 'combo',
				fieldLabel: 'Cat 1',
				id: 'cat_1',
				store: docFilingCatStore,
				triggerAction: 'all',
				mode: 'remote',
				hiddenName: 'cat_1',
				valueField: 'id',
				displayField: 'name',
				name: 'cat_1',
				listeners: {
					select: function(combo, record, index){
						docFilingChildCatStore.load({params: {parentId: record.id }});
						docFilingChildCatStore.on('load', function(store, records, options){
							var catIds = ['cat_2', 'cat_3'];
							if(store.data.length > 0) {
								enableCatDropDown(['cat_2']);
								resetCatDropDown(catIds);
								disableCatDropDown(['cat_3']);
							}
							else {							
								resetCatDropDown(catIds);
								disableCatDropDown(catIds);
							}
						})
					}
				}
			},{
				xtype: 'combo',
				fieldLabel: 'Cat 2',
				disabled: 'true',
				store: docFilingChildCatStore,
				triggerAction: 'all',
				id: 'cat_2',
				mode: 'local',
				hiddenName: 'cat_2',
				valueField: 'id',
				displayField: 'name',
				name: 'cat_2',
				listeners: {
					select: function(combo, record, index){
						docFilingGrandChildCatStore.load({params: {parentId: record.id }});
						docFilingGrandChildCatStore.on('load', function(store, records, options){
							var catId = ['cat_3'];
							if(store.data.length > 0) {							
								enableCatDropDown(catId);
								resetCatDropDown(catId);							
							}
							else {
								disableCatDropDown(catId);
								resetCatDropDown(catId);								
							}
						})
					}
				}
			},{
				xtype: 'combo',
				fieldLabel: 'Cat 3',
				disabled: 'true',
				triggerAction: 'all',
				mode: 'local',
				store: docFilingGrandChildCatStore,
				id: 'cat_3',
				name: 'cat_3',
				hiddenName: 'cat_3',
				valueField: 'id',
				displayField: 'name'				
			}]
		},{
			layout: 'form',
			title: 'Additional Filters',
			frame: true,
			height: 115,
			columnWidth: 0.25,
			items: [{
				xtype: 'combo',
				triggerAction: 'all',
				mode: 'remote',
				fieldLabel: 'Admin',
				hiddenName: 'admin_id',
				store: adminStore,
				valueField: 'id',
				displayField: 'name'
			},{
				xtype: 'combo',
				id: 'location',
				triggerAction: 'all',
				mode: 'remote',
				fieldLabel: 'Location',
				store: locationStore,
				hiddenName: 'filed_location_id',
				valueField: 'id',
				displayField: 'name'
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
			vals = Ext.util.JSON.encode(vals);
			allFiledDocsStore.setBaseParam('filters', vals);
			allFiledDocsStore.load({params: {limit: 25, page: 1}});				
		}
	},{
		text: 'Reset',
		icon:  '/img/icons/arrow_redo.png',
		handler: function() {
			var f = allDocsSearch.getForm();
			f.reset();
			allFiledDocsStore.setBaseParam('filters', '');
			allFiledDocsStore.load();
			var catIds = ['cat_2', 'cat_3'];
			disableCatDropDown(catIds);		
		}
	},{
		text: 'Report',
		icon:  '/img/icons/excel.png',
		handler: function(){
			var f = allDocsSearch.getForm();
			var vals = f.getValues();
			vals = Ext.util.JSON.encode(vals);
			allFiledDocsStore.setBaseParam('filters', vals);			
			window.location = '/admin/filed_documents/report?filters='+ vals
		}
	}]
})

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

Ext.onReady(function(){
	Ext.QuickTips.init();
	allDocsSearch.render('allDocsSearch');	
	allDocsGrid.render('allDocsGrid');	
	allFiledDocsStore.load({params: {limit:25}});
});
