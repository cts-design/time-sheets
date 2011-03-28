/**
 * @author Brandon Cordell
 * @copyright Complete Technology Solutions 2011
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
 
// overwrite the console.log function so browsers without firebug
// wont stop execution upon finding the function
if (typeof console == "undefined") {
    window.console = {
        log: function () {}
    };
}

CareerSeekers = function() {};

CareerSeekers.prototype = {
<<<<<<< HEAD
	init: function() {
		
		Ext.QuickTips.init();
		Ext.BLANK_IMAGE_URL = '/img/ext/default/s.gif';
		
		this.initData();
		this.initGrid();

		this.store.load();
		this.grid.render('surveys');
	},
	initData: function() {
		var Survey = Ext.data.Record.create([
			{ name: 'full_name', type: 'string', mapping: 'answers.first_name + " " + obj.answers.last_name' },
			'answers',
			{ name: 'created', type: 'date', dateFormat: 'Y-m-d H:i:s' }
		]);
		
		var proxy = new Ext.data.HttpProxy({
			api: {
				create:  { url: '/admin/career_seekers_surveys/create',  method: 'POST' },
				read:    { url: '/admin/career_seekers_surveys/read',    method: 'GET'  },
				update:  { url: '/admin/career_seekers_surveys/update',  method: 'POST' },
				destroy: { url: '/admin/career_seekers_surveys/destroy', method: 'POST' }
			}
		});
		
		var reader = new Ext.data.JsonReader({
			root: 'surveys'
		}, Survey);
		
		var writer = new Ext.data.JsonWriter({
			encode: true
		});
		
		this.store = new Ext.data.Store({
			proxy: proxy,
			reader: reader, 
			writer: writer
		});		
	},
	initGrid: function() {
		this.gridToolbar = new Ext.Toolbar({
			items: [{
				id: 'deletebtn',
				text: 'Delete Survey',
				icon: '/img/icons/delete.png',
				tooltip: 'Delete the selected survey',
				disabled: true,
				scope: this,
				handler: function() {
					this.deleteSurvey(this.selectedRecord)
				}
			}]
		});
		
		this.grid = new Ext.grid.GridPanel({
			store: this.store,
			tbar: this.gridToolbar,
			width: '100%',
			height: 300,
			title: 'Career Seekers',
			sm: new Ext.grid.RowSelectionModel({
				singleSelect: true,
				listeners: {
					rowselect: {
						fn: function(sm, ri, rec) {
							this.selectedRecord = rec;
							Ext.getCmp('deletebtn').enable();
						},
						scope: this
					}
				}
			}),
			cm: new Ext.grid.ColumnModel({
				defaults: {
					sortable: true
				},
				columns: [{
					header: 'Career Seekers Name',
					dataIndex: 'full_name',
					renderer: function(value, metaData, record, rowIndex, colIndex, store) {
						// anonymous surveys will have one space as the value
						if (value === " ") {
							return 'Anonymous';
						}
						
						return value;
					}
				},{
					header: 'Submitted',
					dataIndex: 'created',
					xtype: 'datecolumn',
					format: 'm/d/Y h:i A',
					width: 50
				}]
			}),
			viewConfig: {
				forceFit: true
			},
			listeners: {
				rowdblclick: {
					fn: function(g,ri,e) {
						this.showWindow();
					},
					scope: this
				}
			}
		});
	},
	initWindow: function(rec) {
		console.log(rec.data);
		
		var windowTpl = new Ext.XTemplate(
			'<div class="x-careerseeker">',
				'<tpl for="answers">',
				'<div class="x-careerseeker-row">',
					'<p class="label left">First Name:</p>',
					'<p class="left">{first_name}</p>',
					'<br class="clear" />',
				'</div>',
				'<div class="x-careerseeker-row">',
					'<p class="label left">Last Name:</p>',
					'<p class="left">{last_name}</p>',
					'<br class="clear" />',
				'</div>',
				'<div class="x-careerseeker-row">',
					'<p class="label left">Date you worked with the Business Services Team or visited the Website:</p>',
					'<p class="left">',
					'<tpl for="date_you_worked_with_the_business_services_team_or_the_website">',
					'{month}/{day}/{year}',
					'</tpl>',
					'</p>',
					'<br class="clear" />',
				'</div>',
				'<div class="x-careerseeker-row">',
					'<p class="label left">Are your comments related to the Business Services Team or the website:</p>',
					'<p class="left">{are_your_comments_related_to_the_business_services_team_or_the_website}</p>',
					'<br class="clear" />',
				'</div>',
				'<div class="x-careerseeker-row">',
					'<p class="label left">Please enter the name(s) of staff (if any) who assisted you:</p>',
					'<p class="left">{names_of_staff_who_assisted_you}</p>',
					'<br class="clear" />',
				'</div>',
				'<div class="x-careerseeker-row">',
					'<p class="label left">Overall, how satisfied are you with the services you received from the office or website you selected above:</p>',
					'<p class="left">{overall_how_satisfied_are_you_with_the_services_you_received}</p>',
					'<br class="clear" />',
				'</div>',
				'<div class="x-careerseeker-row">',
					'<p class="label left">Think about what you expected from the office or website you visited. How well did the services you received meet your expectations:</p>',
					'<p class="left">{think_about_what_you_expected}</p>',
					'<br class="clear" />',
				'</div>',
				'<div class="x-careerseeker-row">',
					'<p class="label left">Think about the ideal services for other people in your circumstances. How well did the services you received from the office you visited or WorkNet Pinellas website compare to your ideal:</p>',
					'<p class="left">{think_about_the_ideal_services_for_other_people}</p>',
					'<br class="clear" />',
				'</div>',
				'<div class="x-careerseeker-row">',
					'<p class="label left">What programs are you currently participating in:</p>',
					'<p class="left">',
						'<ul>',
						'<tpl if="food_stamps == 1">',
							'<li>Food Stamps (FSET)</li>',
						'</tpl>',
						'<tpl if="unemployment_comp == 1">',
							'<li>Unemployment Compensation</li>',
						'</tpl>',
						'<tpl if="wia == 1">',
							'<li>WIA</li>',
						'</tpl>',
						'<tpl if="vocational_rehab == 1">',
							'<li>Vocational Rehabilitation</li>',
						'</tpl>',
						'<tpl if="welfare_transition == 1">',
							'<li>Veterans Services</li>',
						'</tpl>',
						'<tpl if="universal_services == 1">',
							'<li>Universal Services (Resource Room, Job Search)</li>',
						'</tpl>',
						'</ul>',
					'</p>',
					'<br class="clear" />',
				'</div>',
				'<div class="x-careerseeker-row">',
					'<p class="label left">How did you learn about us (please choose one):</p>',
					'<p class="left">{how_did_you_learn}</p>',
					'<br class="clear" />',
				'</div>',
				'<div class="x-careerseeker-row">',
					'<p class="label left">If you chose "Other" above, please elaborate, if possible:</p>',
					'<p class="left">{if_you_chose_Other}</p>',
					'<br class="clear" />',
				'</div>',
				'<div class="x-careerseeker-row">',
					'<p class="label left">Industry:</p>',
					'<p class="left">{industry}</p>',
					'<br class="clear" />',
				'</div>',
				'<div class="x-careerseeker-row">',
					'<p class="label left">Gender:</p>',
					'<p class="left">{gender}</p>',
					'<br class="clear" />',
				'</div>',
				'<div class="x-careerseeker-row">',
					'<p class="label left">Age:</p>',
					'<p class="left">{age}</p>',
					'<br class="clear" />',
				'</div>',
				'<div class="x-careerseeker-row">',
					'<p class="label left">Highest Level of Education Attained:</p>',
					'<p class="left">{highest_level_of_education_attained}</p>',
					'<br class="clear" />',
				'</div>',
				'<div class="x-careerseeker-row">',
					'<p class="label left">Please share any comments or suggestions you have regarding our Business Services or the website. Please enter your name, phone number and email address here, if you would like our staff to contact you:</p>',
					'<p class="left">{please_share_any_comments}</p>',
					'<br class="clear" />',
				'</div>',
				'</tpl>',
			'</div>'
		);
		
		if (!this.surveyWindow) {
			this.surveyWindow = new Ext.Window({
				title: 'Career Seeker Survey',
				height: 500,
				width: 650,
				closeAction: 'hide',
				resizable: false,
				bodyStyle: 'padding: 15px',
				autoScroll: true,
				tpl: windowTpl,
				data: rec.data
			});
		} else {
			this.surveyWindow.title = 'Test';
			windowTpl.overwrite(this.surveyWindow.body, rec.data);
		}
	},
	deleteSurvey: function(rec) {
		this.store.remove(rec);
		Ext.getCmp('deletebtn').disable();
	},
	showWindow: function() {
		var rec = this.selectedRecord;
		console.log(rec);
		this.initWindow(rec);
		this.surveyWindow.show();
	}
}

var CareerSeekers = new CareerSeekers;
Ext.onReady(CareerSeekers.init, CareerSeekers);
=======
        init: function() {
                
                Ext.QuickTips.init();
                Ext.BLANK_IMAGE_URL = '/img/ext/default/s.gif';
                
                this.initData();
                this.initGrid();

                this.store.load();
                this.grid.render('surveys');
        },
        initData: function() {
                var Survey = Ext.data.Record.create([
                        { name: 'full_name', type: 'string', mapping: 'answers.first_name + " " + obj.answers.last_name' },
                        'answers',
                        { name: 'created', type: 'date', dateFormat: 'Y-m-d H:i:s' }
                ]);
                
                var proxy = new Ext.data.HttpProxy({
                        api: {
                                create:  { url: '/admin/career_seekers_surveys/create',  method: 'POST' },
                                read:    { url: '/admin/career_seekers_surveys/read',    method: 'GET'  },
                                update:  { url: '/admin/career_seekers_surveys/update',  method: 'POST' },
                                destroy: { url: '/admin/career_seekers_surveys/destroy', method: 'POST' }
                        }
                });
                
                var reader = new Ext.data.JsonReader({
                        root: 'surveys'
                }, Survey);
                
                var writer = new Ext.data.JsonWriter({
                        encode: true
                });
                
                this.store = new Ext.data.Store({
                        proxy: proxy,
                        reader: reader, 
                        writer: writer
                });             
        },
        initGrid: function() {
                this.gridToolbar = new Ext.Toolbar({
                        items: [{
                                id: 'deletebtn',
                                text: 'Delete Survey',
                                icon: '/img/icons/delete.png',
                                tooltip: 'Delete the selected survey',
                                disabled: true,
                                scope: this,
                                handler: function() {
                                        this.deleteSurvey(this.selectedRecord)
                                }
                        }]
                });
                
                this.grid = new Ext.grid.GridPanel({
                        store: this.store,
                        tbar: this.gridToolbar,
                        width: '100%',
                        height: 300,
                        title: 'Career Seekers',
                        sm: new Ext.grid.RowSelectionModel({
                                singleSelect: true,
                                listeners: {
                                        rowselect: {
                                                fn: function(sm, ri, rec) {
                                                        this.selectedRecord = rec;
                                                        Ext.getCmp('deletebtn').enable();
                                                },
                                                scope: this
                                        }
                                }
                        }),
                        cm: new Ext.grid.ColumnModel({
                                defaults: {
                                        sortable: true
                                },
                                columns: [{
                                        header: 'Career Seekers Name',
                                        dataIndex: 'full_name',
                                        renderer: function(value, metaData, record, rowIndex, colIndex, store) {
                                                // anonymous surveys will have one space as the value
                                                if (value === " ") {
                                                        return 'Anonymous';
                                                }
                                                
                                                return value;
                                        }
                                },{
                                        header: 'Submitted',
                                        dataIndex: 'created',
                                        xtype: 'datecolumn',
                                        format: 'm/d/Y h:i A',
                                        width: 50
                                }]
                        }),
                        viewConfig: {
                                forceFit: true
                        },
                        listeners: {
                                rowdblclick: {
                                        fn: function(g,ri,e) {
                                                this.showWindow();
                                        },
                                        scope: this
                                }
                        }
                });
        },
        initWindow: function(rec) {
                console.log(rec.data);
                
                var windowTpl = new Ext.XTemplate(
                        '<div class="x-careerseeker">',
                                '<tpl for="answers">',
                                '<div class="x-careerseeker-row">',
                                        '<p class="label left">First Name:</p>',
                                        '<p class="left">{first_name}</p>',
                                        '<br class="clear" />',
                                '</div>',
                                '<div class="x-careerseeker-row">',
                                        '<p class="label left">Last Name:</p>',
                                        '<p class="left">{last_name}</p>',
                                        '<br class="clear" />',
                                '</div>',
                                '<div class="x-careerseeker-row">',
                                        '<p class="label left">Date you worked with the Business Services Team or visited the Website:</p>',
                                        '<p class="left">',
                                        '<tpl for="date_you_worked_with_the_business_services_team_or_the_website">',
                                        '{month}/{day}/{year}',
                                        '</tpl>',
                                        '</p>',
                                        '<br class="clear" />',
                                '</div>',
                                '<div class="x-careerseeker-row">',
                                        '<p class="label left">Are your comments related to the Business Services Team or the website:</p>',
                                        '<p class="left">{are_your_comments_related_to_the_business_services_team_or_the_website}</p>',
                                        '<br class="clear" />',
                                '</div>',
                                '<div class="x-careerseeker-row">',
                                        '<p class="label left">Please enter the name(s) of staff (if any) who assisted you:</p>',
                                        '<p class="left">{names_of_staff_who_assisted_you}</p>',
                                        '<br class="clear" />',
                                '</div>',
                                '<div class="x-careerseeker-row">',
                                        '<p class="label left">Overall, how satisfied are you with the services you received from the office or website you selected above:</p>',
                                        '<p class="left">{overall_how_satisfied_are_you_with_the_services_you_received}</p>',
                                        '<br class="clear" />',
                                '</div>',
                                '<div class="x-careerseeker-row">',
                                        '<p class="label left">Think about what you expected from the office or website you visited. How well did the services you received meet your expectations:</p>',
                                        '<p class="left">{think_about_what_you_expected}</p>',
                                        '<br class="clear" />',
                                '</div>',
                                '<div class="x-careerseeker-row">',
                                        '<p class="label left">Think about the ideal services for other people in your circumstances. How well did the services you received from the office you visited or WorkNet Pinellas website compare to your ideal:</p>',
                                        '<p class="left">{think_about_the_ideal_services_for_other_people}</p>',
                                        '<br class="clear" />',
                                '</div>',
                                '<div class="x-careerseeker-row">',
                                        '<p class="label left">What programs are you currently participating in:</p>',
                                        '<p class="left">',
                                                '<ul>',
                                                '<tpl if="food_stamps == 1">',
                                                        '<li>Food Stamps (FSET)</li>',
                                                '</tpl>',
                                                '<tpl if="unemployment_comp == 1">',
                                                        '<li>Unemployment Compensation</li>',
                                                '</tpl>',
                                                '<tpl if="wia == 1">',
                                                        '<li>WIA</li>',
                                                '</tpl>',
                                                '<tpl if="vocational_rehab == 1">',
                                                        '<li>Vocational Rehabilitation</li>',
                                                '</tpl>',
                                                '<tpl if="welfare_transition == 1">',
                                                        '<li>Veterans Services</li>',
                                                '</tpl>',
                                                '<tpl if="universal_services == 1">',
                                                        '<li>Universal Services (Resource Room, Job Search)</li>',
                                                '</tpl>',
                                                '</ul>',
                                        '</p>',
                                        '<br class="clear" />',
                                '</div>',
                                '<div class="x-careerseeker-row">',
                                        '<p class="label left">How did you learn about us (please choose one):</p>',
                                        '<p class="left">{how_did_you_learn}</p>',
                                        '<br class="clear" />',
                                '</div>',
                                '<div class="x-careerseeker-row">',
                                        '<p class="label left">If you chose "Other" above, please elaborate, if possible:</p>',
                                        '<p class="left">{if_you_chose_Other}</p>',
                                        '<br class="clear" />',
                                '</div>',
                                '<div class="x-careerseeker-row">',
                                        '<p class="label left">Industry:</p>',
                                        '<p class="left">{industry}</p>',
                                        '<br class="clear" />',
                                '</div>',
                                '<div class="x-careerseeker-row">',
                                        '<p class="label left">Gender:</p>',
                                        '<p class="left">{gender}</p>',
                                        '<br class="clear" />',
                                '</div>',
                                '<div class="x-careerseeker-row">',
                                        '<p class="label left">Age:</p>',
                                        '<p class="left">{age}</p>',
                                        '<br class="clear" />',
                                '</div>',
                                '<div class="x-careerseeker-row">',
                                        '<p class="label left">Highest Level of Education Attained:</p>',
                                        '<p class="left">{highest_level_of_education_attained}</p>',
                                        '<br class="clear" />',
                                '</div>',
                                '<div class="x-careerseeker-row">',
                                        '<p class="label left">Please share any comments or suggestions you have regarding our Business Services or the website. Please enter your name, phone number and email address here, if you would like our staff to contact you:</p>',
                                        '<p class="left">{please_share_any_comments}</p>',
                                        '<br class="clear" />',
                                '</div>',
                                '</tpl>',
                        '</div>'
                );
                
                if (!this.surveyWindow) {
                        this.surveyWindow = new Ext.Window({
                                title: 'Career Seeker Survey',
                                height: 500,
                                width: 650,
                                closeAction: 'hide',
                                resizable: false,
                                bodyStyle: 'padding: 15px',
                                autoScroll: true,
                                tpl: windowTpl,
                                data: rec.data
                        });
                } else {
                        this.surveyWindow.title = 'Test';
                        windowTpl.overwrite(this.surveyWindow.body, rec.data);
                }
        },
        deleteSurvey: function(rec) {
                this.store.remove(rec);
                Ext.getCmp('deletebtn').disable();
        },
        showWindow: function() {
                var rec = this.selectedRecord;
                console.log(rec);
                this.initWindow(rec);
                this.surveyWindow.show();
        }
}

var CareerSeekers = new CareerSeekers;
Ext.onReady(CareerSeekers.init, CareerSeekers);
>>>>>>> staging
