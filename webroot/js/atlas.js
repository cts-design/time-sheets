/**
 * @author Brandon Cordell
 * @copyright Complete Technology Solutions 2011
 * @link http://ctsfla.com
 * @package ATLAS V3
 */

// declare our namespace
Ext.ns('Atlas', 'Atlas.util', 'Inflector');

// just in case we leave a console.log call in one of our scripts,
// we don't want to crash the clients browser
if (typeof console == "undefined") {
    window.console = {
        log: function () {}
    };
}

String.prototype.underscore = function() {
	return this.replace(/ /g, '_');
}

String.prototype.lowercase = function() {
	return this.toLowerCase();
}

String.prototype.uppercase = function() {
	return this.toUpperCase();
}

Atlas.util.die = function(msg) {
	var message = (msg) ? msg : 'Die';
	
	throw message;
}

