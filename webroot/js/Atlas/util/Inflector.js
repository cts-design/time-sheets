Ext.define('Atlas.util.Inflector', {
	extend: 'Ext.util.Inflector',
	
	initComponent: function () {
		var me = this;
		
		me.callParent(arguments);
	},
	
	toUnderscore: function (str) {
		
		
		String.prototype.toUnderscore = function(){
			return this.replace(/([A-Z])/g, function($1){return "_"+$1.toLowerCase();});
		};
	}
});