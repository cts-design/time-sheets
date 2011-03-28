App = function() {
    return {
        init : function() {
            Ext.QuickTips.init();
            Ext.BLANK_IMAGE_URL = '/js/ext/resources/images/default/s.gif';

            // this will hold the event categories
            this.calendarStore = new Ext.data.JsonStore({
                url: '/admin/event_categories/get_all_categories',
                storeId: 'calendarStore',
                idProperty: 'id',
                root: 'eventCategories',
                autoLoad: true,
                fields: [
                    {name:'CalendarId', mapping: 'id', type: 'int'},
                    {name:'Title', mapping: 'name', type: 'string'}
                ],
                sortInfo: {
                    field: 'CalendarId',
                    direction: 'ASC'
                }
            });

			var eventProxy = new Ext.data.HttpProxy({
				api: {
					create:  { url: '/admin/events/create',  method: 'POST' },
		   			read:    { url: '/admin/events/read',    method: 'GET'  },
					update:  { url: '/admin/events/update',  method: 'POST' },
					destroy: { url: '/admin/events/destroy', method: 'POST' }	
				}
			});
			
			var reader = new Ext.data.JsonReader({
        		totalProperty: 'total_events',
        		root: 'events',
        		fields: [
        			{name: 'EventId', mapping: 'id', type: 'int'},
        			{name: 'CalendarId', mapping: 'event_category_id', type: 'int'},
        			{name: 'Title', mapping: 'title'},
        			{name: 'StartDate', mapping: 'start', type: 'date', dateFormat: 'Y-m-d H:i:s'},
        			{name: 'EndDate', mapping: 'end', type: 'date', dateFormat: 'Y-m-d H:i:s'},
        			{name: 'IsAllDay', mapping: 'all_day', type: 'int'},
        			{name: 'Location', mapping: 'location'},
        			{name: 'Notes', mapping: 'description'},
        			{name: 'Url', mapping: 'event_url'}
        		]
            });
			
			var writer = new Ext.data.JsonWriter({
			    encode: true
			});
			
            this.eventStore = new Ext.data.Store({
            	proxy: eventProxy,
            	reader: reader,
            	writer: writer,
            	autoLoad: true,
            	listeners: {
            		write: function(store, action, result, res, rs) {

            		}
            	}
            });
			
		    new Ext.Panel({
		    	layout: 'border',
		    	border: false,
		    	height: 900,
		    	renderTo: 'calendar-div',
		    	defaults: {
		    		collapsible: false,
		    		split: false
		    	},
		    	items: [{
		    		id: 'app-center',
                    title: '...', // will be updated to view date range
                    region: 'center',
                    layout: 'border',
                    items: [{
                        id:'app-west',
                        region: 'west',
                        width: 176,
                        border: false,
                        items: [{
                            xtype: 'datepicker',
                            id: 'app-nav-picker',
                            cls: 'ext-cal-nav-picker',
                            listeners: {
                                'select': {
                                    fn: function(dp, dt){
                                        App.calendarPanel.setStartDate(dt);
                                    },
                                    scope: this
                                }
                            }
                        }]
                    },
                    {
                    	xtype: 'calendarpanel',
			    		region: 'center',
	                    eventStore: this.eventStore,
	                    calendarStore: this.calendarStore,
	                    id:'app-calendar',
	                    activeItem: 2, // month view
	                    
	                    // CalendarPanel supports view-specific configs that are passed through to the 
	                    // underlying views to make configuration possible without explicitly having to 
	                    // create those views at this level:
	                    monthViewCfg: {
	                        showHeader: true,
	                        showWeekLinks: true,
	                        showWeekNumbers: true
	                    },
	                    
	                    // Some optional CalendarPanel configs to experiment with:
	                    //showDayView: false,
	                    //showWeekView: false,
	                    //showMonthView: false,
	                    //showNavBar: false,
	                    //showTodayText: false,
	                    //showTime: false,
	                    //title: 'Calendar of Events', // the header of the calendar, could be a subtitle for the app
	                    
	                    // Once this component inits it will set a reference to itself as an application
	                    // member property for easy reference in other functions within App.
	                    initComponent: function() {
	                        App.calendarPanel = this;
	                        this.constructor.prototype.initComponent.apply(this, arguments);
	                    },
	                    
	                    listeners: {
	                        'eventclick': {
	                            fn: function(vw, rec, el){
	                                //console.log('EVENTCLICK');
	                                this.showEditWindow(rec, el);
	                                this.clearMsg();
	                            },
	                            scope: this
	                        },
	                        'eventover': function(vw, rec, el){
	                            ////console.log('Entered evt rec='+rec.data.Title+', view='+ vw.id +', el='+el.id);
	                        },
	                        'eventout': function(vw, rec, el){
	                            ////console.log('Leaving evt rec='+rec.data.Title+', view='+ vw.id +', el='+el.id);
	                        },
	                        'eventadd': {
	                            fn: function(cp, rec){
	                            	//console.log('EVENTADD');
	                                rec.commit();
									this.showMsg('Event '+ rec.data.Title +' was added');

	                            },
	                            scope: this
	                        },
	                        'eventupdate': {
	                            fn: function(cp, rec){
	                            	//console.log('EVENTUPDATE');
	                                this.showMsg('Event '+ rec.data.Title +' was updated');
	                            },
	                            scope: this
	                        },
	                        'eventdelete': {
	                            fn: function(cp, rec){
	                            	//console.log('EVENTDELETE');
	                                this.showMsg('Event '+ rec.data.Title +' was deleted');
	                            },
	                            scope: this
	                        },
	                        'eventcancel': {
	                            fn: function(cp, rec){
	                            	//console.log('EVENTCANCEL');
	                                // edit canceled
	                            },
	                            scope: this
	                        },
	                        'viewchange': {
	                            fn: function(p, vw, dateInfo){
	                            	//console.log('VIEWCHANGE');
	                                if(this.editWin){
	                                    this.editWin.hide();
	                                };
	                                if(dateInfo !== null){
	                                    // will be null when switching to the event edit form so ignore
	                                    Ext.getCmp('app-nav-picker').setValue(dateInfo.activeDate);
	                                    this.updateTitle(dateInfo.viewStart, dateInfo.viewEnd);
	                                }
	                            },
	                            scope: this
	                        },
	                        'dayclick': {
	                            fn: function(vw, dt, ad, el){
	                            	//console.log('DAYCLICK');
	                                this.showEditWindow({
	                                    StartDate: dt,
	                                    IsAllDay: ad
	                                }, el);
	                                this.clearMsg();
	                            },
	                            scope: this
	                        },
	                        'rangeselect': {
	                            fn: function(win, dates, onComplete){
	                            	//console.log('RANGESELECT');
	                                this.showEditWindow(dates);
	                                this.editWin.on('hide', onComplete, this, {single:true});
	                                this.clearMsg();
	                            },
	                            scope: this
	                        },
	                        'eventmove': {
	                            fn: function(vw, rec){
	                            	//console.log('EVENTMOVE');
	                                rec.commit();
	                                var time = rec.data.IsAllDay ? '' : ' \\a\\t g:i a';
	                                this.showMsg('Event '+ rec.data.Title +' was moved to '+rec.data.StartDate.format('F jS'+time));
	                            },
	                            scope: this
	                        },
	                        'eventresize': {
	                            fn: function(vw, rec){
	                            	//console.log('EVENTRESIZE');
	                                rec.commit();
	                                this.showMsg('Event '+ rec.data.Title +' was updated');
	                            },
	                            scope: this
	                        },
	                        'eventdelete': {
	                            fn: function(win, rec){
	                            	//console.log('EVENTDELETE');
	                                this.eventStore.remove(rec);
	                                this.showMsg('Event '+ rec.data.Title +' was deleted');
	                            },
	                            scope: this
	                        },
	                        'initdrag': {
	                            fn: function(vw){
	                            	//console.log('INITDRAG');
	                                if(this.editWin && this.editWin.isVisible()){
	                                    this.editWin.hide();
	                                }
	                            },
	                            scope: this
	                        }
	                    }
                    }]
		    	},
		    	{
		    		
		    	}]
		    });
		    
            
        },
        
        // The edit popup window is not part of the CalendarPanel itself -- it is a separate component.
        // This makes it very easy to swap it out with a different type of window or custom view, or omit
        // it altogether. Because of this, it's up to the application code to tie the pieces together.
        // Note that this function is called from various event handlers in the CalendarPanel above.
		showEditWindow : function(rec, animateTarget){
	        if(!this.editWin){
	            this.editWin = new Ext.calendar.EventEditWindow({
                    calendarStore: this.calendarStore,
					listeners: {
						'eventadd': {
							fn: function(win, rec){
								//console.log('EVENTADD');
								win.hide();
								rec.data.IsNew = false;
								this.eventStore.add(rec);
                                this.showMsg('Event '+ rec.data.Title +' was added');
							},
							scope: this
						},
						'eventupdate': {
							fn: function(win, rec){
								//console.log('EVENTUPDATE');
								win.hide();
								rec.commit();
                                this.showMsg('Event '+ rec.data.Title +' was updated');
							},
							scope: this
						},
						'eventdelete': {
							fn: function(win, rec){
								//console.log('deleted! from showeditwindow');
								this.eventStore.remove(rec);
								win.hide();
                                this.showMsg('Event '+ rec.data.Title +' was deleted');
							},
							scope: this
						},
                        'editdetails': {
                            fn: function(win, rec){
                            	//console.log('EVENTDETAILS');
                                win.hide();
                                App.calendarPanel.showEditForm(rec);
                            }
                        }
					}
                });
	        }
	        this.editWin.show(rec, animateTarget);
		},
        
        // The CalendarPanel itself supports the standard Panel title config, but that title
        // only spans the calendar views.  For a title that spans the entire width of the app
        // we added a title to the layout's outer center region that is app-specific. This code
        // updates that outer title based on the currently-selected view range anytime the view changes.
        updateTitle: function(startDt, endDt){
            var p = Ext.getCmp('app-center');
            
            if(startDt.clearTime().getTime() == endDt.clearTime().getTime()){
                p.setTitle(startDt.format('F j, Y'));
            }
            else if(startDt.getFullYear() == endDt.getFullYear()){
                if(startDt.getMonth() == endDt.getMonth()){
                    p.setTitle(startDt.format('F j') + ' - ' + endDt.format('j, Y'));
                }
                else{
                    p.setTitle(startDt.format('F j') + ' - ' + endDt.format('F j, Y'));
                }
            }
            else{
                p.setTitle(startDt.format('F j, Y') + ' - ' + endDt.format('F j, Y'));
            }
        },
        
        // This is an application-specific way to communicate CalendarPanel event messages back to the user.
        // This could be replaced with a function to do "toast" style messages, growl messages, etc. This will
        // vary based on application requirements, which is why it's not baked into the CalendarPanel.
        showMsg: function(msg){
            Ext.fly('app-msg').update(msg).removeClass('x-hidden');
        },
        
        clearMsg: function(){
            Ext.fly('app-msg').update('').addClass('x-hidden');
        }
    }
}();

Ext.onReady(App.init, App);

