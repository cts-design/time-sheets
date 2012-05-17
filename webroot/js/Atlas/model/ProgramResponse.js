Ext.define('Atlas.model.ProgramResponse', {
  extend: 'Ext.data.Model',
  fields: [{
    name: 'id',
    type: 'int'
  }, {
    name: 'program_id',
    type: 'int'
  }, {
    name: 'user_id',
    type: 'int'
  }, {
    name: 'status',
    type: 'string'
  }, {
    name: 'confirmation_id',
    type: 'int'
  }, {
    name: 'notes',
    type: 'string'
  }, {
    name: 'time_spent',
    type: 'date',
    dateFormat: 'H:i:s'
  }, {
    name: 'created',
    type: 'date',
    dateFormat: 'Y-m-d H:i:s'
  }, {
    name: 'modified',
    type: 'date',
    dateFormat: 'Y-m-d H:i:s'
  }, {
    name: 'expires_on',
    type: 'date',
    dateFormat: 'Y-m-d H:i:s'
  }],
  associations: [{
    type: 'belongsTo',
    model: 'Program',
    name: 'programs'
  }, {
    type: 'belongsTo',
    model: 'User',
    name: 'users'
  }, {
    type: 'hasMany',
    model: 'ProgramResponseDoc',
    name: 'program_response_docs'
  }, {
    type: 'hasMany',
    model: 'ProgramResponseActivity',
    name: 'program_response_activies'
  }]
});
