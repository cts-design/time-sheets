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
		'id', 'cat_1', 'cat_2','cat_3'
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
		'id', 'queue_cat', 'scanned_location',
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
			embedPDF(this.data.id);

			Ext.getCmp('fileDocumentForm').getComponent('docId').setValue(this.data.id);
			Ext.getCmp('reassignQueueForm').getComponent('docId').setValue(this.data.id);

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
						embedPDF(text.QueuedDocument.id);
						Ext.getCmp('fileDocumentForm').getComponent('docId').setValue(text.QueuedDocument.id);
						Ext.getCmp('reassignQueueForm').getComponent('docId').setValue(text.QueuedDocument.id);
						docQueueMask.hide();
					}
					else {
						opts.failure();
					}
				},
				failure: function(response, opts) {
					Ext.MessageBox.alert(
						'Failure', 'Unable to lock document for viewing.<br />' +
						'Make sure it is not locked by someone else.<br />' +
						'Please use the refresh button in the grid toolbar<br />' +
						'to update the grid view if nessesary.'
					);
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
}

function autoPopulateFilingCats(doc) {
	var cat, cat2Store, cat3Store;
	if(doc.data.self_scan_cat_id) {
		cat = Ext.data.StoreManager.lookup('selfScanCategoriesStore').getById(doc.data.self_scan_cat_id);
		if(cat.data.cat_1 !== undefined) {
			Ext.getCmp('mainFilingCats').select(cat.data.cat_1);
			cat2Store = Ext.data.StoreManager.lookup('documentFilingCats2');
			cat2Store.load({params:{'parentId' : cat.data.cat_1}});
		}
		if(cat.data.cat_2 !== undefined) {
			Ext.getCmp('secondFilingCats').select(cat.data.cat_2);
			cat3Store = Ext.data.StoreManager.lookup('documentFilingCats3');
			cat3Store.load({params:{'parentId' : cat.data.cat_2}});
		}
		if(cat.data.cat_3 !== undefined) {
			Ext.getCmp('thirdFilingCats').select(cat.data.cat_3);
		}
	}
	else if(doc.data.bar_code_definition_id) {
		cat = Ext.data.StoreManager.lookup('barCodeDefinitionsStore').getById(doc.data.bar_code_definition_id);
		if(cat.data.cat_1 !== undefined) {
			Ext.getCmp('mainFilingCats').select(cat.data.cat_1);
			cat2Store = Ext.data.StoreManager.lookup('documentFilingCats2');
			cat2Store.load({params:{'parentId' : cat.data.cat_1}});
		}
		if(cat.data.cat_2 !== undefined) {
			Ext.getCmp('secondFilingCats').select(cat.data.cat_2);
			cat3Store = Ext.data.StoreManager.lookup('documentFilingCats3');
			cat3Store.load({params:{'parentId' : cat.data.cat_2}});
		}
		if(cat.data.cat_3 !== undefined) {
			Ext.getCmp('thirdFilingCats').select(cat.data.cat_3);
		}
	}
}

Ext.create('Ext.data.Store', {
	storeId:'queuedDocumentsStore',
	pageSize: 5,
	model: QueuedDocument,
	proxy: {
		type: 'ajax',
		url: '/admin/queued_documents',
		reader: {
			type: 'json',
			root: 'docs',
			totalProperty: 'totalCount'
		}
	},
	listeners: {
		load: function(store, records, successful, operation, eOpts) {
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
		text: 'View Doc',
		icon:  '/img/icons/lock.png',
		handler: function() {
			var selectionModel = Ext.getCmp('queuedDocGrid').getView().getSelectionModel();
			var doc = selectionModel.getLastSelected();
			Ext.getCmp('fileDocFormResetButton').fireEvent('click');
			doc.lockDocument();
		}
	},{
		text: 'Release Doc',
		hidden: true,
		itemId: 'releaseDoc',
		icon:  '/img/icons/lock_open.png',
		handler: function() {
			Ext.Ajax.request({
				url: '/admin/queued_documents/unlock_document',
				success: function(response){
					var text = Ext.JSON.decode(response.responseText);
					if(text.success) {
						documentTimeout.cancel();
						Ext.EventManager.removeListener(Ext.getBody(), 'mousemove', setDocTimeOut);
						Ext.EventManager.removeListener(Ext.getBody(), 'keypress', setDocTimeOut);
						Ext.Msg.alert('Success', text.message);
						Ext.getCmp('queuedDocumentsPdf').el.dom.innerHTML = '<p>No Document Loaded.</p>';
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
					this.getComponent('releaseDoc').show();
			}
			else {
				this.getComponent('releaseDoc').hide();
			}
		}
	}
});

Ext.define('Atlas.grid.QueuedDocPanel', {
	extend: 'Ext.grid.Panel',
	alias: 'widget.atlasdocqueuegridpanel',
	title: 'Documents in Queue',
	store: 'queuedDocumentsStore',
	columns: [{
			header: 'Id',
			dataIndex: 'id',
			width: 75
		},{
			header: 'Queue Cat',
			dataIndex: 'queue_cat',
			width: 75
		},{
			header: 'Scanned Location',
			dataIndex: 'scanned_location'
		},{
			header: 'Queued to Customer',
			dataIndex: 'queued_to_customer',
			width: 150
		},{
			header: 'Locked Status',
			dataIndex: 'locked_status',
			width: 80
		},{
			header: 'Locked By',
			dataIndex: 'locked_by',
			width: 115
		},{
			header: 'Last Act. Admin',
			dataIndex: 'last_activity_admin',
			width: 115
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
		}],
		viewConfig: {
			singleSelect: true,
			emptyText: 'No records at this time.',
			listeners: {
				itemcontextmenu: function(view, rec, node, index, e) {
					e.stopEvent();
					Ext.getCmp('gridContextMenu').showAt(e.getXY());
						docId = rec.data.id;
					return false;
				}
			}
		},
		dockedItems: [{
			xtype: 'pagingtoolbar',
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
	fields: ['id', 'name']
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
	fields: ['id', 'locations', 'queue_cats', 'from_date', 'to_date', 'auto_load_docs']
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
		store: 'queueCatgoriesStore'
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
		inputValue: "1"
	},{
		xtype: 'hidden',
		name: 'id',
		value: 0
	}],
	buttonAlign: 'left',
	buttons:[{
		text: 'Save',
		icon:  '/img/icons/save.png',
		formBind: true,
		disabled: true,
		handler: function() {
			var form = this.up('form').getForm();
			if (form.isValid()) {
				Ext.getCmp('queuedDocumentsPdf').el.dom.innerHTML = '<p>No Document Loaded.</p>';
				form.submit({
					waitTitle: 'Saving',
					waitMsg: 'Please wait...',
					success: function(form, action) {
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
		text: 'Reset',
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
	triggerAction: 'query',
	store: 'customerSsn',
	valueField: 'id',
	displayField: 'ssn',
	listConfig: {
		getInnerTpl: function() {
			return '<div>{fullname}</div>';
		}
	},
	listeners: {
		select: function(combo, records, eOpts) {
			this.nextSibling('#fileDocCusDetails').setValue(
				records[0].data.fullssn + ', ' + records[0].data.lastname);
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
	fields: ['id', 'parent_id', 'name']
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
		startParam: undefined
	},
	listeners: {
		load: function(store, records, successful, operation, eOpts) {
			var combo = Ext.getCmp('secondFilingCats');
			if(records[0] !== undefined)	{
				combo.enable();
			}
			else {
				combo.clearInvalid();
				combo.disable();
			}
		}
	}
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
		startParam: undefined
	},
	listeners: {
		load: function(store, records, successful, operation, eOpts) {
			var combo = Ext.getCmp('thirdFilingCats');
			if(records[0] !== undefined)	{
				combo.enable();
			}
			else {
				combo.clearInvalid();
				combo.disable();
			}
		}
	}
});

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
		name: 'cat_1',
		listeners: {
			select: function(combo, records, eOpts) {
				if(records[0] !== undefined) {
					var store = Ext.data.StoreManager.lookup('documentFilingCats2');
					store.load({params:{'parentId' : records[0].data.id}});
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
		disabled: true,
		allowBlank: false,
		listeners: {
			select: function(combo, records, eOpts) {
				if(records[0] !== undefined) {
					var store = Ext.data.StoreManager.lookup('documentFilingCats3');
					store.load({params:{'parentId' : records[0].data.id}});
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
		disabled: true,
		allowBlank: false
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
		name: 'requeue'
	},{
		xtype: 'hidden',
		name: 'id',
		itemId: 'docId'
	}],
	buttonAlign: 'left',
	buttons:[{
		text: 'File',
		icon:  '/img/icons/save.png',
		formBind: true,
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
		text: 'Reset',
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
		labelWidth: 90,
		anchor: '100%'
	},
	items: [{
		xtype: 'combobox',
		fieldLabel: 'Queue Category',
		store: 'queueCatgoriesStore',
		queryMode: 'local',
		displayField: 'name',
		valueField: 'id',
		name: 'queue_category_id',
		forceSelection: true,
		editable: false,
		allowBlank: false
	}, {
		xtype: 'hidden',
		name: 'id',
		itemId: 'docId',
		value: null
	}],
	buttonAlign: 'left',
	buttons:[{
		text: 'Re-Assign',
		formBind: true,
		handler: function () {
			var form = this.up('form').getForm();
			if(form.isValid()) {
				form.submit({
					waitTitle: 'Saving',
					waitMsg: 'Please wait...',
					success: function(form, action) {
						form.reset();
						Ext.getCmp('queuedDocumentsPdf').el.dom.innerHTML = '<p>No Document Loaded.</p>';
						Ext.Msg.alert('Success', action.result.message);
						Ext.data.StoreManager.lookup('queuedDocumentsStore').load();
					},
					failure: function(form, action) {
						Ext.Msg.alert('Failed', action.result.message);
					}
				});
			}
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
				title: 'Document Actions',
				collapsible: true,
				collapsed: false,
				items: [{
					title: 'File Document',
					border: 0,
					xtype: 'filedocumentformpanel',
					url: '/admin/queued_documents/file_document',
					width: '100%',
					height: 325
				},{
					title: 'Re-Assign to new Queue',
					xtype: 'reassignqueueformpanel',
					url: '/admin/queued_documents/reassign_queue',
					border: 0,
					flex: 1,
					width: '100%'
				},{
					title: 'Delete Document',
					html: 'Panel content!',
					flex: 1,
					width: '100%'
				}]
			},{
				title: 'Queue Filters',
				xtype: 'docqueuefilterformpanel',
				url: '/admin/document_queue_filters/set_filters',
				height: 150,
				width: '100%',
				collapsible: true,
				collapsed: true
			},{
				title: 'Queue Search',
				html: 'Panel content!',
				width: '100%',
				height: 200,
				collapsible: true,
				collapsed: true
			},{
				title: 'Add Customer',
				html: 'Panel content!',
				width: '100%',
				height: 600,
				collapsible: true,
				collapsed: true
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
				layout: 'fit',
				items : [{
					xtype : 'component',
					id: 'queuedDocumentsPdf',
					width: 900,
					height: 400,
					html: '<p>No document currently loaded</p>'
					//TODO: look into possibly having a no acrobat installed message here
				}]
			}]
		}]
	});

	Ext.QuickTips.init();
	Ext.useShims = true;
	Ext.data.StoreManager.lookup('queuedDocumentsStore').load();

	/*
	this is here to release any locked documents on browser close or
	on page exit.
	*/
	window.onunload = function() {
		var url = '/admin/queued_documents/unlock_document';
		unlockDoc(url);
	};
});

function embedPDF(docId){
	var myPDF = new PDFObject({
		url: '/admin/queued_documents/view/'+docId,
		height: "800px",
		pdfOpenParams: {
			scrollbars: '1',
			toolbar: '1',
			statusbar: '0',
			messages: '0',
			navpanes: '0'
		}
	}).embed('queuedDocumentsPdf');
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