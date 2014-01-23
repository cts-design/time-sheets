/*
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 */
$(document).ready(function(){
    $('.scan').button();
    $('.save, .re-scan').button({disabled: true});
    var validator = $('#QueuedDocumentDesktopScanDocumentForm').validate({
	errorPlacement: function(error,element) {
	    return true;
	},
	invalidHandler: function() {
	    var errors = validator.numberOfInvalids();
	    if(errors) {
		var message = errors == 1
		? 'You missed 1 field. It has been highlighted in red.'
		: 'You missed ' + errors + ' fields. They have been highlighted in red.';
		$('.formErrors').html(message);
		$('.formErrors').show();
	    }
	    else {
		$('.formErrors').hide();
	    }
	}
    });
})
function isValid() {
    if($('#QueuedDocumentDesktopScanDocumentForm').valid()) {
	return true
    }
    else {
	return false
    }
}

function disableScan() {
    $('.scan').button({disabled: true});
}
function enableReScan(){
    $('.save, .re-scan').button({disabled: false});
}

function getQueueCat () {
    return $('#QueuedDocumentQueueCatId').val()
}

function getLocationId () {
    return $('#QueuedDocumentLocationId').val()
}