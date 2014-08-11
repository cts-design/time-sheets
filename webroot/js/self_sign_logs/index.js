/*
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2011
 * @link http://ctsfla.com
 * @package Atlas V3
 */

Ext.onReady( function() {
  Ext.state.Manager.setProvider(new Ext.state.CookieProvider({
      expires: new Date(new Date().getTime()+(1000*60*60*24*365)) // 1 year
  }));
    
	var rowIndex = null,
		recordId = null,
		userId = null,
		kioskId = '',
		buttonParentId = '',
		locationId = null,
		locations = [],
		services = [];
		
	Ext.QuickTips.init();
	
	Ext.define('SelfSignLog', {
    extend: 'Ext.data.Model',
		fields: [
			'id', 'status', 'firstname', 'lastname', 'last4', 'admin', 
			{name: 'created', type: 'date', dateFormat: 'Y-m-d H:i:s'}, 
			'location', 'service', 'kioskId', 'userId', 'locationId'
		]
	});
		
	var selfSignProxy = Ext.create('Ext.data.proxy.Ajax', {
    url: '/admin/self_sign_logs/',
    simpleSortMode: true,
    extraParams: {
      locations: locations,
      services: services,
      status: 0			
    },
    reader: {
      type: 'json',
      idProperty: 'id',
      root: 'logs',
      totalProperty: 'results'		
    },
    limitParam: undefined,
    pageParam: undefined,
    startParam: undefined		
	});
	
	Ext.define('Atlas.data.SelfSignLogStore', {
		extend: 'Ext.data.Store',
		model: 'SelfSignLog',
		proxy: selfSignProxy,
		autoDestroy: true		
	});
	
	var openSelfSignLogsStore = Ext.create('Atlas.data.SelfSignLogStore', {
		storeId: 'OpenSelfSignLogsStore',
		listeners: {
			load: function() {
				Ext.getCmp('openItems').setText('Open: ' + this.getCount());
			}
		}
	});
	
	var closedSelfSignLogsStore = Ext.create('Atlas.data.SelfSignLogStore', {
		storeId: 'ClosedSelfSignLogsStore',
		listeners: {
			load: function() {
				Ext.getCmp('closedItems').setText('Closed: ' + this.getCount());
			}
		}		
	});
	
	var notHelpedSelfSignLogsStore = Ext.create('Atlas.data.SelfSignLogStore', {
		storeId: 'NotHelpedSelfSignLogsStore',
		listeners: {
			load: function() {
				Ext.getCmp('notHelpedItems').setText('Not Helped: ' + this.getCount());
			}
		}			
	});	
	
	Ext.define('KioskButton', {
		extend: 'Ext.data.Model',
		fields:['id', 'name']
	});
	
	var kioskButtonProxy = Ext.create('Ext.data.proxy.Ajax', {	
		url: '/admin/self_sign_logs/get_kiosk_buttons/',
		reader: {
			type: 'json',
			root: 'buttons'
		},
		limitParam: undefined,
		pageParam: undefined,
		startParam: undefined
	});
	
	Ext.define('Atlas.data.KioskButtonStore', {
		extend: 'Ext.data.Store',
		model: 'KioskButton',
		autoDestroy: true,
		proxy: kioskButtonProxy
	});
	
	var level1ButtonsStore = Ext.create('Atlas.data.KioskButtonStore', {
		storeId: 'level1ButtonsStore',
		listeners: {
			beforeload: function() {
				this.getProxy().url = '/admin/self_sign_logs/get_kiosk_buttons/'+kioskId+'/';
			}
		}
	});
	
	var level2ButtonsStore = Ext.create('Atlas.data.KioskButtonStore', {
		storeId: 'level2ButtonsStore',
		listeners: {
			beforeload: function() {
				this.getProxy().url = '/admin/self_sign_logs/get_kiosk_buttons/'+kioskId+'/'+buttonParentId;
			},
			load: function(store, records, options) {		
				if(records[0] && records[0].data !== undefined) {
					level2Buttons.enable();
					level2Buttons2.enable();
				}
			}
		}
	});
	
	var level3ButtonsStore = Ext.create('Atlas.data.KioskButtonStore', {
		storeId: 'level3ButtonsStore',
		listeners: {
			beforeload: function() {
				this.getProxy().url = '/admin/self_sign_logs/get_kiosk_buttons/'+kioskId+'/'+buttonParentId;
			},
			load: function(store, records, options) {		
				if(records[0] && records[0].data !== undefined) {
					level3Buttons.enable();
					level3Buttons2.enable();
				}
			}
		}
	});
	
	var level1Buttons = Ext.create('Ext.form.field.ComboBox', {
		store: level1ButtonsStore,
		id: 'level1',
		name: 'level1',
		allowBlank: false,
		forceSelection: true,
		editable: false,
		hideLabel: true,
		valueField: 'id',
    displayField: 'name',
    QueryMode: 'remote',
    emptyText: 'Select 1st Button',
    width: 270,
    getListParent: function() {
       return this.el.up('.x-menu');
    },
    listeners: {
      select: function(combo, records, index) {
        buttonParentId = records[0].data.id;
        level2ButtonsStore.load();
        level2Buttons.reset();
        level2Buttons.disable();
        level3Buttons.reset();
        level3Buttons.disable();
        other.disable();
      }
    }
	});
	
	var level2Buttons = Ext.create('Ext.form.field.ComboBox', {
		store: level2ButtonsStore,
		id: 'level2',
		name: 'level2',
		hideLabel: true,
		forceSelection: true,
		editable: false,
		disabled: true,
		allowBlank: false,
		valueField: 'id',
    displayField: 'name',
    queryMode: 'local',
    emptyText: 'Select 2nd Button',
    selectOnFocus: true,
    width: 270,
    getListParent: function() {
      return this.el.up('.x-menu');
    },
    iconCls: 'no-icon',
    listeners: {
      select: function(combo, records, index) {
        buttonParentId = records[0].data.id;
        other.disable();
        if(records[0].data.name  === 'Other' || records[0].data.name  === 'other') {
          other.enable();
        }
        level3ButtonsStore.load();
        level3Buttons.reset();
        level3Buttons.disable();
      }
    }
	}); 
	   
	var level3Buttons = Ext.create('Ext.form.field.ComboBox', {
	    store: level3ButtonsStore,
	    hideLabel: true,
	    id: 'level3',
	    disabled: true,
	    allowBlank: false,
	    forceSelection: true,
	    editable: false,
	    name: 'level3',
	    valueField: 'id',
	    displayField: 'name',
	    queryMode: 'local',
	    emptyText: 'Select 3rd Button',
	    selectOnFocus: true,
	    width: 270,
	    getListParent: function() {
        return this.el.up('.x-menu');
	    },
	    iconCls: 'no-icon',
	    listeners: {
      select: function(combo, records, index) {
        if(records[0].data.name  === 'Other' || records[0].data.name  === 'other') {
          other.enable();
        }
      }
    }
	}); 
	
	var other = Ext.create('Ext.form.field.Text', {
		id: 'other',
		name: 'other',
		hideLabel: false,
		allowBlank: false,
		disabled: true,
		fieldLabel: 'Other',
		width: 225
	});
	
	var reassign = Ext.create('Ext.form.Panel', {
		width: 285,
		frame: true,
		fieldDefaults: {
			labelWidth: 40	
		},
		items: [
			level1Buttons,
			level2Buttons,
			level3Buttons,
			other
		],
		fbar: {
			items: {
				text: 'Save',
				icon:  '/img/icons/save.png',
				handler: function() {
					var form = reassign.getForm();
					if(form.isValid()) {
						var values = form.getValues();
            form.reset();
						level2Buttons.disable();
						level3Buttons.disable();
            other.disable();
            if(contextMenu.isVisible()){
              contextMenu.hide(); 
            }
            Ext.Ajax.request({
              url: '/admin/self_sign_logs/reassign/',
              params: {
                'data[SelfSignLog][id]':  recordId,
                'data[SelfSignLog][level_1]': values.level1,
                'data[SelfSignLog][level_2]': values.level2,
                'data[SelfSignLog][level_3]': values.level3,
                'data[SelfSignLog][other]': values.other
              },
              success: function(response, opts){
                var obj = Ext.decode(response.responseText);
                if(obj.success) {
                  selfSignTabs.getActiveTab().getStore().load();
                  Ext.Msg.alert('Success', obj.message);
                }
                else {
                  opts.failure();
                }		            
              },
              failure: function(response, opts){
                var obj = Ext.decode(response.responseText);
                Ext.Msg.alert('Error', obj.message);
              }
            });				
					}				
				}
			}
		}
	});
	
	var level1Buttons2 = level1Buttons.cloneConfig({
		id: 'level1Buttons2',
		listeners: {
      select: function(combo, records, index) {
        buttonParentId = records[0].data.id;
        level2ButtonsStore.load();
        level2Buttons2.reset();
        level2Buttons2.disable();
        level3Buttons2.reset();
        level3Buttons2.disable();
        other2.disable();
      }		
		}
	});

	var level2Buttons2 = level2Buttons.cloneConfig({
		id: 'level2Buttons2',
    listeners: {
      select: function(combo, records, index) {
        buttonParentId = records[0].data.id;
        other2.disable();
        if(records[0].data.name  === 'Other' || records[0].data.name  === 'other') {
          other2.enable();
        }
        level3ButtonsStore.load();
        level3Buttons2.reset();
        level3Buttons2.disable();
      }
    }	
	});

	var level3Buttons2 = level3Buttons.cloneConfig({
		id: 'level3Buttons2',
    listeners: {
      select: function(combo, records, index) {
        if(records[0].data.name  === 'Other' || records[0].data.name  === 'other') {
          other2.enable();
        }
      }
    }	
	});
	
	var other2 = other.cloneConfig({id: 'other2', name: 'other'});
		
	var newRecord = Ext.create('Ext.form.Panel', {
		width: 285,
		frame: true,
		fieldDefaults: {
			labelWidth: 40	
		},
		items: [
			level1Buttons2,
			level2Buttons2,
			level3Buttons2,
			other2
		],
		fbar: {
			items: {
				text: 'Save',
				icon:  '/img/icons/save.png',
				handler: function() {
					var form = newRecord.getForm();
					if(form.isValid()) {
						var values = form.getValues();
            form.reset();
						level2Buttons2.disable();
						level3Buttons2.disable();
            other2.disable();
            if(contextMenu.isVisible()){
              contextMenu.hide(); 
            }
            Ext.Ajax.request({
              url: '/admin/self_sign_logs/new_record/',
              params: {
                'data[SelfSignLog][user_id]':  userId,
                'data[SelfSignLog][location_id]':  locationId,
                'data[SelfSignLog][kiosk_id]':  kioskId,
                'data[SelfSignLog][level_1]': values.level1,
                'data[SelfSignLog][level_2]': values.level2,
                'data[SelfSignLog][level_3]': values.level3,
                'data[SelfSignLog][other]': values.other
              },
              success: function(response, opts){
                var obj = Ext.decode(response.responseText);
                if(obj.success){
                  selfSignTabs.getActiveTab().getStore().load();
                  Ext.Msg.alert('Success', obj.message);
                }
                else {
                  opts.failure();
                }		            
              },
              failure: function(response, opts){
                var obj = Ext.decode(response.responseText);
                Ext.Msg.alert('Error', obj.message);
              }
            });				
					}				
				}
			}
		}
	});
	
	var contextMenu = Ext.create('Ext.menu.Menu', {
    items: [{
      text: 'Open',
	    id: 'cmOpen',
	    icon:  '/img/icons/note_add.png',
	    iconCls: 'edit',
	    handler: function() {
        var record = selfSignTabs.getActiveTab().getStore().getAt(rowIndex);
        updateStatus(record.data.id, 0);	
	    }
	  },{
      text: 'Close',
      id: 'cmClose',
      icon:  '/img/icons/note_delete.png',
	    handler: function() {
        var record = selfSignTabs.getActiveTab().getStore().getAt(rowIndex);
        updateStatus(record.data.id, 1);	
	    }
	  },{
      text: 'Not Helped',
      id: 'cmNotHelped',
      icon:  '/img/icons/note_error.png',
	    handler: function() {
        var record = selfSignTabs.getActiveTab().getStore().getAt(rowIndex);
        updateStatus(record.data.id, 2);	
	    }
	  },{
      text: 'Reassign',
      hidden: true,
      id: 'cmReassign',
      icon:  '/img/icons/arrow_undo.png',
      menu: {
        cls: 'reassign-menu',
        enableKeyNav: false,
        width: 295,
        items: [
          reassign
        ],
        listeners: {
          beforehide: function() {
            reassign.getForm().reset();
            level2Buttons.disable();
            level3Buttons.disable();
            other.disable();			    
          }
        }
      }
	  },{
      text: 'New Sign In',
      id: 'cmNewRecord',
      hidden: true,
      icon:  '/img/icons/add.png',
      menu: {
        cls: 'new-sign-in-menu',
        enableKeyNav: false,
        width: 295,
        items: [
          newRecord
        ],
        listeners: {
          beforehide: function() {
            newRecord.getForm().reset();
            level2Buttons2.disable();
            level3Buttons2.disable();
            other2.disable();			    
          }
        }			
      }
	  }]
	});

	function renderVetDisIcon() {
		console.log(arguments);

		var result = arguments[2].raw;

		var fullString = '';
		if(result.disability != '' && result.disability != 0)
		{
			fullString += 'D'
		}

		if(result.veteran != '' && result.veteran != 0)
		{
			fullString += 'V'
		}
		return fullString;
	}
	
	Ext.define('Atlas.grid.SelfSignLogsPanel', {
		extend: 'Ext.grid.Panel',
		height: 500,
		stateful: false,
		width: 950,
		frame: true,
		invalidateScrollerOnRefresh: false,
		columns: [{
			text : 'Special',
			renderer : renderVetDisIcon,
			sortable : false,
			width : 50,
			align: 'center'
		}, {
			text: 'Id',
			dataIndex: 'id',
			sortable: true,
			hidden: true
		},{
			text: 'Status',
			dataIndex: 'status',
			sortable: true,
			hidden: true
		},{
			text: 'First',
			dataIndex: 'firstname',
			sortable: true,
			width: 75
		},{
			text: 'Last',
			dataIndex: 'lastname',
			sortable: true,
			width: 75		
		},{
			text: 'Last 4',
			dataIndex: 'last4',
			sortable: true,
			width: 40	
		},{
			text: 'Service',
			dataIndex: 'service',
			sortable: true,
			flex: 1
		},{
			text: 'Last Act. Admin',
			dataIndex: 'admin',
			sortable: true,
			width: 100
		},{
			text: 'Location',
			dataIndex: 'location',
			sortable: true,
			width: 100
		},{
			text: 'Date',
			dataIndex: 'created',
			format: 'm/d/y g:i a',
			xtype: 'datecolumn',
			sortable: true,
			width: 120
		}]		
	});
			
	var openSelfSignLogsGrid = Ext.create('Atlas.grid.SelfSignLogsPanel', {
    title: 'Open',
		store: openSelfSignLogsStore,
		id: 'selfSignGrid',
		tbar: [{
			xtype: 'tbtext',
			id: 'openItems',
			text: 'Open: ' + openSelfSignLogsStore.getCount()	
		}],
		viewConfig: {
      loadMask: false,
      singleSelect: true,
      emptyText: 'No records at this time.',
      listeners: {
        itemcontextmenu: function(view, rec, node, index, e) {
          e.stopEvent();
          contextMenu.showAt(e.getXY());
          Ext.getCmp('cmOpen').hide();
          Ext.getCmp('cmNotHelped').show();
          Ext.getCmp('cmClose').show();
          Ext.getCmp('cmReassign').show();
          Ext.getCmp('cmNewRecord').hide();		
          rowIndex = index;
          recordId = rec.data.id;
          kioskId = rec.data.kioskId; 
          userId = rec.data.userId;
          locationId = rec.data.locationId;                
          return false;
        }
      }		
    }
	});
		
	var closedSelfSignLogsGrid = Ext.create('Atlas.grid.SelfSignLogsPanel', {
		title: 'Closed',
		store: closedSelfSignLogsStore,
		id: 'closedSelfSignGrid',
		tbar: [{
			xtype: 'tbtext',
			id: 'closedItems',
			text: 'Closed: ' + closedSelfSignLogsStore.getCount()	
		}],		
		viewConfig: {
			loadMask: true,
			singleSelect: true,
			emptyText: 'No records at this time.',
      listeners: {
        itemcontextmenu: function(view, rec, node, index, e) {
          e.stopEvent();
          contextMenu.showAt(e.getXY());
          Ext.getCmp('cmClose').hide();
          Ext.getCmp('cmOpen').show();
          Ext.getCmp('cmNotHelped').show();
          Ext.getCmp('cmReassign').hide();
          Ext.getCmp('cmNewRecord').show();		
          rowIndex = index;
          recordId = rec.data.id;
          kioskId = rec.data.kioskId; 
          userId = rec.data.userId;
          locationId = rec.data.locationId;                
          return false;
        }
      }		
		}				
	});	
	
	var notHepledSelfSignLogsGrid = Ext.create('Atlas.grid.SelfSignLogsPanel', {
		title: 'Not Helped',
		store: notHelpedSelfSignLogsStore,
		id: 'notHelpedSelfSignGrid',
		tbar: [{
			xtype: 'tbtext',
			id: 'notHelpedItems',
			text: 'Not Helped: ' + notHelpedSelfSignLogsStore.getCount()	
		}],		
		viewConfig: {
			loadMask: true,
			singleSelect: true,
			emptyText: 'No records at this time.',
      listeners: {
        itemcontextmenu: function(view, rec, node, index, e) {
          e.stopEvent();
          contextMenu.showAt(e.getXY());
          Ext.getCmp('cmNotHelped').hide();
          Ext.getCmp('cmClose').show();
          Ext.getCmp('cmOpen').show();
          Ext.getCmp('cmReassign').hide();
          Ext.getCmp('cmNewRecord').show();
          rowIndex = index;
          recordId = rec.data.id;
          kioskId = rec.data.kioskId; 
          userId = rec.data.userId;
          locationId = rec.data.locationId;                
          return false;
        }
      }
		}
	});	
	
	Ext.define('Location', {
		extend: 'Ext.data.Model',
		fields: ['id', 'name']
	});
	
	var locationsStore = Ext.create('Ext.data.Store', {
		model: 'Location',
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
	
	Ext.define('Service', {
		extend: 'Ext.data.Model',
		fields: ['id', 'name']
	});
	
	var servicesStore = Ext.create('Ext.data.Store', {
		model: 'Service',
		proxy: {
			type: 'ajax',
			url: '/admin/self_sign_logs/get_services',
			reader: {
				type: 'json',
				root: 'services'
			},
			limitParam: undefined,
			pageParam: undefined,
			startParam: undefined	
		},
		autoLoad: false	
	});
	
	var selfSignSearch = Ext.create('Ext.form.FormPanel', {
		frame: true,
		collapsible: true,
		stateful: false,
		renderTo: 'SelfSignSearch',
		fieldDefautls: {
			labelWidth: 55
		},
		title: 'Filters',
		id: 'selfSignSearch',
		items: [{
			xtype: 'container',
			border: 0,
			layout: 'column',
			items: [{
				xtype: 'container',
				layout: 'anchor',
				columnWidth: 0.5,
				padding: 10,
				items: [{
					border: 0,
					xtype: 'boxselect',
					id: 'locationsSelect',
					fieldLabel: 'Locations',
					displayField: 'name',
					valueField: 'id',
					store: locationsStore,			
					queryMode: 'local',
					allowAddNewData: true,
					emptyText: 'Please make a selection',
					name: 'locations',
					stateful: true, 
					stateEvents: ['change'],
					allowBlank: true,
					msgTarget: 'under',
					width: 400,
					listeners: {
						change: function() {
							Ext.getCmp('servicesSelect').reset();
              var val = this.getValue();
              if(val.length > 0) {
                servicesStore.load({params: {
                  locations: Ext.util.Format.htmlEncode(val)
                }});							
              }
						}
					},
					getState: function() {					
			            return this.getValue();					
					},
          applyState: function(state) {
            if(state[0] !== undefined) {
              // have to manually remove empty text, most likely a bug
              Ext.apply(this, {emptyText: ''});
              this.getStore().on('load', function(){
								var selected = '';
								for(var i in state) {
									selected += state[i] += ', ';
								}
								if(selected !== '') {
									this.select(selected);																		
								}
							}, this);
						}
          }				
				}]
			},{
				layout: 'anchor',
				xtype: 'container',
				columnWidth: 0.5,
				border: 0,
				padding: 10,
				items: [{
					xtype: 'boxselect',
					id: 'servicesSelect',
					store: servicesStore,
					queryMode: 'local',
					valueField: 'id',
					displayField: 'name',
					name: 'services',
					fieldLabel: 'Services',
					stateful: true,
					stateEvents: ['change'],					
					emptyText: 'Please make a selection',
					allowBlank: true,
					msgTarget: 'under',
					width: 400,
					listeners: {
						beforequery: function() {
							var val = Ext.getCmp('locationsSelect').getValue();				
							if(val == '') {
								this.markInvalid('Please select a location first');
							}			
						}
					},
					getState: function() {
            return this.getValue();					
					},
          applyState: function(state) {
            if(state[0] != undefined) {
            // have to manually remove empty text, most likely a bug
              Ext.apply(this, {emptyText: ''});        	
							this.getStore().on('load', function(){
								var selected = '';
								for(var i in state) {
									selected += state[i] += ', ';
								}
								if(selected !== '') {
									this.select(selected);																		
								}						
								Ext.getCmp('filter').fireEvent('click');				
							}, this, {single: true});
						}
          }			
				}]
			}]
		}],
		fbar: [{
			text: 'Filter',
			id: 'filter',
			stateful: false,
			icon:  '/img/icons/find.png',
			listeners: {
				click: function() {
					var form = selfSignSearch.getForm();
					if(form.isValid()) {
						locations = Ext.util.Format.htmlEncode(Ext.getCmp('locationsSelect').getValue());
						services = Ext.util.Format.htmlEncode(Ext.getCmp('servicesSelect').getValue());
						var grid = selfSignTabs.getActiveTab();
						var status = 0;
						switch(grid.title) {
							case 'Closed' : 
								status = 1;
								break;
							case 'Not Helped' :
								status = 2;
						}
						selfSignProxy.extraParams = {
							locations: locations,
							services: services,
							status: status
						}
						selfSignTabs.getActiveTab().getStore().load();
					}
										
				}
			}
		},{
			text: 'Reset',
			icon:  '/img/icons/arrow_redo.png',
			handler: function() {
				locations = [];
				services = [];
				selfSignProxy.extraParams.locations = [];
				selfSignProxy.extraParams.services = [];
				var services = Ext.getCmp('servicesSelect');
				var locations = Ext.getCmp('locationsSelect');
				// kludge to put empty text back
				Ext.apply(locations, {emptyText: 'Please make a selection'});
				Ext.apply(services, {emptyText: 'Please make a selection'});				
				locations.reset();
				services.reset();
				selfSignTabs.getActiveTab().getStore().load();
				var cp = Ext.state.Manager.getProvider();
				cp.clear('ext-servicesSelect');
				cp.clear('ext-locationsSelect');
			}
		}]
	});
	
	
	var selfSignTabs = Ext.create('Ext.tab.Panel', {
		renderTo: 'SelfSignLogs',
		items: [openSelfSignLogsGrid, closedSelfSignLogsGrid, notHepledSelfSignLogsGrid],
		listeners: {
			tabchange: function() {
				var grid = this.getActiveTab();
				switch(grid.title) {
					case 'Open':
						selfSignProxy.extraParams.status = 0;
						break;
					case 'Closed':
						selfSignProxy.extraParams.status = 1;
						break;
					case 'Not Helped':
						selfSignProxy.extraParams.status = 2;
						break;	
				}
				grid.getStore().load();
			}
		}	
	});
	
	function updateStatus(id, status) {
    Ext.Ajax.request({
        url: '/admin/self_sign_logs/update_status/' + id + '/' + status,
        success: function(response){ 
          selfSignTabs.getActiveTab().getStore().load();
        },
        failure: function(response){
            Ext.Msg.alert('Error', 'An error has occured, please try again.');
        }
    });	
	}
	
	var loadLogs = {
		run: function() {
			if(selfSignTabs.getActiveTab().title == 'Open') {
				openSelfSignLogsStore.load({params: {status: 0}});		
			}		
		},
		interval: 15000
	}
	Ext.TaskManager.start(loadLogs);
});
