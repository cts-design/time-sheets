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
	url: '/admin/users/get_admin_list',
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

var dateSearchTb = new Ext.Toolbar({
	width: 250,
	items: [{
		text: 'Today',
		handler: function(){
			var dt = new Date();		
			var formated = dt.format('m/d/Y');
			Ext.getCmp('fromDate').setValue(formated);
			Ext.getCmp('toDate').setValue(formated);		
		}
	},{
		xtype: 'tbseparator'
	},{
		text: 'Yesterday',
		handler: function(){
			var dt = new Date();
			dt.setDate(dt.getDate() - 1);
			var formated = dt.format('m/d/Y');
			Ext.getCmp('fromDate').setValue(formated);
			Ext.getCmp('toDate').setValue(formated);	
		}
	},{
		xtype: 'tbseparator'
	},{
		text: 'Last Week',
		handler: function() {

		}
	},{
		xtype: 'tbseparator'
	},{
		text: 'Last Month'
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
			columnWidth: 0.275,
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
			columnWidth: 0.275,
			items: [{
				xtype: 'combo',
				fieldLabel: 'Search Type',
				triggerAction: 'all',
				store: searchTypeStore,
				mode: 'local',
				hiddenName: 'searchType',
				valueField: 'type',
				displayField: 'label',
				name: 'cusSearchType'
			},{
				xtype: 'textfield',
				fieldLabel: 'Search',
				name: 'cusSearch'
			}]
		},{
			layout: 'form',
			title: 'Filing Categories',
			frame: true,
			defaults: {
				width: 100
			},
			columnWidth: 0.20,
			items: [{
				xtype: 'combo',
				fieldLabel: 'Cat 1',
			},{
				xtype: 'combo',
				fieldLabel: 'Cat 2',
			},{
				xtype: 'combo',
				fieldLabel: 'Cat 3',
			}]
		},{
			layout: 'form',
			title: 'Additional Filters',
			frame: true,
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
				fieldLabel: 'Locations',
				store: locationStore,
				hiddenName: 'filed_location_id',
				valueField: 'id',
				displayField: 'name'
			}]
		}]
	}],
	fbar: [{
		text: 'Submit',
		handler: function(){
			var f = allDocsSearch.getForm();
			console.log(f.getValues());
			var vals = f.getValues();
			vals = Ext.util.JSON.encode(vals);
			allFiledDocsStore.setBaseParam('filters', vals);
			allFiledDocsStore.load({params: {limit: 25, page: 1}});
			//allFiledDocsStore.load({params: {filters: vals}})	;
				
		}
	},{
		text: 'Reset',
		handler: function() {
			var f = allDocsSearch.getForm();
			f.reset();
			allFiledDocsStore.setBaseParam('filters', '');
			allFiledDocsStore.load();			
		}
	},{
		text: 'Report'
	}]
})



Ext.onReady(function(){
	allDocsSearch.render('allDocsSearch');	
	allDocsGrid.render('allDocsGrid');	
	allFiledDocsStore.load({params: {limit:25}});
});