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
	baseParams:{page:1},
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

var dateSearchTb = new Ext.Toolbar({
	width: 250,
	items: [{
		text: 'Today'
	},{
		xtype: 'tbseparator'
	},{
		text: 'Yesterday'
	},{
		xtype: 'tbseparator'
	},{
		text: 'Last Week'
	},{
		xtype: 'tbseparator'
	},{
		text: 'Last Month'
	}]
});

var allDocsSearch = new Ext.form.FormPanel({
	frame: true,
	collapsible: true,
	labelWidth: 75,
	title: 'Filters',
	id: 'allDocsSearchForm',
	items:[{
		layout: 'column',
		items: [{
			layout: 'form',
			columnWidth: 0.35,
			frame: true,
			title: 'Dates',
			items: [{
				xtype: 'datefield',
				fieldLabel: 'From',
				name: 'from'
			},{
				xtype: 'datefield',
				fieldLabel: 'To',
				name: 'to'
			}, dateSearchTb]
		},{
			layout: 'form',
			title: 'Customer',
			frame: true,
			items: [{
				xtype: 'combo',
				fieldLabel: 'Search Type'
			},{
				xtype: 'textfield',
				fieldLabel: 'Search',
				name: 'search'
			}]
		},{
			layout: 'form',
			title: 'Additional Filters',
			frame: true,
			items: [{
				xtype: 'combo',
				fieldLabel: 'Admins'
			},{
				xtype: 'combo',
				fieldLabel: 'Locations'
			}]
		}]
	}]
})

Ext.onReady(function(){
	allDocsSearch.render('allDocsSearch');	
	allDocsGrid.render('allDocsGrid');	
	allFiledDocsStore.load({params: {limit:25}});
});