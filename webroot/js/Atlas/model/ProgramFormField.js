Ext.define('Atlas.model.ProgramFormField', {
  extend: 'Ext.data.Model',
  fields: [{
    name: 'id',
    type: 'int'
  }, {
    name: 'program_step_id',
    type: 'int'
  },  {
    name: 'label',
    type: 'string'
  },  {
    name: 'type',
    type: 'string'
  },  {
    name: 'name',
    type: 'string'
  },  {
    name: 'attributes',
    type: 'string'
  },  {
    name: 'options',
    type: 'string'
  },  {
    name: 'validation',
    type: 'string'
  },  {
    name: 'instructions',
    type: 'string'
  },  {
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
    model: 'ProgramStep',
    name: 'program_steps'
  }]
});

