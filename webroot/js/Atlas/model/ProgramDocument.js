Ext.define('Atlas.model.ProgramDocument', {
  extend: 'Ext.data.Model',
  fields: [{
    name: 'id',
    type: 'int'
  }, {
    name: 'program_id',
    type: 'int'
  }, {
    name: 'program_step_id',
    type: 'int'
  }, {
    name: 'template',
    type: 'string'
  }, {
    name: 'name',
    type: 'string'
  }, {
    name: 'cat_1',
    type: 'int'
  }, {
    name: 'cat_2',
    type: 'int'
  }, {
    name: 'cat_3',
    type: 'int'
  }, {
    name: 'type',
    type: 'string'
  }, {
    name: 'created',
    type: 'date',
    dateFormat: 'Y-m-d H:i:is'
  }, {
    name: 'modified',
    type: 'date',
    dateFormat: 'Y-m-d H:i:is'
  }],
  associations: [{
    type: 'belongsTo',
    model: 'Program',
    name: 'programs'
  }, {
    type: 'belongsTo',
    model: 'ProgramStep',
    name: 'program_step'
  }]
});
