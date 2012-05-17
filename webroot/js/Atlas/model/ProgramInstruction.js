Ext.define('Atlas.model.ProgramInstruction', {
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
    name: 'text',
    type: 'string'
  }, {
    name: 'type',
    type: 'string'
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
    name: 'program_steps'
  }]
});
