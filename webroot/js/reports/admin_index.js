var Reports;
Reports = {
  activeChartType: null, // can be hourly, daily, weekly, monthly
  filterWindow: null,
	groupBy: null,
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
    this.groupBy = 'hour';

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

    Ext.define('Filter', {
      extend: 'Ext.data.Model',
      fields: [
        { name: 'id', type: 'int' },
        'name',
        'date_range',
				'chart_breakdown',
				'group_by',
				'display_as',
        'admin',
        'kiosk',
        'location',
        'program'
      ]
    });

    Ext.create('Ext.data.Store', {
      storeId: 'FilterStore',
      model: 'Filter',
      proxy: {
        type: 'ajax',
        reader: {
          root: 'report_filters'
        },
        writer: {
          root: 'report_filters',
          encode: true,
          writeAllFields: true
        },
        api: {
          create: '/admin/reports/create_filter',
          read: '/admin/reports/read_filters',
          update: '/admin/reports/update_filter',
          destroy: '/admin/reports/destroy_filter'
        }
      },
      autoLoad: true
    });

		Ext.create('Ext.data.ArrayStore', {
			storeId: 'DateRangeStore',
			fields: [
				'short',
				'long'
			],
			data: [
				[ 'today', 'Today' ],
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
		
		Ext.create('Ext.data.ArrayStore', {
			storeId: 'GroupByStore',
			fields: [
				'short',
				'long'
			],
			data: [
				[ 'hour', 'Hour'  ],
				[ 'day', 'Day' ],
				[ 'week', 'Week' ],
				[ 'month', 'Month' ]
			]
		});
	},

	initializeViewPort: function (fieldKeys) {
		"use strict";

		var availableDOMHeight = function () {
      if (typeof window.innerHeight !== 'undefined') {
        return window.innerHeight - 160;
      } else if (typeof document.documentElement !== 'undefined' 
							&& typeof document.documentElement.clientHeight !== 'undefined') {
        return document.documentElement.clientHeight - 100;
      } else {
        return document.getElementsByTagName('body')[0].clientHeight - 100;
      }
    };

		this.window = Ext.create('Ext.window.Window', {
			id: 'reportsWindow',
			title: 'Reports',
			border: false,
			closable: false,
			draggable: false,
			resizable: false,
			height: availableDOMHeight(),
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
				tooltip: 'Chart Filters',
				scope: this,
				handler: this.showFilterWindow
			}, {
				itemId: 'refresh',
				type: 'refresh',
				tooltip: 'Reset Zoom',
				scope: this,
				handler: function () {
					Ext.getCmp('totalUnduplicated').items.items[0].restoreZoom();
				}
			}, {
				itemId: 'print',
				type: 'print',
				tooltip: 'Print This Page',
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
			y: 155,
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
		        mask: 'horizontal',
		        listeners: {
		            select: {
		                fn: function(me, selection) {
		                    me.setZoom(selection);
		                    me.mask.hide();
		                }
		            }
		        },
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
								height: 40,
                width: 'auto',
								renderer: function (storeItem, item) {
                  this.setTitle(item.value[0]);
                  this.update('Total: ' + item.value[1]);
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
				height: 394,
				hidden: true,
				layout: 'auto',
				margins: '0 0 10px',
				resizable: false,
				title: 'Filters',
				width: 815,

				items: [{
					xtype: 'fieldcontainer',
					height: 55,
					margin: '5px 0 8px',
					width: 780,
					layout: {
						type: 'column'
					},
					fieldLabel: '',
					items: [{
						xtype: 'combo',
						id: 'presets',
						fieldLabel: 'Saved Presets',
						margin: '0 222px 0 0',
						store: Ext.data.StoreManager.lookup('FilterStore'),
						valueField: 'id',
						displayField: 'name',
						width: 300,
						query: 'remote',
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
										toTime = Ext.getCmp('toTime'),
										location = Ext.getCmp('location'),
										program = Ext.getCmp('program'),
										kiosk = Ext.getCmp('kiosk'),
										admin = Ext.getCmp('admin'),
										chartBreakdown = Ext.getCmp('chartBreakdown'),
										groupBy = Ext.getCmp('groupBy'),
                    dateRange = Ext.getCmp('dateRange');

                  dateRange.select(record.data.date_range);

                  switch(record.data.date_range) {
                    case 'today':
                      fromDate.reset();
                      fromTime.reset();
                      toDate.reset();
                      toTime.reset();
                      break;

                    case 'lastWeek':
                      fromDate.setValue(Date.today().last().week().monday());
                      fromTime.reset();
                      toDate.setValue(Date.today().last().friday());
                      toTime.reset();
                      break;

                    case 'lastMonth':
                      fromDate.setValue(Date.today().last().month().moveToFirstDayOfMonth());
                      fromTime.reset();
                      toDate.setValue(Date.today().last().month().moveToLastDayOfMonth());
                      toTime.reset();
                      break;

                    case 'monthToDate':
                      fromDate.setValue(Date.today().moveToFirstDayOfMonth());
                      fromTime.reset();
                      toDate.setValue(Date.today());
                      toTime.reset();
                      break;

                    case 'yearToDate':
                      fromDate.setValue(Date.january().moveToFirstDayOfMonth());
                      fromTime.reset();
                      toDate.setValue(Date.today());
                      toTime.reset();
                      break;

                    default:
                      break;
                  }

									chartBreakdown.setValue(record.data.chart_breakdown);
									groupBy.setValue(record.data.group_by);
                  location.setValue(record.data.location);
                  program.setValue(record.data.program);
                  kiosk.setValue(record.data.kiosk);
                  admin.setValue(record.data.admin);

                  Ext.getCmp('saveFilterMenuItem').disable().hide();
                  Ext.getCmp('updateFilterMenuItem').enable().show();
                  Ext.getCmp('deleteFilterMenuItem').enable();
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
					}, {
            xtype: 'combo',
            fieldLabel: 'Date Range',
            id: 'dateRange',
            margin: '0 222px 0 0',
            width: 300,
            store: Ext.data.StoreManager.lookup('DateRangeStore'),
            valueField: 'short',
            value: 'today',
            displayField: 'long',
            query: 'local',
            triggerAction: 'all',
            listeners: {
              select: {
                scope: this,
                fn: function (combo, records, opts) {

                  console.log('selected!');
                  var rec = records[0],
                    fromDate = Ext.getCmp('fromDate'),
                    fromTime = Ext.getCmp('fromTime'),
                    toDate = Ext.getCmp('toDate'),
                    toTime = Ext.getCmp('toTime');

                  switch(rec.data.short) {
                    case 'today':
                      fromDate.reset();
                      fromTime.reset();
                      toDate.reset();
                      toTime.reset();
                      break;

                    case 'lastWeek':
                      fromDate.setValue(Date.today().last().week().monday());
                      fromTime.reset();
                      toDate.setValue(Date.today().last().friday());
                      toTime.reset();
                      break;

                    case 'lastMonth':
                      fromDate.setValue(Date.today().last().month().moveToFirstDayOfMonth());
                      fromTime.reset();
                      toDate.setValue(Date.today().last().month().moveToLastDayOfMonth());
                      toTime.reset();
                      break;

                    case 'monthToDate':
                      fromDate.setValue(Date.today().moveToFirstDayOfMonth());
                      fromTime.reset();
                      toDate.setValue(Date.today());
                      toTime.reset();
                      break;

                    case 'yearToDate':
                      fromDate.setValue(Date.january().moveToFirstDayOfMonth());
                      fromTime.reset();
                      toDate.setValue(Date.today());
                      toTime.reset();
                      break;

                    default:
                      break;
                  }
                }
              }
            }
          }, {
						xtype: 'combo',
						fieldLabel: 'Group By',
						id: 'groupBy',
						store: Ext.data.StoreManager.lookup('GroupByStore'),
						valueField: 'short',
						value: 'hour',
						displayField: 'long',
						query: 'local',
						triggerAction: 'all',
						listeners: {
							select: {
								scope: this,
								fn: function (combo, recs, opts) {
									var record = recs[0],
										value = record.data.short;

									this.groupBy = value;
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
              id: 'saveFilterMenuItem',
							text: 'Save this filter',
							icon: '/img/icons/save.png',
							scope: this,
							handler: this.saveFilter
						}, {
              id: 'updateFilterMenuItem',
              text: 'Update this filter',
              icon: '/img/icons/save.png',
              scope: this,
              handler: this.updateFilter,
              hidden: true
            }, {
              id: 'deleteFilterMenuItem',
							text: 'Delete this filter',
							icon: '/img/icons/delete.png',
							scope: this,
							handler: this.deleteFilter,
              disabled: true
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

	saveFilter: function () {
		"use strict";

		var filterFieldSet = this.filterWindow.down('fieldset'),
			chartBreakdown = Ext.getCmp('chartBreakdown'),
      dateRange = Ext.getCmp('dateRange'),
      groupBy = Ext.getCmp('groupBy'),
      recordData = {},
      store = Ext.data.StoreManager.lookup('FilterStore');

    Ext.MessageBox.prompt('Save Preset', 'Enter a name for your filter preset', function (btn, text) {
      if (btn === 'ok' && !Ext.isEmpty(text)) {
        recordData['name'] = text;

        Ext.Object.each(filterFieldSet.items.items, function (key, value, me) {
          var val = value.id;

          if (val !== 'fromDate' && val !== 'fromTime' && val !== 'toDate' && val !== 'toTime') {
            recordData[value.id] = value.getValue();
          }
        });

        recordData['chart_breakdown'] = chartBreakdown.getValue();
        recordData['group_by'] = groupBy.getValue();
        recordData['date_range'] = dateRange.getValue();
      }

      store.add(recordData);
      store.sync();
    });
	},

  updateFilter: function () {
		"use strict";

		var filterFieldSet = this.filterWindow.down('fieldset'),
			chartBreakdown = Ext.getCmp('chartBreakdown'),
      dateRange = Ext.getCmp('dateRange'),
      groupBy = Ext.getCmp('groupBy'),
      recordData = {},
      store = Ext.data.StoreManager.lookup('FilterStore'),
      recordId = Ext.getCmp('presets').getValue(),
      record = store.getById(recordId);

      Ext.Object.each(filterFieldSet.items.items, function (key, value, me) {
        var val = value.id;

        if (val !== 'fromDate' && val !== 'fromTime' && val !== 'toDate' && val !== 'toTime') {
          record.set(value.id, value.getValue());
        }
      });

      record.set('chart_breakdown', chartBreakdown.getValue());
      record.set('group_by', groupBy.getValue());
      record.set('date_range', dateRange.getValue());

      store.sync();
  },
	
	deleteFilter: function () {
		"use strict";
		
		var recordId = Ext.getCmp('presets').getValue(),
      store = Ext.data.StoreManager.lookup('FilterStore'),
      recordIndex = store.find('id', recordId);
		
		Ext.MessageBox.confirm('Are you sure?', 'Are you sure you want to delete this preset filter?', function (btn) {
			if (btn === 'yes') {
        store.removeAt(recordIndex);
        store.sync();
			}
		});

    Ext.getCmp('deleteFilterMenuItem').disable();
    this.resetFilters();
	},

	resetFilters: function () {
		"use strict";
		
		var fromDate = Ext.getCmp('fromDate'),
			fromTime = Ext.getCmp('fromTime'),
			toDate = Ext.getCmp('toDate'),
			toTime = Ext.getCmp('toTime'),
			location = Ext.getCmp('location'),
			program = Ext.getCmp('program'),
			kiosk = Ext.getCmp('kiosk'),
			admin = Ext.getCmp('admin'),
			chartBreakdown = Ext.getCmp('chartBreakdown'),
			groupBy = Ext.getCmp('groupBy'),
			presets = Ext.getCmp('presets'),
      dateRange = Ext.getCmp('dateRange');
		
		presets.reset();
		fromDate.reset();
		fromTime.reset();
		toDate.reset();
		toTime.reset();
		location.reset();
		program.reset();
		kiosk.reset();
		admin.reset();
		chartBreakdown.reset();
		groupBy.reset();
    dateRange.reset();

    Ext.getCmp('saveFilterMenuItem').enable().show();
    Ext.getCmp('updateFilterMenuItem').disable().hide();
    Ext.getCmp('deleteFilterMenuItem').disable();
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

      programMeta = (Ext.isEmpty(programMeta)) ? 'All' : programMeta;
      locationMeta = (Ext.isEmpty(locationMeta)) ? 'All' : locationMeta;
      kioskMeta = (Ext.isEmpty(kioskMeta)) ? 'All' : kioskMeta;
      adminMeta = (Ext.isEmpty(adminMeta)) ? 'All' : adminMeta;

			
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
			groupBy = this.groupBy,
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
				chartType: chartType,
				groupBy: groupBy
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
