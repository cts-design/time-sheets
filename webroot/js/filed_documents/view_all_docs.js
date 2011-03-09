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
	height: 620,
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
		width: 120
	},{
		header: 'Actions',
		dataIndex: 'view',
		width: 50
	}],
	viewConfig: {
		scrollOffset: 0
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

Ext.onReady(function(){
	allDocsGrid.render('allDocsGrid');
	allFiledDocsStore.load({params: {limit:25}});
});