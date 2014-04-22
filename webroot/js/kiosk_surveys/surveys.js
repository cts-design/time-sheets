/**
 * @author Brandon Cordell
 * @copyright Complete Technology Solutions 2011
 * @link http://ctsfla.com
 * @package ATLAS V3
 */

var surveyPanel = {
	selectedSurvey: null,
	selectedQuestion: null,
	init: function () {

		this.initData();
		this.initGrid();

    Ext.define('SurveyType', {
      extend: 'Ext.data.Model',
      fields: [
        { name: 'shortname', type: 'string' },
        { name: 'longname',  type: 'string' }
      ]
    });

    Ext.create('Ext.data.ArrayStore', {
      storeId: 'surveyTypeStore',
      model: 'SurveyType',
      data: [
        [ 'yesno', 'Yes/No' ],
        [ 'truefalse', 'True/False' ],
        [ 'multi', 'Multiple Choice' ],
        [ 'text', 'Text Box']
      ]
    });

		var questionGridToolbar = Ext.create('Ext.toolbar.Toolbar', {
			items: [{
        id: 'newQuestionButton',
				text: 'New Question',
				icon: '/img/icons/add.png',
				disabled: true,
				scope: this,
				handler: function () {
					var form = Ext.getCmp('questionForm'),
						questionsGrid = Ext.getCmp('questionsGrid'),
						questionsSelModel = questionsGrid.getSelectionModel();
						
					this.selectedQuestion = null;

					if (!questionsSelModel.isLocked()) {
						questionsSelModel.clearSelections();
					}

					form.getForm().reset();
					form.enable();
				}
			}, {
				id: 'deleteQuestionButton',
				text: 'Delete Question',
				icon: '/img/icons/delete.png',
				disabled: true,
				scope: this,
				handler: function () {
					var form = Ext.getCmp('questionForm'),
						me = Ext.getCmp('deleteQuestionButton');

					me.disable();
					this.surveyQuestionStore.remove(this.selectedQuestion);
					this.selectedQuestion = null;
					form.getForm().reset();
					
					this.surveyQuestionStore.sync();
				}
			}]
		});



			this.gridForm = Ext.create('Ext.form.Panel', {
        title: 'Survey Questions',
        tbar: questionGridToolbar,
				id: 'questionForm',
				frame: true,
        fieldDefaults: {
          labelAlign: 'top'
        },
				bodyStyle: 'padding:5px',
				layout: 'column',
				items: [{
					columnWidth: 0.70,
					layout: 'fit',
					items: [ this.surveyQuestionsGrid ]
				}, {
					columnWidth: 0.30,
					xtype: 'fieldset',
					labelWidth: 90,
					title: 'Questions/Options',
					defaults: {
						width: 243,
						border: false
					},
					defaultType: 'textfield',
					autoHeight: true,
					bodyStyle: Ext.isIE ? 'padding: 0 0 5px 15px' : 'padding: 10px 15px',
					border: false,
					style: {
						'margin-left': '8px',
						'margin-right': Ext.isIE6 ? (Ext.isStrict ? '-10px' : '-13px') : '0'
					},
					items: [{
						id: 'questionField',
						xtype: 'textfield',
						fieldLabel: 'Question',
						name: 'question',
						// disabled: true,
						allowBlank: false
					}, {
						id: 'typeField',
						xtype: 'combo',
						fieldLabel: 'Type',
						name: 'type',
						// disabled: true,
						allowBlank: false,
						store: Ext.data.StoreManager.lookup('surveyTypeStore'),
						valueField: 'shortname',
						displayField: 'longname',
						queryMode: 'local',
						triggerAction: 'all',
						emptyText: 'Select a question type...',
						selectOnFocus: true,
						listeners: {
							select: function (combo, rec, index) {
								if (rec[0].data.shortname === 'yesno' || rec[0].data.shortname === 'truefalse'  || rec[0].data.shortname === 'text') {
									Ext.getCmp('optionsField').disable().reset();
								} else {
									Ext.getCmp('optionsField').enable();
								}
							}
						}
					}, {
						id: 'optionsField',
						xtype: 'textfield',
						fieldLabel: 'Options',
						name: 'options',
						// disabled: true,
						allowBlank: false
					}, {
            id: 'orderField',
            xtype: 'textfield',
            fieldLabel: 'Order',
            name: 'order',
            // disabled: true,
            allowBlank: false
          }, {
						id: 'saveButton',
						xtype: 'button',
						text: 'Save',
						width: 75,
						scope: this,
						handler: function () {
							var form = Ext.getCmp('questionForm').getForm(),
								vals = form.getValues(),
								rec;
							
							if (form.isValid()) {
								if (this.selectedQuestion) {
									rec = this.surveyQuestionStore.getById(this.selectedQuestion.data.id);
									
									rec.beginEdit();
									rec.set('question', vals.question);
									rec.set('type', vals.type);
									rec.set('options', vals.options);
									rec.set('order', vals.order);
									rec.endEdit();
								} else {
									this.surveyQuestionStore.add({
										kiosk_survey_id: this.selectedSurvey.data.id,
										question: vals.question,
										type: vals.type,
										options: vals.options,
										order: vals.order
									});
								}
								
								this.surveyQuestionStore.sync();
							}
						}
					}]
				}]
			});
	},
	initData: function () {

    Ext.define('Survey', {
      extend: 'Ext.data.Model',
      fields: [
        { name: 'id',       type: 'int' },
        'name',
        { name: 'created',  type: 'date', dateFormat: 'Y-m-d H:i:s' },
        { name: 'modified', type: 'date', dateFormat: 'Y-m-d H:i:s' }
      ]
    });

    Ext.define('SurveyQuestion', {
      extend: 'Ext.data.Model',
      fields: [
        { name: 'id',              type: 'int' },
        { name: 'kiosk_survey_id', type: 'int' },
        { name: 'question',        type: 'string' },
        { name: 'type',            type: 'string' },
        { name: 'options',         type: 'string' },
        { name: 'order',           type: 'int' },
        { name: 'created',         type: 'date', dateFormat: 'Y-m-d H:i:s' }
      ]
    });

    this.surveyStore = Ext.create('Ext.data.Store', {
      storeId: 'surveyStore',
      model: 'Survey',
      autoLoad: true,
      proxy: {
        type: 'ajax',
        reader: {
          root: 'surveys',
          type: 'json'
        },
				writer: {
					encode: true,
					root: 'surveys',
					writeAllFields: false
				},
        api: {
          create:  '/admin/kiosk_surveys/create',
          read:    '/admin/kiosk_surveys/read',
          update:  '/admin/kiosk_surveys/update',
          destroy: '/admin/kiosk_surveys/destroy'
        }
      },
		  listeners: {
		    remove: {
		      fn: function (store, rec, index) {
						var toolbar = Ext.getCmp('surveyGrid').getDockedItems(),
							questionStore = Ext.data.StoreManager.lookup('surveyQuestionStore');
							
						toolbar[1].items.items[1].disable();
						toolbar[1].items.items[2].disable();
						
		        // check if there are questions
		        if (questionStore.totalLength > 0) {
		          questionStore.reload();
		        }
		      },
		      scope: this
		    },
		    datachange: {
		      fn: function (store, action, result, res, rs) {
		        store.reload();
		      }
		    }
		  }
    });

    this.surveyQuestionStore = Ext.create('Ext.data.Store', {
      storeId: 'surveyQuestionStore',
      model: 'SurveyQuestion',
			sorters: [
				{ property: 'order', direction: 'ASC' }
			],
      proxy: {
        type: 'ajax',
				reader: {
					root: 'surveyQuestions'
				},
				writer: {
					type: 'json',
					root: 'surveyQuestions',
					encode: true,
					writeAllFields: true
				},
        api: {
          create:  '/admin/kiosk_survey_questions/create',
          read:    '/admin/kiosk_survey_questions/read',
          update:  '/admin/kiosk_survey_questions/update',
          destroy: '/admin/kiosk_survey_questions/destroy'
        }
      },
			listeners: {
				write: function (store, operation, opts) {
					Ext.getCmp('questionForm').getForm().reset();
				}
			}
    });
	},
	initGrid: function () {
    var surveyToolbar = Ext.create('Ext.toolbar.Toolbar', {
      items: [{
        text: 'New Survey',
        icon: '/img/icons/add.png',
        scope: this,
        handler: function () {
          Ext.Msg.prompt('New Survey', 'Enter a name for the survey', function (btn, text) {
            if (btn === 'ok') {
							var currentTime = new Date(),
								month = currentTime.getMonth() + 1,
								day = currentTime.getDate(),
								year = currentTime.getFullYear();
								
              this.surveyStore.add({ name: text, created: month + "/" + day + "/" + year });
              this.surveyStore.sync();
            }
          }, this);
        }
      }, {
        text: 'Delete Survey',
        icon: '/img/icons/delete.png',
        disabled: true,
        scope: this,
        handler: function () {
          Ext.Msg.show({
            scope: this,
            title: 'Are you sure?',
            msg: 'Are you sure you want to delete this record?',
            buttons: Ext.Msg.YESNO,
            icon: Ext.MessageBox.QUESTION,
            fn: function (btn) {
              if (btn === 'yes') {
                this.surveyStore.remove(this.selectedSurvey);
								this.surveyStore.sync();
              }
            }
          });
        }
      }, {
				xtype: 'splitbutton',
        text: 'Report',
        icon: '/img/icons/chairman_reports.png',
        disabled: true,
        scope: this,
				menu: new Ext.menu.Menu({
					items: [{
						text: 'View Responses',
						icon: '/img/icons/chairman_reports.png',
						handler: function () {}
					}, {
						text: 'Download as Excel',
						icon: '/img/icons/excel.png',
						handler: function () {}
					}]
				}),
        handler: function () {
          var surveyId = this.selectedSurvey.data.id;
          window.location = '/admin/kiosk_surveys/report?survey_id=' + surveyId;
        }
      }]
    });

		this.surveyGrid = Ext.create('Ext.grid.Panel', {
      id: 'surveyGrid',
      title: 'Surveys',
			tbar: surveyToolbar,
      store: this.surveyStore,
      autoScroll: true,
			height: 275,
			frame: false,
      forceFit: true,
      columns: [
        { text: 'Id',   sortable: true, dataIndex: 'id', hidden: true },
				{ text: 'Name', sortable: true, dataIndex: 'name' },
        {
          text: 'Created',
          sortable: true,
          dataIndex: 'created',
          renderer: Ext.util.Format.dateRenderer()
        }
      ],
			listeners: {
		    deselect: {
		      fn: function (rm, rec, index, opts) {
		        this.selectedSurvey = null;
		      },
		      scope: this
		    },
		    select: {
		      fn: function (rm, rec, index, opts) {
		        var newQuestionButton = Ext.getCmp('newQuestionButton'),
	            form = Ext.getCmp('questionForm'),
		          questionField = Ext.getCmp('questionField'),
		          typeField = Ext.getCmp('typeField'),
		          optionsField = Ext.getCmp('optionsField'),
	            orderField = Ext.getCmp('orderField');

            form.getForm().reset();
            questionField.disable();
            typeField.disable();
            optionsField.disable();
            orderField.disable();

		        this.selectedSurvey = rec;
		        surveyToolbar.items.items[1].enable(); // enable delete button
	          surveyToolbar.items.items[2].enable();
		        
						// load any existing questions
						this.surveyQuestionStore.load({
							params: { 
								kiosk_id: rec.data.id
							} 
						});
						
		        newQuestionButton.enable(); // enable new question button
		      },
		      scope: this
		    }
			}
		});

		this.surveyQuestionsGrid = Ext.create('Ext.grid.Panel', {
			id: 'questionsGrid',
      store: this.surveyQuestionStore,
			height: 270,
			frame: false,
      loadMask: true,
			viewConfig: {
				emptyText: '<br/><br/><center><div class="no_data">No data available...</div></center>'
			},
      columns: [
				{ header: 'Question', sortable: true, dataIndex: 'question', flex: 1 },
				{
					header: 'Type',
					sortable: true,
					dataIndex: 'type',
					renderer: function (value) {
						switch (value) {
						case 'yesno':
							return 'Yes/No';
						case 'truefalse':
							return 'True/False';
						case 'multi':
							return 'Multiple Choice';
						case 'text':
 							return 'Text Box';
						}

						return value;
					}
				},
				{ header: 'Options', sortable: true, dataIndex: 'options' },
        { header: 'Order', sortable: true, dataIndex: 'order', width: 75, align: 'center' }
      ],
			listeners: {
		    deselect: {
		      fn: function (rm, rec, index, opts) {
		        this.selectedQuestion = null;
		      },
		      scope: this
		    },
		    select: {
		      fn: function (rm, rec, index, opts) {
					this.selectedQuestion = rec;
						
					var form = Ext.getCmp('questionForm').getForm(),
					questionField = Ext.getCmp('questionField'),
					typeField = Ext.getCmp('typeField'),
					optionsField = Ext.getCmp('optionsField'),
					orderField = Ext.getCmp('orderField');

					if (rec.data.type === "yesno" || rec.data.type === "truefalse" || rec.data.type === "text") {
						questionField.enable();
	 	                typeField.enable();
						orderField.enable();
						optionsField.disable();
	         		} else {
						questionField.enable();
						typeField.enable();
						orderField.enable();
						optionsField.enable();        			
	         		}

					form.loadRecord(rec);
                Ext.getCmp('deleteQuestionButton').enable();
		      },
		      scope: this
		    }
			}
		});
	}
};

Ext.onReady(function () {
	Ext.QuickTips.init();
	surveyPanel.init();
  surveyPanel.surveyGrid.render('survey-grid');
  surveyPanel.gridForm.render('grid-form');
});
