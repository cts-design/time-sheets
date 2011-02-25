/* 
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 */

$.fx.speeds._default = 1000;
$(document).ready(function(){
    $("#message").dialog({
	modal: true,
	title: 'Success',
	resizable: false,
	show: 'blind',
	draggable: false,
	open: function(event, ui) {
	    setTimeout(function(){
		$("#message").dialog('close')
	    }, 8000)
	}
    });
})