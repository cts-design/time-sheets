var Reports;
Reports = {
  activeChartType: null, // can be hourly, daily, weekly, monthly
  filterWindow: null,
	selectedLocations: null,
  todaysDate: null,
	loadMask: null,

	filters: {
    fromDate: '2011-06-06',
    fromTime: '08:00:00',
    toDate: '2011-06-10',
    toTime: '17:00:00',
    admins: [],
    kiosks: [],
    locations: []
  },

	locations: {
		count: 0,
		names: []
	},

	services: {
		count: 0,
		names: []
	},

  initialize: function () {
		"use strict";

		this.todaysDate = this.filters.fromDate = this.filters.toDate = Ext.Date.format(new Date(), 'Y-m-d');
    this.activeChartType = 'hourly';

		this.initializeProgramStore();
		this.initializeServicesStore();
		this.initializeAdminStore();
		this.initializeKioskStore();
		this.initializeFilterStore();
		this.initializeLocationStore();
  },

	// Load this first so we know how many locations were
	// dealing with in each region
  initializeLocationStore: function () {
		"use strict";

    Ext.define('Location', {
			extend: 'Ext.data.Model',
      fields: [ 'id', 'name', 'public_name' ]
    });

		Ext.create('Ext.data.Store', {
			storeId: 'LocationStore',
			model: 'Location',
      autoLoad: true,
      proxy: {
				type: 'ajax',
				url: '/admin/reports/get_all_locations',
				reader: {
					type: 'json',
					root: 'locations'
        }
      },
			listeners: {
				load: {
					scope: this,
					fn: this.loadLocationCache
				}
			}
    });
  },

	initializeServicesStore: function () {
		"use strict";

    Ext.define('Service', {
			extend: 'Ext.data.Model',
			fields: [ 'id', 'name' ]
    });

		Ext.create('Ext.data.Store', {
			storeId: 'ServiceStore',
			model: 'Service',
			autoLoad: true,
			proxy: {
				type: 'ajax',
				url: '/admin/reports/get_all_programs',
				reader: {
					type: 'json',
					root: 'programs'
        }
      },
			listeners: {
				load: {
					scope: this,
					fn: this.loadServiceCache
				}
			}
    });
	},

	loadServiceCache: function () {
		"use strict";

		var store = Ext.StoreManager.lookup('ServiceStore');

		if (store.getCount()) {
			Reports.services.count = store.data.length;

			Ext.each(store.data.items, function (item, index, items) {
				Reports.services.names.push(item.data.name);
			});
		}

		return Reports.services.names;
	},

	loadLocationCache: function () {
		"use strict";

		var store = Ext.StoreManager.lookup('LocationStore');

		if (store.getCount()) {
			Reports.locations.count = store.data.length;

			Ext.each(store.data.items, function (item, index, items) {
				Reports.locations.names.push(item.data.name);
			});
		}

		this.initializeUDIChartStore();

		return Reports.locations.names;
	},

	// This will initialize the Unduplicated Individuals Chart Store
	initializeUDIChartStore: function () {
		"use strict";

		var locations = Reports.locations.names,
			services = Reports.services.names;

		locations.push('time');
		services.push('time');

		if (!this.uidStore) {
			this.uidStore = Ext.create('Ext.data.JsonStore', {
				storeId: 'UnduplicatedIndividualsStore',
	      fields: locations,
				listeners: {
					datachanged: {
						scope: this,
						fn: function (store, eOpts) {
							var records = store.data.items,
								chartContainer = Ext.getCmp('totalUnduplicated'),
								chart = chartContainer.items.items[0],
								series = chart.series.items[0],
								axes = chart.axes.items[0],
								includeRawData = Ext.getCmp('includeRawData').checked,
								locations = [],
								newTitle,
								table;

							Ext.Object.each(records, function (key, value, me) {
								Ext.Object.each(value.data, function (k, v, m) {
									if (k !== "time" && !Ext.isEmpty(v)) {
										Ext.Array.include(locations, k);
									}
								});
							});

							if (includeRawData) {
								console.log(Reports.filters.meta);
								table = this.buildRawDataTable(store, records);

								if (!Ext.isEmpty(chartContainer.items.items[1])) {
									chartContainer.items.items[1].destroy();
								}

								chartContainer.insert(1, {
									html: table,
									border: 0,
									margin: '10px 0 0 50px',
									height: 200,
									width: '100%'
								});
							} else {
								if (!Ext.isEmpty(chartContainer.items.items[1])) {
									chartContainer.items.items[1].destroy();
								}
							}

							series.yField = axes.fields = locations;
						}
					}
				}
			});

			Ext.create('Ext.data.JsonStore', {
				storeId: 'TotalServicesStore',
	      fields: services,
				listeners: {
					datachanged: {
						scope: this,
						fn: function (store, eOpts) {
							var records = store.data.items,
								chartContainer = Ext.getCmp('servicesChartContainer'),
								chart = Ext.getCmp('servicesChart'),
								series = chart.series,
								axes = chart.axes.items[0],
								selectedServices = [],
								includeRawData = Ext.getCmp('includeRawData').checked,
								tmpServices = [],
								newChartConfig,
								filterWindow = this.filterWindow,
								table;

							Ext.Object.each(records, function (key, value, me) {
								Ext.Object.each(value.data, function (k, v, m) {
									if (k !== "time" && !Ext.isEmpty(v)) {
										Ext.Array.include(selectedServices, k);
									}
								});
							});

							Ext.Array.each(selectedServices, function (item, index, allItems) {
								tmpServices.push({
									type: 'line',
			            highlight: {
		                size: 7,
		                radius: 7
			            },
			            axis: 'left',
			            xField: 'name',
			            yField: item,
			            markerConfig: {
		                type: 'circle',
		                size: 4,
		                radius: 4,
		                'stroke-width': 0
			            },
									tips: {
										trackMouse: true,
										renderer: function (storeItem, item) {
											this.setTitle(item.series.yField);
											this.update('Total for ' + storeItem.data.time + ': ' + item.value[1]);
											this.setWidth(item.series.yField.length * 7);
										}
									}
								});
							});

							chart.destroy();

							newChartConfig = {
								xtype: 'chart',
								id: 'servicesChart',
								flex: 1,
								width: '100%',
								store: store,
								label: {
									renderer: Ext.util.Format.numberRenderer('0,0')
								},
			          legend: {
			            position: 'right'
			          },
								axes: [{
									type: 'Numeric',
									minimum: 0,
									fields: selectedServices,
									position: 'left',
									title: 'Total Services',
									minorTickSteps: 1,
									grid: true
								}, {
									type: 'Category',
									position: 'bottom',
									fields: ['time']
								}],
								series: tmpServices
							};

							chartContainer.insert(0, newChartConfig);

							if (includeRawData) {
								table = this.buildRawDataTable(store, records);

								if (!Ext.isEmpty(chartContainer.items.items[1])) {
									chartContainer.items.items[1].destroy();
								}

								chartContainer.insert(1, {
									html: table,
									border: 0,
									margin: '10px 0 0 50px',
									height: 200,
									width: '100%'
								});
							} else {
								if (!Ext.isEmpty(chartContainer.items.items[1])) {
									chartContainer.items.items[1].destroy();
								}
							}
						}
					}
				}
			});
			
			this.initializeViewPort();
		}
	},

	buildRawDataTable: function (store, records) {
		"use strict";

		var keys = [],
			time = [],
			counts = {},
			tableStart = '<table class="raw-data">',
			tableEnd = '</table>',
			tableHead = '',
			tableBody = '',
			table,
			colCount = {};

		Ext.Array.each(records, function (item, index, allItems) {
			time.push(item.data.time);
			colCount[item.data.time] = 0;

			Ext.Object.each(item.data, function (key, value, me) {
				if (key !== 'time' && !Ext.isEmpty(value)) {
					Ext.Array.include(keys, key);
				}
			});
		});

		// build table head
		tableHead = '<tr><th>&nbsp</th>';
		Ext.Array.each(time, function (item, index) {
			tableHead += '<th>' + item + '</th>';
		});
		tableHead += '<th>Total</th>';
		tableHead += '</tr>';

		Ext.Array.each(keys, function (item, index) {
			var rowCount = 0;

			tableBody += '<tr>';
			tableBody += '<td>' + item + '</td>';

			// find corresponding values
			Ext.Array.each(time, function (timeItem, timeIndex) {
				var recIndex = store.find('time', timeItem),
					rec;

				if (recIndex !== -1) {
					rec = store.getAt(recIndex);
					if (Ext.isEmpty(rec.data[item])) {
						colCount[timeItem] += 0;
						tableBody += '<td>0</td>';
					} else {
						rowCount += rec.data[item];
						colCount[timeItem] += rec.data[item];
						tableBody += '<td>' + rec.data[item] + '</td>';
					}
				}
			});

			tableBody += '<td class="total">' + rowCount + '</td>';
			tableBody += '</tr>';
		});

		tableBody += '<tr><td class="total">Totals</td>';
		Ext.Array.each(time, function (item, index) {
			tableBody += '<td class="total">' + colCount[item] + '</td>';
		});
		tableBody += '<td>&nbsp</td></tr>';

		table = tableStart + tableHead + tableBody + tableEnd;

		return table;
	},

	initializeAdminStore: function () {
		"use strict";

		Ext.define('Admin', {
			extend: 'Ext.data.Model',
			fields: ['id', 'fullname']
		});

		Ext.create('Ext.data.Store', {
			storeId: 'AdminStore',
			model: 'Admin',
			proxy: {
				type: 'ajax',
				url: '/admin/reports/get_all_admins',
				reader: {
					type: 'json',
					root: 'admins'
				}
			}
		});
	},

	initializeKioskStore: function () {
		"use strict";

		Ext.define('Kiosk', {
			extend: 'Ext.data.Model',
			fields: ['id', 'location_id', 'location_recognition_name']
		});

		Ext.create('Ext.data.Store', {
			storeId: 'KioskStore',
			model: 'Kiosk',
			proxy: {
				type: 'ajax',
				url: '/admin/reports/get_all_kiosks',
				reader: {
					type: 'json',
					root: 'kiosks'
				}
			}
		});
	},

	initializeProgramStore: function () {
		"use strict";

		Ext.define('MasterKioskButton', {
			extend: 'Ext.data.Model',
			fields: ['id', 'name']
		});

		Ext.create('Ext.data.Store', {
			storeId: 'MasterKioskButtonStore',
			model: 'MasterKioskButton',
			proxy: {
				type: 'ajax',
				url: '/admin/reports/get_all_kiosk_buttons',
				reader: {
					type: 'json',
					root: 'kiosk_buttons'
				}
			}
		});
	},

	initializeFilterStore: function () {
		"use strict";

		Ext.define('Location', {
			extend: 'Ext.data.Model',
			fields: [{
				name: 'id'
			}, {
				name: 'name'
			}, {
				name: 'from',
				type: 'date',
				dateFormat: 'Y-m-d H:i:s'
			}, {
				name: 'from',
				type: 'date',
				dateFormat: 'Y-m-d H:i:s'
			},
				'admins',
				'kiosks',
				'locations',
				'programs']
		});

		// Ext.create('Ext.data.Store', {
		// 	storeId: 'FilterStore',
		// 	model: 'Filter',
		// 	proxy: {
		// 		api: {
		// 			create: '/admin/reports/create_filters',
		// 			read: '/admin/reports/read_filters',
		// 			destroy: '/admin/reports/destroy_filters'
		// 		},
		// 		reader: {
		// 			type: 'json',
		// 			root: 'filters'
		// 		},
		// 		type: 'ajax'
		// 	},
		// 	autoLoad: true
		// });

		Ext.create('Ext.data.ArrayStore', {
			storeId: 'PresetStore',
			fields: [
				'short',
				'long'
			],
			data: [
				[ 'today', 'Today'  ],
				[ 'lastWeek', 'Last Week' ],
				[ 'lastMonth', 'Last Month' ],
				[ 'monthToDate', 'Month to Date' ],
				[ 'yearToDate', 'Year to Date' ]
			]
		});

		Ext.create('Ext.data.ArrayStore', {
			storeId: 'ChartTypeStore',
			fields: [
				'short',
				'long'
			],
			data: [
				[ 'hourly', 'Hourly'  ],
				[ 'daily', 'Daily' ],
				[ 'weekly', 'Weekly' ],
				[ 'monthly', 'Monthly' ],
				[ 'yearly', 'Yearly' ]
			]
		});
	},

	initializeViewPort: function (fieldKeys) {
		"use strict";

		var availableDOMHeight = window.innerHeight;

		this.window = Ext.create('Ext.window.Window', {
			id: 'reportsWindow',
			title: 'Reports',
			border: false,
			closable: false,
			draggable: false,
			resizable: false,
			height: (availableDOMHeight - 100),
			layout: {
				type: 'fit',
				padding: '10 20'
			},
			maximizable: true,
			listeners: {
				show: {
					scope: this,
					fn: function (window, eOpts) {
						this.showFilterWindow();
					}
				}
			},
			tools: [{
				itemId: 'filters',
				type: 'gear',
				scope: this,
				handler: this.showFilterWindow
			}, {
				itemId: 'print',
				type: 'print',
				scope: this,
				handler: function () {
					window.print();
				}
			}],
			dockedItems: [{
				xtype: 'statusbar',
				id: 'windowStatusBar',
				text: 'Ready',
				dock: 'bottom'
			}],
			width: '99%',
			x: 7,
			y: 95,
			items: [{
				xtype: 'tabpanel',
				id: 'chartTabPanel',
				title: false,
				border: 0,
				items: [{
					xtype: 'container',
					layout: 'vbox',
					id: 'totalUnduplicated',
					title: 'Total Unduplicated Individuals',
					items: [{
						xtype: 'totalunduplicated',
						flex: 1,
						width: '100%',
						store: Ext.data.StoreManager.lookup('UnduplicatedIndividualsStore'),
						axes: [{
							type: 'Numeric',
							scope: this,
							fields: [],
							position: 'left',
							title: 'Total Unduplicated Individuals',
							minimum: 0,
							grid: true,
							label: {
								renderer: Ext.util.Format.numberRenderer('0,0')
							}
						}, {
							type: 'Category',
							position: 'bottom',
							fields: ['time']
						}],
						series: [{
							type: 'column',
							axis: 'left',
							highlight: true,
							tips: {
								trackMouse: true,
								height: 28,
								width: 150,
								renderer: function (storeItem, item) {
									this.setTitle(item.value[0] + ' &mdash; Total: ' + item.value[1]);
								}
							},
							xField: 'time',
							scope: this,
							yField: [],
							label: {
								display: 'insideEnd',
								'text-anchor': 'middle',
								field: 'total',
								renderer: Ext.util.Format.numberRenderer('0'),
								orientation: 'vertical',
								color: '#333'
							}
						}]
					}]
				}, {
					xtype: 'container',
					layout: 'vbox',
					id: 'servicesChartContainer',
					title: 'Services',
					items: [{
						xtype: 'chart',
						id: 'servicesChart',
						width: '100%',
						store: Ext.data.StoreManager.lookup('TotalServicesStore'),
	          legend: {
	            position: 'right'
	          },
						axes: [{
							type: 'Numeric',
							scope: this,
							fields: [],
							position: 'left',
							title: 'Total Services',
							minorTickSteps: 1,
							grid: {
								odd: {
									opacity: 1,
									fill: '#ddd',
									stroke: '#bbb',
									'stroke-width': 0.5
								}
							}
						}, {
							type: 'Category',
							position: 'bottom',
							fields: ['time']
						}],
						series: []
					}]
				}]
			}]
		}).show();
	},

	showFilterWindow: function () {
		"use strict";

		var activePanel = Ext.getCmp('chartTabPanel').activeTab.id,
			programsField;

		if (!this.filterWindow) {
			this.filterWindow = Ext.create('Ext.window.Window', {
				bodyPadding: '5px 10px',
				closeAction: 'hide',
				height: 375,
				hidden: true,
				layout: 'auto',
				margins: '0 0 10px',
				resizable: false,
				title: 'Filters',
				width: 815,

				items: [{
					xtype: 'fieldcontainer',
					height: 31,
					margin: '5px 0 8px',
					width: 780,
					layout: {
						type: 'column'
					},
					fieldLabel: '',
					items: [{
						xtype: 'combo',
						fieldLabel: 'Saved Presets',
						margin: '0 267px 0 0',
						store: Ext.data.StoreManager.lookup('PresetStore'),
						valueField: 'short',
						displayField: 'long',
						query: 'local',
						triggerAction: 'all',
						listeners: {
							select: {
								scope: this,
								fn: function (combo, recs, opts) {
									var record = recs[0],
										value = record.data.short,
										fromDate = Ext.getCmp('fromDate'),
										fromTime = Ext.getCmp('fromTime'),
										toDate = Ext.getCmp('toDate'),
										toTime = Ext.getCmp('toTime');

									switch (value) {
									case 'today':
										fromDate.setValue(Date.today());
										toDate.setValue(Date.today());
										break;

									case 'lastWeek':
										fromDate.setValue(Date.today().last().week().monday());
										toDate.setValue(Date.today().last().friday());
										break;

									case 'lastMonth':
										fromDate.setValue(Date.today().last().month().moveToFirstDayOfMonth());
										toDate.setValue(Date.today().last().month().moveToLastDayOfMonth());
										break;

									case 'monthToDate':
										fromDate.setValue(Date.today().moveToFirstDayOfMonth());
										toDate.setValue(Date.today());
										break;

									case 'yearToDate':
										fromDate.setValue(Date.january().moveToFirstDayOfMonth());
										toDate.setValue(Date.today());
										break;

									default:
										break;
									}
								}
							}
						}
					}, {
						xtype: 'combo',
						fieldLabel: 'Chart Breakdown',
						id: 'chartBreakdown',
						store: Ext.data.StoreManager.lookup('ChartTypeStore'),
						valueField: 'short',
						value: 'hourly',
						displayField: 'long',
						query: 'local',
						triggerAction: 'all',
						listeners: {
							select: {
								scope: this,
								fn: function (combo, recs, opts) {
									var record = recs[0],
										value = record.data.short;

									this.activeChartType = value;
								}
							}
						}
					}]
				}, {
					xtype: 'fieldset',
					height: 220,
					width: 780,
					layout: {
						type: 'column'
					},
					title: 'Filter By',
					items: [{
						id: 'location',
						xtype: 'comboboxselect',
						fieldLabel: 'Locations',
						displayField: 'name',
						valueField: 'id',
						margin: '0 50px 0 0',
						growMin: 75,
						growMax: 75,
						store: Ext.data.StoreManager.lookup('LocationStore'),
						queryMode: 'remote',
						emptyText: 'Leave blank for all locations',
						name: 'locations',
						allowBlank: true,
						width: 350
					}, {
						xtype: 'boxselect',
						id: 'program',
						width: 350,
						fieldLabel: 'Program(s)',
						displayField: 'name',
						valueField: 'id',
						growMin: 75,
						growMax: 75,
						store: Ext.data.StoreManager.lookup('MasterKioskButtonStore'),
						queryMode: 'remote',
						name: 'programs'
					}, {
						xtype: 'comboboxselect',
						id: 'kiosk',
						margin: '0 50px 0 0',
						width: 350,
						fieldLabel: 'Kiosk(s)',
						displayField: 'location_recognition_name',
						valueField: 'id',
						growMin: 75,
						growMax: 75,
						store: Ext.data.StoreManager.lookup('KioskStore'),
						queryMode: 'remote',
						emptyText: 'Leave blank for all kiosks',
						name: 'kiosks',
						allowBlank: true
					}, {
						xtype: 'comboboxselect',
						id: 'admin',
						width: 350,
						fieldLabel: 'Admin(s)',
						displayField: 'fullname',
						valueField: 'id',
						growMin: 75,
						growMax: 75,
						store: Ext.data.StoreManager.lookup('AdminStore'),
						queryMode: 'remote',
						emptyText: 'Leave blank for all admins',
						name: 'admins',
						allowBlank: true
					}, {
						xtype: 'datefield',
						id: 'fromDate',
						margin: '0 8px 0 0',
						scope: this,
						value: this.filters.fromDate,
						width: 220,
						fieldLabel: 'From'
					}, {
						xtype: 'timefield',
						id: 'fromTime',
						margin: '0 50px 0 0',
						minValue: '8:00 AM',
						maxValue: '5:00 PM',
						value: '8:00 AM',
						width: 122
					}, {
						xtype: 'datefield',
						id: 'toDate',
						margin: '0 8px 0 0',
						scope: this,
						value: this.filters.toDate,
						width: 220,
						fieldLabel: 'To'
					}, {
						xtype: 'timefield',
						id: 'toTime',
						minValue: '8:00 AM',
						maxValue: '5:00 PM',
						value: '5:00 PM',
						width: 122
					}]
				}, {
					xtype: 'checkbox',
					id: 'includeRawData',
					fieldLabel: 'Include raw data',
					margin: '0 0 10px 0'
				}, {
					xtype: 'splitbutton',
					handler: this.setFilters,
					scope: this,
					text: 'Filter',
					menu: new Ext.menu.Menu({
						items: [{
							text: 'Save this filter for later',
							icon: '/img/icons/save.png',
							scope: this,
							handler: this.saveFilter
						}]
					})
				}, {
					xtype: 'button',
					margin: '0 0 0 12px',
					text: 'Reset Filters',
					scope: this,
					handler: this.resetFilters
				}]
			});
		}
		
		programsField = this.filterWindow.down('#program');
		
		if (activePanel === 'servicesChartContainer') {
			console.log(programsField);
			programsField.emptyText = 'Please select at least one program';
			programsField.allowBlank = false;
			programsField.reset();
		} else {
			programsField.emptyText = 'Leave blank for all programs';
			programsField.allowBlank = true;
			programsField.reset();
		}

		this.filterWindow.show();
	},

	saveFilters: function () {
		"use strict";
	},

	resetFilters: function () {
		"use strict";
	},

	setFilters: function () {
		"use strict";

		var filterValues = this.getFilterValues(),
			reportsWindow = Ext.getCmp('reportsWindow'),
			chartContainer = Ext.getCmp('servicesChartContainer'),
			windowTitle,
			titleFromDate,
			titleToDate,
			locationMeta,
			programMeta,
			adminMeta,
			kioskMeta,
			statusMeta,
			store;
			
		if (this.filterWindow.down('#program').isValid()) {			
			if (filterValues.fromDate) {
				filterValues.fromDate = Ext.Date.format(filterValues.fromDate, 'Y-m-d');
			}

			if (filterValues.fromTime) {
				filterValues.fromTime = Ext.Date.format(filterValues.fromTime, 'H:i:s');
			}

			if (filterValues.toDate) {
				filterValues.toDate = Ext.Date.format(filterValues.toDate, 'Y-m-d');
			}

			if (filterValues.toTime) {
				filterValues.toTime = Ext.Date.format(filterValues.toTime, 'H:i:s');
			}

			this.selectedLocations = filterValues.location || this.filters.location;

			this.filters = {
				admin: filterValues.admin || this.filters.admin,
				fromDate: filterValues.fromDate || this.filters.fromDate,
				fromTime: filterValues.fromTime || this.filters.fromTime,
				kiosk: filterValues.kiosk || this.filters.kiosk,
				location: filterValues.location || this.filters.location,
				program: filterValues.program || this.filters.program,
				toDate: filterValues.toDate || this.filters.toDate,
				toTime: filterValues.toTime || this.filters.toTime
			};

			titleFromDate = Ext.Date.format(Date.parse(this.filters.fromDate + ' ' + this.filters.fromTime), 'm/d/Y ga');
			titleToDate = Ext.Date.format(Date.parse(this.filters.toDate + ' ' + this.filters.toTime), 'm/d/Y ga');
			windowTitle = titleFromDate + ' &mdash; ' + titleToDate;
			
			if (!Ext.isEmpty(this.filters.location)) {
				store = Ext.StoreManager.lookup('LocationStore');
				locationMeta = '';
				
				Ext.Array.each(this.filters.location, function (item) {
					var loc = store.getAt(store.find('id', item));
					locationMeta += loc.data.name + ', ';
				});
				locationMeta = locationMeta.slice(0, -2);
			}
			
			if (!Ext.isEmpty(this.filters.program)) {
				store = Ext.StoreManager.lookup('ServiceStore');
				programMeta = '';
				
				Ext.Array.each(this.filters.program, function (item) {
					var prog = store.getAt(store.find('id', item));
					programMeta += prog.data.name + ', ';
				});
				programMeta = programMeta.slice(0, -2);
			}
			
			if (!Ext.isEmpty(this.filters.admin)) {
				store = Ext.StoreManager.lookup('AdminStore');
				adminMeta = '';
				
				Ext.Array.each(this.filters.admin, function (item) {
					var admin = store.getAt(store.find('id', item));
					adminMeta += admin.data.fullname + ', ';
				});
				adminMeta = adminMeta.slice(0, -2);
			}
			
			if (!Ext.isEmpty(this.filters.kiosk)) {
				store = Ext.StoreManager.lookup('KioskStore');
				kioskMeta = '';
				
				Ext.Array.each(this.filters.kiosk, function (item) {
					var kiosk = store.getAt(store.find('id', item));
					kioskMeta += kiosk.data.location_recognition_name + ', ';
				});
				kioskMeta = kioskMeta.slice(0, -2);
			}
			
			statusMeta = "<strong>Program(s)</strong>: " + programMeta + " &mdash; ";
			statusMeta += "<strong>Location(s)</strong>: " + locationMeta + " &mdash; ";
			statusMeta += "<strong>Kiosk(s)</strong>: " + kioskMeta + " &mdash; ";
			statusMeta += "<strong>Admin(s)</strong>: " + adminMeta;
			
			reportsWindow.setTitle('Report: ' + windowTitle);
			Ext.getCmp('windowStatusBar').setStatus(statusMeta);

			this.sendRequest();
			this.filterWindow.hide();
		}
	},

	getFilterValues: function () {
		"use strict";

		var filterFieldSet = this.filterWindow.down('fieldset'),
			chartBreakdown = Ext.getCmp('chartBreakdown'),
			filterValues = {};

		Reports.activeChartType = chartBreakdown.getValue();

		Ext.Object.each(filterFieldSet.items.items, function (key, value, me) {
			filterValues[value.id] = value.getValue();
		});

		return filterValues;
	},

	buildRequestParams: function () {
		"use strict";

		var requestParams = {};

		Ext.Object.each(this.filters, function (key, value, obj) {
			if (obj[key] && key !== 'updateInterval') {
				requestParams[key] = value;
			}
		});

		return requestParams;
	},

	sendRequest: function () {
		"use strict";

		var requestParams = this.buildRequestParams(),
			chartType = this.activeChartType,
			activePanel = Ext.getCmp('chartTabPanel').activeTab.id,
			url,
			storeId;

		Ext.getCmp(activePanel).setLoading('Loading Report...');

		switch (activePanel) {
		case 'totalUnduplicated':
			url = '/admin/reports/total_unduplicated_individuals';
			storeId = 'UnduplicatedIndividualsStore';
			break;

		case 'servicesChartContainer':
			url = '/admin/reports/total_services';
			storeId = 'TotalServicesStore';
			break;

		default:
			break;
		}

		Ext.Ajax.request({
			url: url,
			params: {
				filters: Ext.encode(requestParams),
				chartType: chartType
			},
      scope: this,
			success: function (response) {
				Ext.getCmp(activePanel).setLoading(false);
			  var jsonStr = response.responseText,
					store = Ext.data.StoreManager.lookup(storeId);

				store.loadData(this.parseResponse(jsonStr));
			}
		});
	},

  parseResponse: function (obj) {
		"use strict";

    var data = Ext.JSON.decode(obj),
      retVal = [];
		
		Ext.Object.each(data, function (key, value, me) {
			var time = key,
				data,
				tmp,
				tmpKeys = Ext.Object.getKeys(value),
				tmpKeysLength = tmpKeys.length;
			
			tmp = new Object;
			tmp.time = time;
			
			for (var i=0; i < tmpKeysLength; i++) {
				tmp[tmpKeys[i]] = value[tmpKeys[i]];
			};
			
      retVal.push(tmp);
    });

    return retVal;
  }
};

Ext.onReady(Reports.initialize, Reports);