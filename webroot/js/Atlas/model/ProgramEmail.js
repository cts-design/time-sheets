Ext.define('Atlas.model.ProgramEmail', {
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
    name: 'cat_id',
    type: 'int'
  }, {
    name: 'to',
    type: 'string'
  }, {
    name: 'from',
    type: 'string'
  }, {
    name: 'subject',
    type: 'string'
  }, {
    name: 'body',
    type: 'string'
  }, {
    name: 'type',
    type: 'string'
  }, {
    name: 'name',
    type: 'string'
  }, {
    name: 'disabled',
    type: 'boolean'
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
    model: 'Program',
    name: 'programs'
  }, {
    type: 'belongsTo',
    model: 'ProgramStep',
    name: 'program_step'
  }]
});
