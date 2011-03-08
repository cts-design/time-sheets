var allFiledDocsProxy = new Ext.data.HttpProxy({
	method: 'GET',
	prettyUrls: true,
	url: '/admin/filed_documents/view_all_docs'
});

var allFiledDocsStore = new Ext.data.JsonStore({
	proxy: allFiledDocsProxy,
	storeId: 'allFiledDocsStore',
	totalProperty: 'totalCount',
	root: 'docs',
	idProperty: 'id',
	fields: ['id', 'customer', 'location', 'admin', 'cat_1', 'created', 'last_activity_admin']
});

var allDocsGrid = new Ext.grid.GridPanel({
	store: allFiledDocsStore,
	title: 'All Filed Documents',
	frame: true,
	height: 600,
	columns:[{
		header: 'Id',
		dataIndex: 'id'	
	},{
		header: 'Customer',
		dataIndex: 'customer'
	},{
		header: 'Location',
		dataIndex: 'location'
	},{
		header: 'Admin',
		dataIndex: 'admin'
	},{
		header: 'Cat',
		dataIndex: 'cat_1'
	},{
		header: 'Created',
		dataIndex: 'created'
	},{
		header: 'Last Activity Admin',
		dataIndex: 'last_activity_admin'
	}],
	 bbar: new Ext.PagingToolbar({
	            pageSize: 25,
	            store: allFiledDocsStore,
	            displayInfo: true,
	            displayMsg: 'Displaying topics {0} - {1} of {2}',
	            emptyMsg: "No topics to display"
	        })
	
});

Ext.onReady(function(){
	allDocsGrid.render('allDocsGrid');
	allFiledDocsStore.load({params:{start:0, limit:25}});
});