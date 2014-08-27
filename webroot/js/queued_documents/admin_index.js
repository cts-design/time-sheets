Ext.apply(Ext.form.field.VTypes, {
	daterange: function(val, field) {
		var date = field.parseDate(val);

		if (!date) {
			return false;
		}
		if (field.startDateField && (!this.dateRangeMax || (date.getTime() != this.dateRangeMax.getTime()))) {
			var start = field.up('form').down('#' + field.startDateField);
			start.setMaxValue(date);
			start.validate();
			this.dateRangeMax = date;
		}
		else if (field.endDateField && (!this.dateRangeMin || (date.getTime() != this.dateRangeMin.getTime()))) {
			var end = field.up('form').down('#' + field.endDateField);
			end.setMinValue(date);
			end.validate();
			this.dateRangeMin = date;
		}
		/*
		* Always return true since we're only using this vtype to set the
		* min/max allowed values (these are tested for after the vtype test)
		*/
		return true;
	},

	daterangeText: 'Start date must be less than end date'
});

var closeWindow = new Ext.util.DelayedTask(function(){
	Ext.getCmp('timeOutConfirm').close();
});

function setDocTimeOut() {
	documentTimeout.delay(docTimeOutDelay);
}

function removeTimeoutListeners() {
	Ext.EventManager.removeListener(Ext.getBody(), 'mousemove', setDocTimeOut);
	Ext.EventManager.removeListener(Ext.getBody(), 'keypress', setDocTimeOut);
}

var documentTimeout = new Ext.util.DelayedTask(function(){
	
	Ext.getCmp('timeOutConfirm').show({
		title: 'Document Time Out',
		msg: 'Do you wish to keep this document open?<br />Clicking no will return you to the dashboard.',
		closable: false,
		buttons: Ext.Msg.YESNO,
		icon: Ext.Msg.QUESTION,
		fn: function(clicked) {
			if(clicked === 'yes') {
				closeWindow.cancel();
				documentTimeout.delay(docTimeOutDelay);
			}
			else {
				window.location = '/admin/users/dashboard';
			}
		}
	});
});

Ext.create('Ext.window.MessageBox',{
	id: 'timeOutConfirm',
	listeners: {
		beforeclose: function() {
			window.location = '/admin/users/dashboard';
		},
		show: function() {
			closeWindow.delay(30000);
		}
	}
});

Ext.define('SelfScanCategory', {
	extend: 'Ext.data.Model',
	fields: [
		'id', 'name', 'cat_1', 'cat_2','cat_3'
	]
});

Ext.create('Ext.data.Store', {
	storeId:'selfScanCategoriesStore',
	model: SelfScanCategory,
	proxy: {
		type: 'ajax',
		url: '/admin/self_scan_categories/get_cats',
		reader: {
			type: 'json',
			root: 'cats'
		},
		limitParam: undefined,
		pageParam: undefined,
		startParam: undefined
	},
	autoLoad: true
});

Ext.define('BarCodeDefinition', {
	extend: 'Ext.data.Model',
	fields: [
		'id', 'cat_1', 'cat_2','cat_3'
	]
});

Ext.create('Ext.data.Store', {
	storeId:'barCodeDefinitionsStore',
	model: SelfScanCategory,
	proxy: {
		type: 'ajax',
		url: '/admin/bar_code_definitions/get_definitions',
		reader: {
			type: 'json',
			root: 'definitions'
		},
		limitParam: undefined,
		pageParam: undefined,
		startParam: undefined
	},
	autoLoad: true
});

Ext.define('QueuedDocument', {
	extend: 'Ext.data.Model',
	fields:[
		'id', 'queue_cat', 'scanned_location', 'secure',
		'queued_to_customer', 'queued_to_customer_id', 'queued_to_customer_ssn',
		'queued_to_customer_first', 'queued_to_customer_last',
		'locked_by', 'locked_by_id', 'locked_status', 'requeued',
		'last_activity_admin', 'bar_code_definition_id', 'self_scan_cat_id',
		{name: 'created', type: 'date', dateFormat: 'Y-m-d H:i:s'},
		{name: 'modified', type: 'date', dateFormat: 'Y-m-d H:i:s'}
	],
	lockDocument: function() {
		Ext.EventManager.on(Ext.getBody(), 'mousemove', setDocTimeOut);
		Ext.EventManager.on(Ext.getBody(), 'keypress', setDocTimeOut);
		setDocTimeOut();
		var docQueueMask =
			new Ext.LoadMask(Ext.getBody(), {msg:"Loading Document..."});
		docQueueMask.show();
		if(this.data.locked_status == "Locked" && this.data.locked_by_id == adminId) {
			if(this.data.self_scan_cat_id || this.data.bar_code_definition_id) {
				autoPopulateFilingCats(this);
			}
			if(this.data.queued_to_customer_id) {
				autoPopulateCustomerInfo(this);
			}
			Ext.getCmp('secureDocMessage').show();
			if(1+1 == 2){
				
			}
			else {
				Ext.getCmp('secureDocMessage').hide();
			}

			embedPDF(this.data);

			Ext.getCmp('fileDocumentForm').getComponent('docId').setValue(this.data.id);
			Ext.getCmp('reassignQueueForm').getComponent('docId').setValue(this.data.id);
			Ext.getCmp('deleteDocumentForm').getComponent('docId').setValue(this.data.id);

			docQueueMask.hide();
		}
		else {
			var docStore = Ext.data.StoreManager.lookup('queuedDocumentsStore');
			Ext.Ajax.request({
				url: '/admin/queued_documents/lock_document',
				params: {
					doc_id: this.get('id')
				},
				success: function(response, opts){
					var text = Ext.JSON.decode(response.responseText);
					if(text.success) {
						if(text.unlocked !== undefined) {
							var unlockedDoc = docStore.getById(text.unlocked);
							if(unlockedDoc) {
								unlockedDoc.set('locked_status', 'Unlocked');
								unlockedDoc.set('locked_by', '');
								unlockedDoc.commit();
							}
						}
						this.set('locked_status', 'Locked');
						this.set('locked_by', text.admin);
						this.set('locked_by_id', text.LockedBy.id);
						this.set('last_activity_admin', text.admin);
						this.commit();
						autoPopulateFilingCats(this);
						if(this.data.queued_to_customer_id) {
							autoPopulateCustomerInfo(this);
						}

						embedPDF(text.QueuedDocument);
						Ext.getCmp('fileDocumentForm').getComponent('docId').setValue(text.QueuedDocument.id);
						Ext.getCmp('reassignQueueForm').getComponent('docId').setValue(text.QueuedDocument.id);
						Ext.getCmp('deleteDocumentForm').getComponent('docId').setValue(text.QueuedDocument.id);
						docQueueMask.hide();
					}
					else {
						opts.failure(response);
					}
				},
				failure: function(response, opts) {
					documentTimeout.cancel();
					removeTimeoutListeners();
					var message = 'Unable to lock document for viewing.<br />' +
						'Make sure it is not locked by someone else.<br />' +
						'Please use the refresh button in the grid toolbar<br />' +
						'to update the grid view if nessesary.';
					Ext.MessageBox.alert('Failure', message);
					docQueueMask.hide();
					docStore.load();
					Ext.getCmp('queuedDocumentsPdf').el.dom.innerHTML = '<p>No Document Loaded.</p>';
				},
				scope: this
			});
		}
	}
});

function autoPopulateCustomerInfo(doc) {
	var cusStore = Ext.data.StoreManager.lookup('customerFirstname');
	
	cusStore.add({
		id: doc.data.queued_to_customer_id,
		firstname: doc.data.queued_to_customer_first,
		lastname: doc.data.queued_to_customer_last
	});
	
	Ext.getCmp('fileDocLastname').setValue(doc.data.queued_to_customer_last);
	Ext.getCmp('fileDocFirstname').setValue(doc.data.queued_to_customer_id);
	Ext.getCmp('fileDocCusDetails').setValue(
		doc.data.queued_to_customer_ssn + ', ' + doc.data.queued_to_customer_last);
  Ext.getCmp('fileDocumentForm').getComponent('userId').setValue(doc.data.queued_to_customer_id);
}

function autoPopulateFilingCats(doc) {
	var cat, cat2Store, cat3Store;

	if(doc.data.self_scan_cat_id || doc.data.bar_code_definition_id) {
		if(doc.data.self_scan_cat_id) {
			cat = Ext.data.StoreManager.lookup('selfScanCategoriesStore').getById(doc.data.self_scan_cat_id);
		}
		else {
			cat = Ext.data.StoreManager.lookup('barCodeDefinitionsStore').getById(doc.data.bar_code_definition_id);
		}

		if(cat.data.cat_1 !== null) {
			Ext.getCmp('mainFilingCats').select(cat.data.cat_1);
			cat2Store = Ext.data.StoreManager.lookup('documentFilingCats2');
			cat2Store.clearFilter();
			cat2Store.filter(getCatFilter(cat.data.cat_1));
		}
		if(cat.data.cat_2 !== null) {
			Ext.getCmp('secondFilingCats').select(cat.data.cat_2);
			cat3Store = Ext.data.StoreManager.lookup('documentFilingCats3');
			cat3Store.clearFilter();
			cat3Store.filter(getCatFilter(cat.data.cat_2));
		}
		if(cat.data.cat_3 !== null) {
			Ext.getCmp('thirdFilingCats').select(cat.data.cat_3);
		}
	}
}

Ext.create('Ext.data.Store', {
	storeId:'queuedDocumentsStore',
	pageSize: 5,
	model: QueuedDocument,
	remoteSort: true,
	proxy: {
		type: 'ajax',
		simpleSortMode: true,
		directionParam: 'direction',
		url: '/admin/queued_documents',
		reader: {
			type: 'json',
			root: 'docs',
			totalProperty: 'totalCount'
		}
	},
	listeners: {
		beforeload: function (store, options) {
			if(store.sorters.items[0]){
				var oldSortParam = store.sorters.items[0].property;
				for(var i=0; i < gridColumns.length; i++) {
					var currentCol = gridColumns[i];
					if(currentCol.sortable && currentCol.customSort && currentCol.dataIndex == oldSortParam) {
						store.sorters.items[0].property = currentCol.customSort;
						break;
					}
				}
			}
		},
		load: function(store, records, successful, operation, eOpts) {
			documentTimeout.cancel();
			removeTimeoutListeners();
			Ext.getCmp('queuedDocumentsPdf').el.dom.innerHTML = '<p>No Document Loaded.</p>';
			var autoLoad = Ext.getCmp('autoLoadDocs').getValue();
			if(records[0] !== undefined && (autoLoad || records[0].data.requeued)) {
				var doc = this.getById(records[0].data.id);
				doc.lockDocument();
			}
		}
	}
});

Ext.create('Ext.menu.Menu', {
	id: 'gridContextMenu',
	items: [{
		text: 'Unlock Doc & Return to Queue',
		hidden: true,
		itemId: 'unlockDoc',
		icon:  '/img/icons/key.png',
		handler: function() {
			Ext.Ajax.request({
				url: '/admin/queued_documents/unlock_document',
				success: function(response){
					var text = Ext.JSON.decode(response.responseText);
					if(text.success) {
						removeTimeoutListeners();
						documentTimeout.cancel();
						Ext.Msg.alert('Success', text.message);
						Ext.getCmp('queuedDocumentsPdf').el.dom.innerHTML = '<p>No Document Loaded.</p>';
						Ext.getCmp('secureDocMessage').hide();
						Ext.data.StoreManager.lookup('queuedDocumentsStore').load();
					}
					else {
						Ext.Msg.alert('Failure', text.message);
					}
				}
			});
		}
	}],
	listeners: {
		beforeshow: function() {
			var selectionModel = Ext.getCmp('queuedDocGrid').getView().getSelectionModel();
			var doc = selectionModel.getLastSelected();
			if(!Ext.getCmp('autoLoadDocs').getValue() &&
				doc.data.locked_status == 'Locked' && doc.data.locked_by_id == adminId) {
					this.getComponent('unlockDoc').show();
			}
			else {
				this.getComponent('unlockDoc').hide();
			}
		}
	}
});

var gridColumns = [{
		header: 'Id',
		dataIndex: 'id',
		width: 75
	},{
		header: 'Queue Cat',
		dataIndex: 'queue_cat',
		width: 100,
		customSort: 'DocumentQueueCategory.name'
	},{
		header: 'Scanned Location',
		dataIndex: 'scanned_location',
		customSort: 'Location.name',
		width: 125
	},{
		header: 'Queued to Customer',
		dataIndex: 'queued_to_customer',
		flex: 1,
		customSort: 'User.lastname'
	},{
		header: 'Locked Status',
		dataIndex: 'locked_status',
		width: 90
	},{
		header: 'Locked By',
		dataIndex: 'locked_by',
		width: 150,
		customSort: 'LockedBy.lastname'
	},{
		header: 'Last Act. Admin',
		dataIndex: 'last_activity_admin',
		width: 150,
		customSort: 'LastActAdmin.lastname'
	},{
		header: 'Created',
		dataIndex: 'created',
		width: 110,
		format: 'm/d/y g:i a',
		xtype: 'datecolumn'
	},{
		header: 'Modified',
		dataIndex: 'modified',
		width: 110,
		format: 'm/d/y g:i a',
		xtype: 'datecolumn'
	}];

Ext.define('Atlas.grid.QueuedDocPanel', {
	extend: 'Ext.grid.Panel',
	alias: 'widget.atlasdocqueuegridpanel',
	title: 'Documents in Queue',
	store: 'queuedDocumentsStore',
	columns: gridColumns,
		viewConfig: {
			singleSelect: true,
			emptyText: 'No records at this time.',
			listeners: {
				itemClick: function(row, record, itme, index, e, eOpts) {
					if(!Ext.getCmp('autoLoadDocs').getValue()){
						Ext.getCmp('fileDocFormResetButton').fireEvent('click');
						record.lockDocument();
					}
				},
				itemcontextmenu: function(view, rec, node, index, e) {
					e.stopEvent();
					if(!Ext.getCmp('autoLoadDocs').getValue() && rec.data.locked_status === "Locked") {
						Ext.getCmp('gridContextMenu').showAt(e.getXY());
					}
					return false;
				}
			}
		},
		dockedItems: [{
			xtype: 'pagingtoolbar',
			id: 'queuedDocGridPaging',
			store: 'queuedDocumentsStore',
			dock: 'bottom',
			displayInfo: true
		}]
});


Ext.define('Location', {
	extend: 'Ext.data.Model',
	fields: ['id', 'name']
});

Ext.create('Ext.data.Store', {
	model: 'Location',
	storeId: 'locationsStore',
	proxy: {
		type: 'ajax',
		url: '/admin/locations/get_location_list',
		reader: {
			type: 'json',
			root: 'locations'
		},
		limitParam: undefined,
		pageParam: undefined,
		startParam: undefined
	},
	autoLoad: true
});

Ext.define('QueueCategory', {
	extend: 'Ext.data.Model',
	fields: ['id', 'name', 'secure', {
		name : 'img',
		convert: function(value, record){
			var img = '';
			var secure = record.get('secure');
			if(secure) {
				img = '<img src="/img/icons/lock.png" />&nbsp';
			}
			return img;
		}
	}]
});

Ext.create('Ext.data.Store', {
	model: 'QueueCategory',
	storeId: 'queueCatgoriesStore',
	proxy: {
		type: 'ajax',
		url: '/admin/document_queue_categories/get_cats',
		reader: {
			type: 'json',
			root: 'cats'
		},
		limitParam: undefined,
		pageParam: undefined,
		startParam: undefined
	},
	autoLoad: true
});

Ext.define('DocumentQueueFilter', {
	extend: 'Ext.data.Model',
	fields: ['id', 'locations', 'queue_cats', 'self_scan_cats', 'from_date', 'to_date', 'auto_load_docs']
});

Ext.create('Ext.data.Store', {
	model: 'DocumentQueueFilter',
	storeId: 'documentQueueFiltersStore',
	proxy: {
		type: 'ajax',
		url: '/admin/document_queue_filters/get_filters',
		reader: {
			type: 'json',
			root: 'filters'
		},
		limitParam: undefined,
		pageParam: undefined,
		startParam: undefined
	},
	listeners: {
		load: function(store, records, successful, operation, eOpts) {
			if(records[0] !== undefined) {
				Ext.getCmp('documentQueueFilterForm').loadRecord(records[0]);
			}
		}
	},
	autoLoad: true
});


Ext.define('Atlas.form.DocQueueFilterPanel', {
	extend: 'Ext.form.Panel',
	alias: 'widget.docqueuefilterformpanel',
	id: 'documentQueueFilterForm',
	bodyPadding: 10,
	layout: 'anchor',
	defaults: {
		labelWidth: 90,
		anchor: '100%'
	},
	items:[{
		xtype: 'boxselect',
		id: 'locationsSelect',
		encodeSubmitValue: true,
		fieldLabel: 'Locations',
		name: 'locations',
		emptyText: 'Please Select',
		displayField: 'name',
		valueField: 'id',
		store: 'locationsStore'
	},{
		xtype: 'boxselect',
		fieldLabel: 'Queue Cats',
		id: 'queueCatsSelect',
		encodeSubmitValue: true,
		name: 'queue_cats',
		emptyText: 'Please Select',
		displayField: 'name',
		valueField: 'id',
		listConfig: {
			getInnerTpl: function() {
				return '<div>{img}{name}</div>';
			}
		},
		store: 'queueCatgoriesStore'
	},{
		xtype: 'boxselect',
		fieldLabel: 'Self Scan Cats',
		id: 'selfScanCatsSelect',
		encodeSubmitValue: true,
		name: 'self_scan_cats',
		emptyText: 'Please Select',
		displayField: 'name',
		valueField: 'id',
		store: 'selfScanCategoriesStore'
	},{
		xtype: 'datefield',
		fieldLabel: 'From',
		id: 'fromDate',
		name: 'from_date',
		vtype: 'daterange',
		endDateField: 'toDate',
		maxValue: new Date()
	},{
		xtype: 'datefield',
		id: 'toDate',
		fieldLabel: 'To',
		name: 'to_date',
		vtype: 'daterange',
		startDateField: 'fromDate',
		maxValue: new Date()
	},{
		xtype: 'checkbox',
		id: 'autoLoadDocs',
		fieldLabel: 'Auto Load Docs',
		name: 'auto_load_docs',
		inputValue: "1",
		listeners: {
			change: function(checkbox, newValue, oldValue, eOpts) {
				if(newValue) {
					Ext.getCmp('queueSearch').disable();
					Ext.getCmp('queuedDocGrid').collapse();
					Ext.getCmp('queuedDocGridPaging').disable();

				}
				else {
					Ext.getCmp('queueSearch').enable();
					Ext.getCmp('queuedDocGrid').expand();
					Ext.getCmp('queuedDocGridPaging').enable();
				}
			}
		}
	},{
		xtype: 'hidden',
		name: 'id',
		value: 0
	}],
	buttonAlign: 'left',
	buttons:[{
		text: 'Save',
		icon:  '/img/icons/save.png',
		handler: function() {
			var form = this.up('form').getForm();
			if (form.isValid()) {
				Ext.getCmp('queuedDocumentsPdf').el.dom.innerHTML = '<p>No Document Loaded.</p>';
				form.submit({
					waitTitle: 'Saving',
					waitMsg: 'Please wait...',
					success: function(form, action) {
						removeTimeoutListeners();
						documentTimeout.cancel();
						Ext.Msg.alert('Success', action.result.message);
						Ext.data.StoreManager.lookup('documentQueueFiltersStore').load();
						Ext.data.StoreManager.lookup('queuedDocumentsStore').load();
						Ext.getCmp('fileDocumentForm').getForm().reset();
						Ext.getCmp('secondFilingCats').disable();
						Ext.getCmp('thirdFilingCats').disable();
					},
					failure: function(form, action) {
						Ext.Msg.alert('Failed', action.result.message);
					}
				});
			}
		}
	},{
		text: 'Reset Form',
		icon:  '/img/icons/reset.png',
		handler: function() {
			var form = this.up('form').getForm();
			var vals = form.getValues();
			form.reset();
			form.setValues({id: vals.id});
		}
	}]
});

Ext.define('Atlas.form.field.LastNameText', {
	extend: 'Ext.form.field.Text',
	alias: 'widget.lastnametextfield',
	emptyText: 'Enter customer last name',
	fieldLabel: 'Last Name',
	allowBlank: false,
	submitValue: false,
	msgTarget: 'under',
	name: 'lastname'
});

Ext.define('Atlas.form.field.FirstNameComboBox', {
	extend: 'Ext.form.field.ComboBox',
	alias: 'widget.firstnamecombobox',
	fieldLabel: 'First Name',
	allowBlank: false,
	forceSelection: true,
  submitValue: false,
	hideTrigger: true,
	valueField: 'id',
	displayField: 'firstname',
	msgTarget: 'under',
	triggerAction: 'query',
	minChars: 2,
	emptyText: 'Enter at least 2 chars of first name',
	name: 'user_id',
	store: 'customerFirstname',
	listConfig: {
		getInnerTpl: function() {
			return '<div>{fullname}</div>';
		}
	},
	listeners: {
		beforequery: function(queryEvent, eOpts) {
			queryEvent.query = this.prev().getValue() + ',' + queryEvent.query;
		},
		select: function(combo, records, eOpts) {
			this.nextSibling('#fileDocCusDetails').setValue(
				records[0].data.fullssn + ', ' + records[0].data.lastname);
      Ext.getCmp('fileDocumentForm').getComponent('userId').setValue(records[0].data.id);
		}
	}
});

Ext.define('Customer', {
	extend: 'Ext.data.Model',
	fields: ['id', 'firstname', 'lastname', 'fullname', 'ssn', 'fullssn']
});

Ext.create('Ext.data.Store', {
	model: 'Customer',
	storeId: 'customerFirstname',
	proxy: {
		type: 'ajax',
		url: '/admin/users/get_customers_by_first_and_last_name',
		reader: {
			type: 'json',
			root: 'users'
		},
		limitParam: undefined,
		pageParam: undefined,
		startParam: undefined
	}
});

Ext.define('Atlas.form.field.SsnComboBox', {
	extend: 'Ext.form.field.ComboBox',
	alias: 'widget.ssncombobox',
	fieldLabel: 'Last 4 SSN',
	disabled: true,
	forceSelection: true,
	hideTrigger: true,
	emptyText: 'Please enter last 4 of customer ssn',
	msgTarget: 'under',
	name: 'user_id',
	allowBlank: false,
	store: 'customerSsn',
	valueField: 'id',
	displayField: 'ssn',
  submitValue: false,
	listConfig: {
		getInnerTpl: function() {
			return '<div>{fullname}</div>';
		}
	},
	listeners: {
		select: function(combo, records, eOpts) {
			this.nextSibling('#fileDocCusDetails').setValue(
				records[0].data.fullssn + ', ' + records[0].data.lastname);
      Ext.getCmp('fileDocumentForm').getComponent('userId').setValue(records[0].data.id);
		}
	}
});

Ext.create('Ext.data.Store', {
	model: 'Customer',
	storeId: 'customerSsn',
	proxy: {
		type: 'ajax',
		url: '/admin/users/get_customers_by_ssn',
		reader: {
			type: 'json',
			root: 'users'
		},
		limitParam: undefined,
		pageParam: undefined,
		startParam: undefined
	}
});

Ext.define('Atlas.form.field.FindCusByComboBox', {
	extend: 'Ext.form.field.ComboBox',
	alias: 'widget.findcusbycombobox',
	fieldLabel: 'Find Cus. By',
	store: 'findCusBy',
	submitValue: false,
	emptyText: 'Please Select',
	value: 'Name',
	valueField: 'type',
	displayField: 'type',
	listeners: {
		change: function(combo, newValue, oldValue, eOpts) {
			var first = this.next(),
				last = first.next(),
				ssn = last.next();
				cusDetails = ssn.next();
			if(!newValue){
				first.disable();
				last.disable();
				ssn.disable();
			}
			if(newValue === 'Name') {
				first.enable();
				last.enable();
				ssn.disable();
				ssn.reset();
				cusDetails.reset();
			}
			if(newValue === 'Last 4 SSN') {
				first.disable();
				first.reset();
				last.reset();
				last.disable();
				ssn.enable();
				cusDetails.reset();
			}
		}
	}
});

Ext.create('Ext.data.ArrayStore', {
	storeId: 'findCusBy',
	autoLoad: true,
	idIndex: 0,
	fields: ['type'],
	data: [
		['Name'],
		['Last 4 SSN']
	]
});

Ext.define('DocumentFilingCategory', {
	extend: 'Ext.data.Model',
	fields: ['id', 'parent_id', 'name', 'secure', {
		name : 'img',
		convert: function(value, record){
			var img = '';
			var secure = record.get('secure');
			if(secure) {
				img = '<img src="/img/icons/lock.png" />&nbsp';
			}
			return img;
		}
	}]
});

Ext.create('Ext.data.Store', {
	model: 'DocumentFilingCategory',
	storeId: 'documentFilingCats',
	proxy: {
		type: 'ajax',
		url: '/admin/document_filing_categories/get_cats',
		reader: {
			type: 'json',
			root: 'cats'
		},
		limitParam: undefined,
		pageParam: undefined,
		startParam: undefined,
		extraParams: {
			parentId: 'parent'
		}
	},
	autoLoad: true
});

Ext.create('Ext.data.Store', {
	model: 'DocumentFilingCategory',
	storeId: 'documentFilingCats2',
	proxy: {
		type: 'ajax',
		url: '/admin/document_filing_categories/get_cats',
		reader: {
			type: 'json',
			root: 'cats'
		},
		limitParam: undefined,
		pageParam: undefined,
		startParam: undefined,
		extraParams: {
			parentId: 'notParent'
		}
	},
	listeners: {
		datachanged: function(store, records, successful, operation, eOpts) {
			var combo = Ext.getCmp('secondFilingCats');
			if(store.data.length && store.isFiltered())	{
				combo.enable();
			}
			else {
				combo.reset();
				combo.disable();
			}
		}
	},
	autoLoad: true
});

Ext.create('Ext.data.Store', {
	model: 'DocumentFilingCategory',
	storeId: 'documentFilingCats3',
	proxy: {
		type: 'ajax',
		url: '/admin/document_filing_categories/get_cats',
		reader: {
			type: 'json',
			root: 'cats'
		},
		limitParam: undefined,
		pageParam: undefined,
		startParam: undefined,
		extraParams: {
			parentId: 'notParent'
		}
	},
	listeners: {
		datachanged: function(store, records, successful, operation, eOpts) {
			var combo = Ext.getCmp('thirdFilingCats');
			if(store.data.length && store.isFiltered())	{
				combo.enable();
			}
			else {
				combo.reset();
				combo.disable();
			}
		}
	},
	autoLoad: true
});

function getCatFilter(parentId) {
	var catFilter = new Ext.util.Filter({
		exactMatch: true,
		property: 'parent_id',
		root: 'data',
		value: parentId
	});
	return catFilter;
}

Ext.define('Atlas.form.FileDocumentPanel', {
	extend: 'Ext.form.Panel',
	alias: 'widget.filedocumentformpanel',
	id: 'fileDocumentForm',
	bodyPadding: 10,
	layout: 'anchor',
	defaults: {
		labelWidth: 75,
		anchor: '100%'
	},
    initComponent: function() {
      this.on('beforeadd', function(me, field){
        if (!field.allowBlank)
          field.labelSeparator += '<span style="color: red; padding-left: 2px;">*</span>';
      });
      this.callParent(arguments);
    },
	items:[{
		xtype: 'combobox',
		fieldLabel: 'Main Cat',
		id: 'mainFilingCats',
		store: 'documentFilingCats',
		emptyText: 'Select main filing category',
		editable: false,
		displayField: 'name',
		valueField: 'id',
		forceSelection: true,
		allowBlank: false,
		queryMode: 'local',
		triggerAction: 'all',
		name: 'cat_1',
		listConfig: {
			getInnerTpl: function() {
				return '<div>{img}{name}</div>';
			}
		},
		listeners: {
			select: function(combo, records, eOpts) {
				if(records[0] !== undefined) {
					var secondFilingCats = combo.nextSibling(),
					store = secondFilingCats.getStore();
					store.clearFilter();
					secondFilingCats.reset();
					secondFilingCats.nextSibling().getStore().clearFilter();
					store.filter(getCatFilter(records[0].data.id));
					secondFilingCats.nextSibling().getStore().clearFilter();
				}
			}
		}
	},{
		xtype: 'combobox',
		fieldLabel: 'Second Cat',
		id: 'secondFilingCats',
		store: 'documentFilingCats2',
		emptyText: 'Select second filing category',
		displayField: 'name',
		valueField: 'id',
		name: 'cat_2',
		forceSelection: true,
		editable: false,
		queryMode: 'local',
		triggerAction: 'all',
		lastQuery: '',
		disabled: true,
		allowBlank: false,
		listConfig: {
			getInnerTpl: function() {
				return '<div>{img}{name}</div>';
			}
		},
		listeners: {
			select: function(combo, records, eOpts) {
				if(records[0] !== undefined) {
					var thirdFilingCats = combo.nextSibling(),
					store = thirdFilingCats.getStore();
					store.clearFilter();

					thirdFilingCats.reset();
					store.filter(getCatFilter(records[0].data.id));
				}
			}
		}

	},{
		xtype: 'combobox',
		fieldLabel: 'Third Cat',
		id: 'thirdFilingCats',
		name: 'cat_3',
		store: 'documentFilingCats3',
		emptyText: 'Select third filing category',
		displayField: 'name',
		valueField: 'id',
		forceSelection: true,
		editable: false,
		queryMode: 'local',
		triggerAction: 'all',
		lastQuery: '',
		disabled: true,
		allowBlank: false,
		listConfig: {
			getInnerTpl: function() {
				return '<div>{img}{name}</div>';
			}
		}
	},{
		xtype: 'textfield',
		fieldLabel: 'Other',
		name: 'description'
	},{
		xtype: 'findcusbycombobox'
	},{
		xtype: 'lastnametextfield',
		id: 'fileDocLastname'
	},{
		xtype: 'firstnamecombobox',
		id: 'fileDocFirstname'
	},{
		xtype: 'ssncombobox'
	},{
		xtype: 'textfield',
		fieldLabel: 'Cus. Details',
		readOnly: true,
		id: 'fileDocCusDetails',
		submitValue: false
	},{
		xtype: 'checkbox',
		fieldLabel: 'Re-Queue',
		value: 'yes',
		name: 'requeue',
		allowBlank: true
	},{
		xtype: 'hidden',
		name: 'id',
		itemId: 'docId'
	},{
    xtype: 'hidden',
    name: 'user_id',
    itemId: 'userId'
  }],
	buttonAlign: 'left',
	buttons:[{
		text: 'File',
		icon:  '/img/icons/save.png',
		formBind: true,
		disabled: true,
		handler: function() {
			var form = this.up('form').getForm();
			if (form.isValid()) {
				form.submit({
					waitTitle: 'Filing',
					waitMsg: 'Please wait...',
					success: function(form, action) {
						Ext.getCmp('secondFilingCats').disable();
						Ext.getCmp('thirdFilingCats').disable();
						form.reset();
						Ext.getCmp('queuedDocumentsPdf').el.dom.innerHTML = '<p>No Document Loaded.</p>';
						removeTimeoutListeners();
						documentTimeout.cancel();
						Ext.Msg.alert('Success', action.result.message);
						var store = Ext.data.StoreManager.lookup('queuedDocumentsStore');
						if(action.result.locked !== undefined) {
							store.load({params: {id: action.result.locked, requeued: true}});
						}
						else {
							store.load();
						}
					},
					failure: function(form, action) {
						Ext.Msg.alert('Failed', action.result.message);
					}
				});
			}
		}
	},{
		text: 'Reset Form',
		id: 'fileDocFormResetButton',
		icon:  '/img/icons/reset.png',
		listeners: {
			click: function() {
				this.up('form').getForm().reset();
				Ext.getCmp('secondFilingCats').disable();
				Ext.getCmp('thirdFilingCats').disable();
			}
		}
	}]
});

Ext.define('Atlas.form.ReassignQueuePanel', {
	extend: 'Ext.form.Panel',
	alias: 'widget.reassignqueueformpanel',
	id: 'reassignQueueForm',
	bodyPadding: 10,
	layout: 'anchor',
	defaults: {
		labelWidth: 100,
		anchor: '100%'
	},
    initComponent: function() {
      this.on('beforeadd', function(me, field){
        if (!field.allowBlank)
          field.labelSeparator += '<span style="color: red; padding-left: 2px;">*</span>';
      });
      this.callParent(arguments);
    },
	items: [{
		xtype: 'combobox',
		fieldLabel: 'Queue Category',
		store: 'queueCatgoriesStore',
		queryMode: 'local',
		displayField: 'name',
		valueField: 'id',
		listConfig: {
			getInnerTpl: function() {
				return '<div>{img}{name}</div>';
			}
		},
		name: 'queue_category_id',
		forceSelection: true,
		editable: false,
		allowBlank: false
	},{
		xtype: 'hidden',
		name: 'id',
		itemId: 'docId',
		value: null
	}],
	buttonAlign: 'left',
	buttons:[{
		text: 'Re-Assign',
		formBind: true,
		disabled: true,
		handler: function () {
			var form = this.up('form').getForm();
			if(form.isValid()) {
				form.submit({
					waitTitle: 'Saving',
					waitMsg: 'Please wait...',
					success: function(form, action) {
						form.reset();
						Ext.getCmp('queuedDocumentsPdf').el.dom.innerHTML = '<p>No Document Loaded.</p>';
						Ext.getCmp('fileDocumentForm').getForm().reset();
						Ext.getCmp('secondFilingCats').disable();
						Ext.getCmp('thirdFilingCats').disable();
						removeTimeoutListeners();
						documentTimeout.cancel();
						Ext.Msg.alert('Success', action.result.message);
						Ext.data.StoreManager.lookup('queuedDocumentsStore').load();
					},
					failure: function(form, action) {
						Ext.Msg.alert('Failed', action.result.message);
					}
				});
			}
		}
	},{
		text: 'Reset Form',
		icon:  '/img/icons/reset.png',
		listeners: {
			click: function() {
				this.up('form').getForm().reset();
			}
		}
	}]
});

var reasons = [
		['Duplicate scan'],
		['Customer info missing'],
		['Multiple customers in same scan'],
		['Multiple programs in same scan'],
		['Document unreadable'],
		['Scan is incomplete'],
		['Document scanned in error or not needed'],
		['Other']
];

Ext.create('Ext.data.ArrayStore', {
	autoDestroy: true,
	storeId: 'deletedReasonsStore',
	data: reasons,
	idIndex: 0,
	fields: ['reason']
});

Ext.define('Atlas.form.DeleteDocumentPanel', {
	extend: 'Ext.form.Panel',
	alias: 'widget.deletedocumentformpanel',
	id: 'deleteDocumentForm',
	bodyPadding: 10,
	layout: 'anchor',
	defaults: {
		labelWidth: 100,
		anchor: '100%'
	},
    initComponent: function() {
      this.on('beforeadd', function(me, field){
        if (!field.allowBlank)
          field.labelSeparator += '<span style="color: red; padding-left: 2px;">*</span>';
      });
      this.callParent(arguments);
    },
	items:[{
		xtype: 'combobox',
		fieldLabel: 'Deleted Reason',
		name: 'deleted_reason',
		store: 'deletedReasonsStore',
		queryMode: 'local',
		displayField: 'reason',
		valueField: 'reason',
		forceSelection: true,
		editable: false,
		allowBlank: false,
		listeners: {
			select: function(combo, records, eOpts) {
				if(records[0].data.reason === 'Other') {
					this.next().enable();
				}
				else{
					this.next().reset();
					this.next().disable();
				}
			}
		}
	},{
		xtype: 'textfield',
		fieldLabel: 'Other',
		disabled: true,
		name: 'other',
		allowBlank: false
	},{
		xtype: 'hidden',
		name: 'id',
		itemId: 'docId',
		value: null
	}],
	buttonAlign: 'left',
	buttons: [{
		text: 'Delete',
		formBind: true,
		disabled: true,
		handler: function() {
			var form = this.up('form').getForm();
			if(form.isValid()) {
				form.submit({
					waitTitle: 'Saving',
					waitMsg: 'Please wait...',
					success: function(form, action) {
						form.reset();
						Ext.getCmp('queuedDocumentsPdf').el.dom.innerHTML = '<p>No Document Loaded.</p>';
						Ext.getCmp('fileDocumentForm').getForm().reset();
						Ext.getCmp('secondFilingCats').disable();
						Ext.getCmp('thirdFilingCats').disable();
						Ext.Msg.alert('Success', action.result.message);
						removeTimeoutListeners();
						documentTimeout.cancel();
						Ext.data.StoreManager.lookup('queuedDocumentsStore').load();
					},
					failure: function(form, action) {
						Ext.Msg.alert('Failed', action.result.message);
					}
				});
			}
		}
	},{
		text: 'Reset Form',
		icon:  '/img/icons/reset.png',
		listeners: {
			click: function() {
				this.up('form').getForm().reset();
			}
		}
	}]
});

var searchTypes = [
	['Document Id'],
	['Customer Last Name'],
	['Customer Last 4 SSN']
];

Ext.create('Ext.data.ArrayStore', {
	autoDestroy: true,
	storeId: 'searchTypesStore',
	data: searchTypes,
	idIndex: 0,
	fields: ['type']
});

Ext.define('Atlas.form.documentQueueSearchPanel', {
	extend: 'Ext.form.Panel',
	alias: 'widget.documentqueuesearchformpanel',
	id: 'documentQueueSearchPanel',
	bodyPadding: 10,
	height: 100,
	layout: 'anchor',
	defaults: {
		labelWidth: 100,
		anchor: '100%'
	},
    initComponent: function() {
      this.on('beforeadd', function(me, field){
        if (!field.allowBlank)
          field.labelSeparator += '<span style="color: red; padding-left: 2px;">*</span>';
      });
      this.callParent(arguments);
    },
	items: [{
		html:
			'<p>This will search the document queue within whatever filters you have set. ' +
			'Please check the exclude filters checkbox if you want to search the entire queue ' +
			'disregarding filter settings.</p>',
		border: 0,
		margin: '0 0 10 0'
	},{
		xtype: 'combobox',
		fieldLabel: 'Search Type',
		store: 'searchTypesStore',
		displayField: 'type',
		valueField: 'type',
		value: 'Document Id',
		submitValue: false,
		listeners: {
			change: function(combo, newValue, oldValue, eOpts) {
				var docId = combo.nextSibling('[name=doc_id]');
				var lastName = combo.nextSibling('[name=lastname]');
				var last4 = combo.nextSibling('[name=last4]');

				if(newValue === 'Document Id') {
					lastName.reset();
					lastName.disable();
					last4.reset();
					last4.disable();
					docId.enable();
				}
				if(newValue === 'Customer Last Name') {
					docId.reset();
					docId.disable();
					last4.reset();
					last4.disable();
					lastName.enable();
				}
				if(newValue === 'Customer Last 4 SSN') {
					docId.reset();
					docId.disable();
					lastName.reset();
					lastName.disable();
					last4.enable();
				}
			}
		}
	},{
		xtype: 'numberfield',
		fieldLabel: 'Doc Id',
		name: 'doc_id',
		minValue: 0,
		hideTrigger: true,
		allowBlank: false
	},{
		xtype: 'textfield',
		fieldLabel: 'Cus. Last Name',
		name: 'lastname',
		itemId: 'lastName',
		allowBlank: false,
		disabled: true
	},{
		xtype: 'numberfield',
		fieldLabel: 'Cus. Last 4 SSN',
		name: 'last4',
		minValue: 0,
		hideTrigger: true,
		allowBlank: false,
		disabled: true
	},{
		xtype: 'checkbox',
		fieldLabel: 'Exclude Filters',
		name: 'exclude_filters',
		allowBlank: true
	}],
	buttonAlign: 'left',
	buttons: [{
		text: 'Search',
		icon:  '/img/icons/find.png',
		handler: function() {
			var form = this.up('form').getForm();
			if(form.isValid()) {
				var vals = form.getValues();
				var store = Ext.data.StoreManager.lookup('queuedDocumentsStore');
				store.getProxy().extraParams = vals;
				store.load();
				Ext.getCmp('queuedDocumentsPdf').el.dom.innerHTML = '<p>No Document Loaded.</p>';
			}
		}
	},{
		text: 'Reset Search',
		icon:  '/img/icons/reset.png',
		handler: function() {
			var form = this.up('form').getForm();
			form.reset();
			var store = Ext.data.StoreManager.lookup('queuedDocumentsStore');
			store.getProxy().extraParams = {};
			store.load();
			Ext.getCmp('queuedDocumentsPdf').el.dom.innerHTML = '<p>No Document Loaded.</p>';
		}
	}]
});

var states = [
	['AL',"Alabama"],
	['AK',"Alaska"],
	['AZ',"Arizona"],
	['AR',"Arkansas"],
	['CA',"California"],
	['CO',"Colorado"],
	['CT',"Connecticut"],
	['DE',"Delaware"],
	['DC',"District Of Columbia"],
	['FL',"Florida"],
	['GA',"Georgia"],
	['HI',"Hawaii"],
	['ID',"Idaho"],
	['IL',"Illinois"],
	['IN',"Indiana"],
	['IA',"Iowa"],
	['KS',"Kansas"],
	['KY',"Kentucky"],
	['LA',"Louisiana"],
	['ME',"Maine"],
	['MD',"Maryland"],
	['MA',"Massachusetts"],
	['MI',"Michigan"],
	['MN',"Minnesota"],
	['MS',"Mississippi"],
	['MO',"Missouri"],
	['MT',"Montana"],
	['NE',"Nebraska"],
	['NV',"Nevada"],
	['NH',"New Hampshire"],
	['NJ',"New Jersey"],
	['NM',"New Mexico"],
	['NY',"New York"],
	['NC',"North Carolina"],
	['ND',"North Dakota"],
	['OH',"Ohio"],
	['OK',"Oklahoma"],
	['OR',"Oregon"],
	['PA',"Pennsylvania"],
	['RI',"Rhode Island"],
	['SC',"South Carolina"],
	['SD',"South Dakota"],
	['TN',"Tennessee"],
	['TX',"Texas"],
	['UT',"Utah"],
	['VT',"Vermont"],
	['VA',"Virginia"],
	['WA',"Washington"],
	['WV',"West Virginia"],
	['WI',"Wisconsin"],
	['WY',"Wyoming"]
];

Ext.create('Ext.data.ArrayStore', {
	autoDestroy: true,
	storeId: 'statesStore',
	idIndex: 0,
	fields: ['abrv', 'state'],
	data: states
});

Ext.create('Ext.data.ArrayStore', {
	autoDestroy: true,
	storeId: 'surnamesStore',
	idIndex: 0,
	fields: ['surname'],
	data: [
		['Sr'],
		['Jr'],
		['III']
	]
});

Ext.create('Ext.data.ArrayStore', {
	autoDestroy: true,
	storeId: 'gendersStore',
	idIndex: 0,
	fields: ['gender'],
	data: [
		['Male'],
		['Female']
	]
});

Ext.create('Ext.data.ArrayStore', {
	autoDestroy: true,
	storeId: 'languagesStore',
	idIndex: 0,
	fields: ['language'],
	data: [
		['English'],
		['Spanish'],
		['Other']
	]
});

Ext.create('Ext.data.ArrayStore', {
	autoDestroy: true,
	storeId: 'racesStore',
	idIndex: 0,
	fields: ['race'],
	data: [
		['American Indian or Alaska Native'],
		['Asian'],
		['Black or African American'],
		['Hawaiian or Other Pacific Islander'],
		['White']
	]
});

Ext.create('Ext.data.ArrayStore', {
	autoDestroy: true,
	storeId: 'ethnicitiesStore',
	idIndex: 0,
	fields: ['ethnicity'],
	data: [
		['Hispanic or Latino'],
		['Not Hispanic or Latino']
	]
});

Ext.create('Ext.data.ArrayStore', {
	autoDestroy: true,
	storeId: 'boolsStore',
	idIndex: 0,
	fields: ['bool'],
	data: [
		['Yes'],
		['No']
	]
});

Ext.define('Atlas.form.CustomerAddPanel', {
	extend: 'Ext.form.Panel',
	alias: 'widget.customeraddformpanel',
	id: 'customerAddFormPanel',
	bodyPadding: 10,
	height: 100,
	layout: 'anchor',
	defaults: {
		labelWidth: 75,
		anchor: '100%'
	},
    initComponent: function() {
      this.on('beforeadd', function(me, field){
        if (!field.allowBlank)
          field.labelSeparator += '<span style="color: red; padding-left: 2px;">*</span>';
      });
      this.callParent(arguments);
    },
	items: [{
		xtype: 'textfield',
		fieldLabel: 'First Name',
		allowBlank: false,
		name: 'firstname'
	},{
		xtype: 'textfield',
		fieldLabel: 'Last Name',
		allowBlank: false,
		name: 'lastname'
	},{
		xtype: 'textfield',
		fieldLabel: 'Middle Initial',
		name: 'middle_initial'
	},{
		xtype: 'combobox',
		fieldLabel: 'Surname',
		store: 'surnamesStore',
		valueField: 'surname',
		displayField: 'surname',
		emptyText: 'Please Select',
		queryMode: 'local',
		forceSelection: true,
		editable: false,
		name: 'surname'
	},{
		xtype: 'textfield',
		fieldLabel: 'SSN',
		allowBlank: false,
		name: 'ssn'
	},{
		xtype: 'textfield',
		fieldLabel: 'Address',
		allowBlank: false,
		name: 'address_1'
	},{
		xtype: 'textfield',
		fieldLabel: 'City',
		allowBlank: false,
		name: 'city'
	},{
		xtype: 'textfield',
		fieldLabel: 'County',
		allowBlank: false,
		name: 'county'
	},{
		xtype: 'combobox',
		fieldLabel: 'State',
		allowBlank: false,
		store: 'statesStore',
		displayField: 'state',
		valueField: 'abrv',
		emptyText: 'Please Select',
		queryMode: 'local',
		forceSelection: true,
		editable: false,
		name: 'state'
	},{
		xtype: 'numberfield',
		fieldLabel: 'Zip',
		allowBlank: false,
		maxLength: 5,
		hideTrigger: true,
		name: 'zip'
	},{
		xtype: 'textfield',
		fieldLabel: 'Phone',
		allowBlank: false,
		name: 'phone'
	},{
		xtype: 'textfield',
		fieldLabel: 'Alt Phone',
		name: 'alt_phone'
	},{
		xtype: 'combobox',
		fieldLabel: 'Gender',
		allowBlank: false,
		store: 'gendersStore',
		displayField: 'gender',
		valueField: 'gender',
		emptyText: 'Please Select',
		queryMode: 'local',
		forceSelection: true,
		editable: false,
		name: 'gender'
	},{
		xtype: 'datefield',
		fieldLabel: 'DOB',
		allowBlank: false,
		name: 'dob'
	},{
		xtype: 'textfield',
		fieldLabel: 'Email',
		allowBlank: false,
		vtype: 'email',
		name: 'email'
	},{
		xtype: 'combobox',
		fieldLabel: 'Language',
		store: 'languagesStore',
		displayField: 'language',
		valueField: 'language',
		emptyText: 'Please Select',
		queryMode: 'local',
		forceSelection: true,
		editable: false,
		name: 'language'
	},{
		xtype: 'combobox',
		fieldLabel: 'Race',
		store: 'racesStore',
		displayField: 'race',
		valueField: 'race',
		emptyText: 'Please Select',
		queryMode: 'local',
		forceSelection: true,
		editable: false,
		name: 'race'
	},{
		xtype: 'combobox',
		fieldLabel: 'Ethnicity',
		store: 'ethnicitiesStore',
		displayField: 'ethnicity',
		valueField: 'ethnicity',
		emptyText: 'Please Select',
		queryMode: 'local',
		forceSelection: true,
		editable: false,
		name: 'ethnicity'
	},{
		xtype: 'combobox',
		fieldLabel: 'US Veteran',
		store: 'boolsStore',
		displayField: 'bool',
		valueField: 'bool',
		emptyText: 'Please Select',
		queryMode: 'local',
		forceSelection: true,
		editable: false,
		name: 'veteran'
	},{
		xtype: 'hidden',
		name: 'role_id',
		value: 1
	}],
	buttonAlign: 'left',
	buttons: [{
		text: 'Save',
		formBind: true,
		disabled: true,
		handler: function() {
			var form = this.up('form').getForm();
			if(form.isValid()) {
				form.submit({
					waitTitle: 'Saving',
					waitMsg: 'Please wait...',
					success: function(form, action) {
						form.reset();
						Ext.Msg.alert('Success', action.result.message);
					},
					failure: function(form, action) {
						Ext.Msg.alert('Failed', action.result.message);
					}
				});
			}
		}
	},{
		text: 'Reset Form',
		handler: function() {
			this.up('form').getForm().reset();
		}
	}]
});

Ext.onReady(function(){
	//TODO: see about moving viewport out of onReady?
	Ext.create('Ext.container.Viewport', {
		layout: 'border',
		items:[{
			region:'west',
			width: 315,
			id: 'westContainer',
			layout: 'accordion',
			title: 'Queued Documents',
			tools: [{
				type: 'prev',
				tooltip: 'Back to dashboard',
				handler: function()	{
					window.location = '/admin/users/dashboard';
				}
			}],
			items: [{
				xtype: 'panel',
				layout: 'vbox',
				height: 'auto',
				id: 'documentActions',
				title: 'Document Actions',
				disabled: true,
				collapsible: true,
				collapsed: false,
				items: [{
					title: 'File Document',
					border: 0,
					xtype: 'filedocumentformpanel',
					url: '/admin/queued_documents/file_document',
					width: '100%',
					flex: 1
				},{
					title: 'Re-Assign to new Queue',
					xtype: 'reassignqueueformpanel',
					url: '/admin/queued_documents/reassign_queue',
					border: 0,
					height: 100,
					width: '100%',
					hidden: true,
					itemId: 'reassignDocument'
				},{
					title: 'Delete Document',
					xtype: 'deletedocumentformpanel',
					url: '/admin/queued_documents/delete',
					height: 150,
					border: 0,
					width: '100%',
					hidden: true,
					itemId: 'deleteDocument'
				}]
			},{
				title: 'Queue Filters',
				xtype: 'docqueuefilterformpanel',
				url: '/admin/document_queue_filters/set_filters',
				width: '100%',
				collapsible: true,
				collapsed: true
			},{
				title: 'Queue Search',
				id: 'queueSearch',
				xtype: 'documentqueuesearchformpanel',
				width: '100%',
				height: 200,
				collapsible: true,
				collapsed: true
			},{
				title: 'Add Customer',
				id: 'addCustomer',
				xtype: 'customeraddformpanel',
				url: '/admin/users/add',
				width: '100%',
				collapsible: true,
				collapsed: true,
				disabled: true
			}]
		},{
			region: 'center',
			xtype: 'panel',
			layout: {
				align: 'stretch',
				type: 'vbox'
			},
			items: [{
				xtype: 'atlasdocqueuegridpanel',
				id: 'queuedDocGrid',
				height: 185,
				collapsible: true
			},{
				title: 'Document',
				flex: 1,
				layout: 'vbox',
				items : [{
					xtype: 'component',
					id: 'secureDocMessage',
					html: [
						'<p style="background:red; padding:10px; font-weight: bold">' +
						'<img src="/img/icons/lock.png" />' +
						'&nbsp;This is a secure document please handle with care.</p>'
					],
					height: 40,
					width: '100%',
					hidden: true
				},{
					xtype : 'component',
					id: 'queuedDocumentsPdf',
					width: '100%',
					flex: 1,
					html: '<p>No Document Loaded</p>'
					//TODO: look into possibly having a no acrobat installed message here
				}]
			}]
		}]
	});

	Ext.QuickTips.init();
	Ext.useShims = true;
	Ext.data.StoreManager.lookup('queuedDocumentsStore').load();
	if(canFile) {
		Ext.getCmp('documentActions').enable();
	}
	if(!canFile) {
		Ext.getCmp('documentActions').collapse();
		Ext.getCmp('autoLoadDocs').hide();
	}
	if(canDelete) {
		Ext.getCmp('documentActions').getComponent('deleteDocument').show();
	}
	if(canReassign) {
		Ext.getCmp('documentActions').getComponent('reassignDocument').show();
	}
	if(canAddCustomer) {
		Ext.getCmp('addCustomer').enable();
	}
	if(Ext.getCmp('autoLoadDocs').getValue()) {
		Ext.getCmp('queuedDocGrid').collapse();
		Ext.getCmp('queuedDocGridPaging').disable();
	}
	/*
	this is here to release any locked documents on browser close or
	on page exit.
	*/
	window.onunload = function() {
		var url = '/admin/queued_documents/unlock_document';
		unlockDoc(url);
	};
});

function embedPDF(doc){
	/*var myPDF = new PDFObject({
		url: '/admin/queued_documents/view/'+doc.id,
		pdfOpenParams: {
			scrollbars: '1',
			toolbar: '1',
			statusbar: '0',
			messages: '0',
			navpanes: '0',
			view: "FitH"
		}
	}).embed('queuedDocumentsPdf');*/
	
	jQuery('#queuedDocumentsPdf').html('<iframe style="width:100%;height:100%" frameborder="0" src="/admin/queued_documents/view/' + doc.id + '"></iframe>');
	
	if(doc.secure) {
		Ext.getCmp('secureDocMessage').show();
	}
	else {
		Ext.getCmp('secureDocMessage').hide();
	}

}

function unlockDoc(url, passData) {
	if (window.XMLHttpRequest) {
		AJAX=new XMLHttpRequest();
	}
	else {
		AJAX=new ActiveXObject("Microsoft.XMLHTTP");
	}
	if (AJAX) {
		AJAX.open("POST", url, false);
		AJAX.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		AJAX.setRequestHeader("X-Requested-With", "XMLHttpRequest");
		AJAX.send(passData);
	}
}
