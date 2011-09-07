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
    "use strict";

		this.initData();
		this.initGrid();
		
		var questionGridToolbar = new Ext.Toolbar({
			items: [{
				id: 'newQuestionButton',
				text: 'New Question',
				icon: '/img/icons/add.png',
				disabled: true,
				handler: function () {
					var form = Ext.getCmp('questionForm'),
						questionsGrid = Ext.getCmp('questionsGrid'),
						questionsSelModel = questionsGrid.getSelectionModel();	
					
					if (!questionsSelModel.isLocked()) {
						questionsSelModel.clearSelections();
					}
					
					form.getForm().reset();
					form.items.items[1].buttons[0].enable();
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
				}
			}]
		}),
		
			gridForm = new Ext.FormPanel({
				id: 'questionForm',
				frame: true,
				labelAlign: 'top',
				title: false,
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
						disabled: true,
						allowBlank: false
					}, {
						id: 'typeField',
						xtype: 'combo',
						fieldLabel: 'Type',
						name: 'type',
						disabled: true,
						allowBlank: false,
						hiddenName: 'type',
						store: new Ext.data.ArrayStore({
							fields: ['shortname', 'longname'],
							data: [
								['yesno', 'Yes/No'],
								['truefalse', 'True/False'],
								['multi', 'Multiple Choice']
							]
						}),
						valueField: 'shortname',
						displayField: 'longname',
						mode: 'local',
						triggerAction: 'all',
						emptyText: 'Select a question type...',
						selectOnFocus: true,
						listeners: {
							select: function (combo, rec, index) {
								if (rec.data.shortname === 'yesno' || rec.data.shortname === 'truefalse') {
									Ext.getCmp('optionsField').disable();
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
						disabled: true,
						allowBlank: false
					}, {
            id: 'orderField',
            xtype: 'textfield',
            fieldLabel: 'Order',
            name: 'order',
            disabled: true,
            allowBlank: false
          }],
					buttons: [{
						id: 'saveButton',
						text: 'Save',
						disabled: true,
						scope: this,
						handler: function () {
							var form = Ext.getCmp('questionForm').getForm(),
								vals = form.getValues(),
								NewRecord = Ext.data.Record.create(['kiosk_survey_id', 'question', 'type', 'options', 'order']),
								rec,
								questionField = Ext.getCmp('questionField'),
								typeField = Ext.getCmp('typeField'),
								optionsField = Ext.getCmp('optionsField'),
                orderField = Ext.getCmp('orderField'),
								saveButton = Ext.getCmp('saveButton');

								
							if (form.isValid()) {
                questionField.disable();
                typeField.disable();
                optionsField.disable();
                orderField.disable();
                saveButton.disable();

								if (this.selectedQuestion) {
                  rec = this.surveyQuestionStore.getById(this.selectedQuestion.id);

                  rec.beginEdit();
                  rec.set('question', vals.question);
                  rec.set('type', vals.type);
                  rec.set('options', vals.options);
                  rec.set('order', vals.order);
                  rec.endEdit();

                  console.log(rec);

                } else {
                  rec = new NewRecord({
                    kiosk_survey_id: this.selectedSurvey.id,
                    question: vals.question,
                    type: vals.type,
                    options: vals.options,
                    order: vals.order
                  });

                  this.surveyQuestionStore.add(rec);
                }
	
                this.surveyQuestionStore.commitChanges();
                questionField.enable();
                typeField.enable();
                optionsField.enable();
                orderField.enable();
                saveButton.enable();
							}
						}
					}]
				}]
			});

		this.panel = new Ext.Panel({
			layout: 'border',
			renderTo: 'surveys',
			height: 550,
			width: '100%',
			defaults: {
				collapsible: false,
				split: false
			},
			items: [{
				title: 'Surveys',
				height: 175,
				region: 'center',
				items: [ this.surveyGrid ]
			}, {
				title: 'Survey Questions',
				tbar: questionGridToolbar,
				height: 346,
				region: 'south',
				bodyStyle: {
					'background': '#DFE8F6'
				},
				items: [gridForm]
			}]
		});

		this.surveyStore.load();
	},
	initData: function () {
    "use strict";

		var surveyProxy = new Ext.data.HttpProxy({
			api: {
				create:  { url: '/admin/kiosk_surveys/create',  method: 'POST' },
				read:    { url: '/admin/kiosk_surveys/read',    method: 'GET'  },
				update:  { url: '/admin/kiosk_surveys/update',  method: 'POST' },
				destroy: { url: '/admin/kiosk_surveys/destroy', method: 'POST' }
			}
		}),

			surveyFields = Ext.data.Record.create([
				{ name: 'id', type: 'int' },
				{ name: 'kiosk_id', type: 'int' },
				'name',
				{ name: 'created', type: 'date', dateFormat: 'Y-m-d H:i:s' }
			]),

			surveyReader = new Ext.data.JsonReader({
				messageProperty: 'message',
				root: 'surveys'
			}, surveyFields),

			surveyWriter = new Ext.data.JsonWriter(),

			surveyQuestionProxy = new Ext.data.HttpProxy({
				api: {
					create:  { url: '/admin/kiosk_survey_questions/create',  method: 'POST' },
					read:    { url: '/admin/kiosk_survey_questions/read',    method: 'GET'  },
					update:  { url: '/admin/kiosk_survey_questions/update',  method: 'POST' },
					destroy: { url: '/admin/kiosk_survey_questions/destroy', method: 'POST' }
				}
			}),

			surveyQuestionFields = Ext.data.Record.create([
				{ name: 'id', type: 'int' },
				{ name: 'kiosk_survey_id', type: 'int' },
				'question',
				'type',
				'options',
        { name: 'order', type: 'int' },
				{ name: 'created', type: 'date', dateFormat: 'Y-m-d H:i:s' }
			]),

			surveyQuestionReader = new Ext.data.JsonReader({
				messageProperty: 'message',
				root: 'surveyQuestions'
			}, surveyQuestionFields),

			surveyQuestionWriter = new Ext.data.JsonWriter({
        writeAllFields: true
      });
			
		this.surveyStore = new Ext.data.Store({
			storeId: 'surveyStore',
			proxy: surveyProxy,
			reader: surveyReader,
			writer: surveyWriter,
			autoSave: true,
			listeners: {
				remove: {
					fn: function (store, rec, index) {
						// disable delete button
						this.surveyGrid.topToolbar.items.items[1].disable();
						
						// check if there are questions
						if (this.surveyQuestionStore.totalLength > 0) {
							this.surveyQuestionStore.reload();
						}
					},
					scope: this
				},
				write: {
					fn: function (store, action, result, res, rs) {
						store.reload();
					}
				}
			}
		});

		this.surveyQuestionStore = new Ext.data.Store({
			storeId: 'surveyQuestionStore',
			proxy: surveyQuestionProxy,
			reader: surveyQuestionReader,
			writer: surveyQuestionWriter,
      sortInfo: {
        field: 'order',
        direction: 'ASC'
      },
			write: {
				fn: function (store, action, result, res, rs) {
					store.reload();
				}
			}
		});
	},
	initGrid: function () {
    "use strict";

		var surveyGridView = new Ext.grid.GridView({ forceFit: true }),

			surveyColModel = new Ext.grid.ColumnModel([
				{ header: 'Name', sortable: true, dataIndex: 'name' },
				{
					header: 'Created',
					sortable: true,
					dataIndex: 'created',
					renderer: Ext.util.Format.dateRenderer(),
					width: 50
				}
			]),

			surveySelModel = new Ext.grid.RowSelectionModel({
				singleSelect: true,
				listeners: {
					rowdeselect: {
						fn: function (sm, row, rec) {
							this.selectedSurvey = null;
						},
						scope: this
					},
					rowselect: {
						fn: function (sm, row, rec) {
							var newQuestionButton = Ext.getCmp('newQuestionButton'),
                form = Ext.getCmp('questionForm'),
								questionField = Ext.getCmp('questionField'),
								typeField = Ext.getCmp('typeField'),
								optionsField = Ext.getCmp('optionsField'),
                orderField = Ext.getCmp('orderField'),
								saveButton = Ext.getCmp('saveButton');

              form.getForm().reset();
              questionField.disable();
              typeField.disable();
              optionsField.disable();
              orderField.disable();
              saveButton.disable();

							this.selectedSurvey = rec;
							sm.grid.topToolbar.items.items[1].enable(); // enable delete button
							this.surveyQuestionStore.reload({ params: {kiosk_id: rec.id} }); // load any existing questions
							newQuestionButton.enable(); // enable new question button

						},
						scope: this
					}
				}
			}),

			surveyToolbar = new Ext.Toolbar({
				items: [{
					text: 'New Survey',
					icon: '/img/icons/add.png',
					scope: this,
					handler: function () {
						Ext.Msg.prompt('New Survey', 'Enter a name for the survey', function (btn, text) {
							if (btn === 'ok') {
								var NewRecord = Ext.data.Record.create(['name', 'created']),
									rec = new NewRecord({
										name: text,
										created: new Date().format('m/d/Y')
									});

								this.surveyStore.add(rec);
								this.surveyStore.commitChanges();
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
								}
							}
						});
					}
				}]
			}),
		
			surveyQuestionsGridView = new Ext.grid.GridView({ forceFit: true }),

			surveyQuestionsColModel = new Ext.grid.ColumnModel([
				{ header: 'Question', sortable: true, dataIndex: 'question' },
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
						}

						return value;
					}
				},
				{ header: 'Options', sortable: true, dataIndex: 'options' },
        { header: 'Order', sortable: false, dataIndex: 'order' }
			]),

			surveyQuestionsSelModel = new Ext.grid.RowSelectionModel({
				singleSelect: true,
				listeners: {
					rowdeselect: {
						fn: function (sm, row, rec) {
							this.selectedQuestion = null;
						},
						scope: this
					},
					rowselect: {
						fn: function (sm, row, rec) {
							this.selectedQuestion = rec;

							var form = Ext.getCmp('questionForm').getForm(),
								questionField = Ext.getCmp('questionField'),
								typeField = Ext.getCmp('typeField'),
								optionsField = Ext.getCmp('optionsField'),
                orderField = Ext.getCmp('orderField'),
								saveButton = Ext.getCmp('saveButton');

							if (questionField.disabled) {
								questionField.enable();
								typeField.enable();
                orderField.enable();
								saveButton.enable();

							} else {
                if (rec.data.type === "yesno" || rec.data.type === "truefalse") {
                  optionsField.disable();
                }
              }
                if (rec.data.type !== "yesno" && rec.data.type !== 'truefalse') {
                  optionsField.enable();
                }

							
							form.loadRecord(rec);
							
							Ext.getCmp('deleteQuestionButton').enable();
						},
						scope: this
					}
				}
			});

		this.surveyGrid = new Ext.grid.GridPanel({
			tbar: surveyToolbar,
			store: this.surveyStore,
			colModel: surveyColModel,
			sm: surveySelModel,
			view: surveyGridView,
			height: 175,
			frame: false,
      loadMask: true
		});

		this.surveyQuestionsGrid = new Ext.grid.GridPanel({
			id: 'questionsGrid',
			store: this.surveyQuestionStore,
			colModel: surveyQuestionsColModel,
			sm: surveyQuestionsSelModel,
			view: surveyQuestionsGridView,
			height: 270,
			frame: true,
      loadMask: true,
			viewConfig: {
				emptyText: '<br/><br/><center><div class="no_data">No data available...</div></center>'
			}
		});
	}
};

Ext.onReady(function () {
  "use strict";

	Ext.QuickTips.init();
	surveyPanel.init();
});
