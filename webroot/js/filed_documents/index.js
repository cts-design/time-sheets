/* 
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 */

$(document).ready(function(){
    $("#modalDeleteForm").dialog({
		modal: true,
		height: 150,
		width: 400,
		resizable: false,
		draggable: false,
		title: 'Delete Filed Document ?',
		autoOpen: false
    });
    $(".delete").click(function() {
		$('#FiledDocumentId').val($(this).attr('rel'));
		$( "#modalDeleteForm" ).dialog( "open" );
		return false;
    });
});
