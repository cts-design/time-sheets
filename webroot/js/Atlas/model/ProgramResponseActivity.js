Ext.define('Atlas.model.ProgramResponseActivity', {
  extend: 'Ext.data.Model',
  fields: [{
    name: 'id',
    type: 'int'
  }, {
    name: 'program_response_id',
    type: 'int'
  }, {
    name: 'program_step_id',
    type: 'int'
  }, {
    name: 'type',
    type: 'string'
  }, {
    name: 'status',
    type: 'string'
  }, {
    name: 'answers',
    type: 'string'
  }, {
    name: 'percent_correct',
    type: 'int'
  }, {
    name: 'created',
    type: 'date',
    dateFormat: 'Y-m-d H:i:s'
  }, {
    name: 'modified',
    type: 'date',
    dateFormat: 'Y-m-d H:i:s'
  }],
  associations: [{
    type: 'belongsTo',
    model: 'ProgramResponse',
    name: 'program_responses'
  }]
});
