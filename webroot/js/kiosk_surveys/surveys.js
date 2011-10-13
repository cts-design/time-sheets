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
      extend: 'Ext.data.Model'
    });

		var questionGridToolbar = Ext.create('Ext.toolbar.Toolbar', {
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

      surveyTypes = Ext.create('Ext.data.ArrayStore', {
        storeId: 'surveyTypeStore',
        model: 'SurveyType',
        fields: [
          { name: 'shortname', type: 'string' },
          { name: 'longname',  type: 'string' }
        ],
        data: [
          [ 'yesno', 'Yes/No' ],
          [ 'truefalse', 'True/False' ],
          [ 'multi', 'Multiple Choice' ]
        ]
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
						disabled: true,
						allowBlank: false
					}, {
						id: 'typeField',
						xtype: 'combo',
						fieldLabel: 'Type',
						name: 'type',
						disabled: true,
						allowBlank: false,
						store: surveyTypes,
						valueField: 'shortname',
						displayField: 'longname',
						queryMode: 'local',
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

		// this.panel = Ext.create('Ext.panel.Panel', {
		//   layout: 'border',
		//   renderTo: 'surveys',
		//   height: 550,
		//   width: '100%',
		//   defaults: {
		//     collapsible: false,
		//     split: false
		//   },
		//   items: [{
		//     title: 'Surveys',
		//     height: 175,
		//     region: 'center',
		//     items: [ this.surveyGrid ]
		//   }, {
		//     title: 'Survey Questions',
		//     tbar: questionGridToolbar,
    //     frame: false,
		//     height: 346,
		//     region: 'south',
		//     bodyStyle: {
		//       'background': '#DFE8F6'
		//     },
		//     items: [gridForm]
		//   }]
		// });
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
        api: {
          create:  '/admin/kiosk_surveys/create',
          read:    '/admin/kiosk_surveys/read',
          update:  '/admin/kiosk_surveys/update',
          destroy: '/admin/kiosk_surveys/destroy'
        }
      }
    });

    this.surveyQuestionStore = Ext.create('Ext.data.Store', {
      storeId: 'surveyQuestionStore',
      model: 'SurveyQuestion',
      proxy: {
        type: 'ajax',
        api: {
          create:  '/admin/kiosk_survey_questions/create',
          read:    '/admin/kiosk_survey_questions/read',
          update:  '/admin/kiosk_survey_questions/update',
          destroy: '/admin/kiosk_survey_questions/destroy'
        }
      }
    });

		// this.surveyStore = new Ext.data.Store({
		//   storeId: 'surveyStore',
    //   model: 'Survey',
		//   proxy: surveyProxy,
		//   reader: surveyReader,
		//   writer: surveyWriter,
		//   autoSync: true,
		//   listeners: {
		//     remove: {
		//       fn: function (store, rec, index) {
		//         // disable delete button
		//         this.surveyGrid.topToolbar.items.items[1].disable();

		//         // check if there are questions
		//         if (this.surveyQuestionStore.totalLength > 0) {
		//           this.surveyQuestionStore.reload();
		//         }
		//       },
		//       scope: this
		//     },
		//     datachange: {
		//       fn: function (store, action, result, res, rs) {
		//         store.reload();
		//       }
		//     }
		//   }
		// });

		// this.surveyQuestionStore = new Ext.data.Store({
		//   storeId: 'surveyQuestionStore',
    //   model: 'SurveyQuestion',
		//   proxy: surveyQuestionProxy,
		//   reader: surveyQuestionReader,
		//   writer: surveyQuestionWriter,
    //   sortInfo: {
    //     field: 'order',
    //     direction: 'ASC'
    //   },
		//   write: {
		//     fn: function (store, action, result, res, rs) {
		//       store.reload();
		//     }
		//   }
		// });


			// surveyReader = new Ext.data.JsonReader({
			//   messageProperty: 'message',
			//   root: 'surveys'
			// }),

			// surveyWriter = new Ext.data.JsonWriter(),

			// surveyQuestionProxy = new Ext.data.HttpProxy({
			//   api: {
			//   }
			// }),

			// surveyQuestionFields = Ext.data.Record.create([
			//   { name: 'id', type: 'int' },
			//   { name: 'kiosk_survey_id', type: 'int' },
			//   'question',
			//   'type',
			//   'options',
      //   { name: 'order', type: 'int' },
			//   { name: 'created', type: 'date', dateFormat: 'Y-m-d H:i:s' }
			// ]),

			// surveyQuestionReader = new Ext.data.JsonReader({
			//   messageProperty: 'message',
			//   root: 'surveyQuestions'
			// }),

			// surveyQuestionWriter = new Ext.data.JsonWriter({
      //   writeAllFields: true
      // });
	},
	initGrid: function () {

		// var surveyGridView = new Ext.grid.GridView({ forceFit: true }),


			// surveySelModel = new Ext.grid.RowSelectionModel({
			//   singleSelect: true,
			//   listeners: {
			//     rowdeselect: {
			//       fn: function (sm, row, rec) {
			//         this.selectedSurvey = null;
			//       },
			//       scope: this
			//     },
			//     rowselect: {
			//       fn: function (sm, row, rec) {
			//         var newQuestionButton = Ext.getCmp('newQuestionButton'),
      //           form = Ext.getCmp('questionForm'),
			//           questionField = Ext.getCmp('questionField'),
			//           typeField = Ext.getCmp('typeField'),
			//           optionsField = Ext.getCmp('optionsField'),
      //           orderField = Ext.getCmp('orderField'),
			//           saveButton = Ext.getCmp('saveButton');

      //         form.getForm().reset();
      //         questionField.disable();
      //         typeField.disable();
      //         optionsField.disable();
      //         orderField.disable();
      //         saveButton.disable();

			//         this.selectedSurvey = rec;
			//         sm.grid.topToolbar.items.items[1].enable(); // enable delete button
      //         sm.grid.topToolbar.items.items[2].enable();
			//         this.surveyQuestionStore.reload({ params: {kiosk_id: rec.id} }); // load any existing questions
			//         newQuestionButton.enable(); // enable new question button

			//       },
			//       scope: this
			//     }
			//   }
			// }),

    var surveyToolbar = Ext.create('Ext.toolbar.Toolbar', {
      items: [{
        text: 'New Survey',
        icon: '/img/icons/add.png',
        scope: this,
        handler: function () {
          Ext.Msg.prompt('New Survey', 'Enter a name for the survey', function (btn, text) {
            if (btn === 'ok') {
              this.surveyStore.add({ name: text, created: Ext.Date.format('m/d/Y') });
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
              }
            }
          });
        }
      }, {
        text: 'Report',
        icon: '/img/icons/excel.png',
        disabled: true,
        scope: this,
        handler: function () {
          var surveyId = this.selectedSurvey.id;
          window.location = '/admin/kiosk_surveys/report?survey_id=' + surveyId;
        }
      }]
    });
			// surveyQuestionsGridView = new Ext.grid.GridView({ forceFit: true }),

			// surveyQuestionsSelModel = new Ext.grid.RowSelectionModel({
			//   singleSelect: true,
			// });

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
      ]
		});

		this.surveyQuestionsGrid = Ext.create('Ext.grid.Panel', {
			id: 'questionsGrid',
      store: this.surveyQuestionStore,
			// sm: surveyQuestionsSelModel,
			// view: surveyQuestionsGridView,
			height: 270,
			frame: false,
      loadMask: true,
			viewConfig: {
				emptyText: '<br/><br/><center><div class="no_data">No data available...</div></center>'
			},
      columns: [
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
      ]
		});
	}
};

Ext.onReady(function () {
  Ext.Compat.showErrors = true;
	Ext.QuickTips.init();
	surveyPanel.init();
  surveyPanel.surveyGrid.render('survey-grid');
  surveyPanel.gridForm.render('grid-form');
});
