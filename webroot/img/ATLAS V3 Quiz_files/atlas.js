/**
 * @author Brandon Cordell
 * @copyright Complete Technology Solutions 2011
 * @link http://ctsfla.com
 * @package ATLAS V3
 */

/**
 * Make sure our client side code doesn't crash IE if we leave
 * any console.log messages in our code
 */
if (typeof console === "undefined") {
  window.console = {
    log: function () {}
  };
}

/**
 * Convenience function for lowercasing string
 * return {String} The lowercase version of the string
 */
String.prototype.lowercase = function() {
  return this.toLowerCase();
};

/**
 * Convenience function for uppercasing string
 * return {String} The uppercase version of the string
 */
String.prototype.uppercase = function() {
  return this.toUpperCase();
};

/**
 * Capitalize the first letter of a string
 * return {String} The input string with first letter capitalized
 */
String.prototype.capitalize = function () {
  var firstLetter = this.substring(0,1),
    restOfString = this.substring(1, this.length);

  return firstLetter.uppercase() + restOfString;
};

/**
 * Removes any non-alphanumeric characters then replaces all spaces
 * with underscores. Last sets the entire string to lowercase
 * return {String} The underscored version of the string
 */
String.prototype.underscore = function() {
  if (Ext.isIE) {
    return this.replace(/[^A-Za-z0-9\s]/g, '').replace(/\s/g, '_').lowercase();
  } else {
    return this.trim().replace(/[^A-Za-z0-9\s]/g, '').replace(/\s/g, '_').lowercase();
  }
};


/**
 * Converts string to camelcase
 * return {String} The camelcased version of the string
 */
String.prototype.camelize = function () {
  return this;
};

/**
 * Converts string to human readable string by replacing
 * underscores with spaces and capitalizing the first 
 * letter of the string
 * return {String} The humanized version of the string
 */
String.prototype.humanize = function () {
  return this.capitalize().replace(/_/g, ' ');
};

if (!Ext.isChrome) {
  window.location.origin  = window.location.protocol;
  window.location.origin += '//' + window.location.host;
}
