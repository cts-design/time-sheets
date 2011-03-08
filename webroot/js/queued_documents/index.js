/* 
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 */
$(document).ready(function(){
    var validator = $('#QueuedDocumentFileDocumentForm').validate({
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
    $('.reset').button();
    $('.view').button();
    $.getJSON('/admin/document_filing_categories/get_child_cats',{
	id: $("#FiledDocumentCat1").val()
    },
    function(childCats) {
	if(childCats != '') {
	    resetGrandChildCatsList();
	    populateChildCatsList(childCats);
	    $('#FiledDocumentCat1').trigger('change');
	    $('#FiledDocumentCat2').trigger('change');
	}
    });

    $("#FiledDocumentCat1").change(function(){
	$.getJSON('/admin/document_filing_categories/get_child_cats',{
	    id: $(this).val()
	},
	function(childCats) {
	    if(childCats != '') {
		$('#FiledDocumentCat2').show().addClass('required');
		resetGrandChildCatsList();
		populateChildCatsList(childCats);
	    }
	    else {
		$('#FiledDocumentCat2').hide().removeClass('required');
		$('#FiledDocumentCat3').hide().removeClass('required');
	    }
	});
    });
    $("#FiledDocumentCat2").change(function(){
	$.getJSON('/admin/document_filing_categories/get_grand_child_cats',{
	    id: $(this).val()
	},
	function(grandCats) {
	    if(grandCats != '') {
		$('#FiledDocumentCat3').show().addClass('required');
		populateGrandChildCatsList(grandCats);
	    }
	    else {
		$('#FiledDocumentCat3').hide().removeClass('required');
	    }
	});
    });
    $('.date').datepicker();
    var needToConfirm = true;
    $(window).bind('beforeunload', function() {
	if(needToConfirm)
	    return 'If you have a document open it will be returned to the queue!' ;

    });
    $(window).unload(function(){
	if(needToConfirm)
	    $.ajax({
		url: '/admin/queued_documents/index/reset/',
		async: false
	    });
    });
    $('.reset, input:submit, .view, .next, .prev, .add-customer').live('click', function(){
	needToConfirm = false;
    });
    var $bar = $('#idletimeout'), // id of the warning div
    $countdown = $bar.find('span'), // span tag that will hold the countdown value
    redirectAfter = 10, // number of seconds to wait before redirecting the user
    redirectTo = '/admin/queued_documents/index/resetInactive', // URL to relocate the user to once they have timed out
    expiredMessage = 'If you had a document open it has been return to the queue.', // message to show user when the countdown reaches 0
    running = false, // var to check if the countdown is running
    timer; // reference to the setInterval timer so it can be stopped

    // start the idle timer.  the user will be considered idle after 2 seconds (2000 ms)
    $.idleTimer(60000);

    // bind to idleTimer's idle.idleTimer event
    $(document).bind("idle.idleTimer", function(){

	// if the user is idle and a countdown isn't already running
	if( $.data(document,'idleTimer') === 'idle' && !running ){
	    var counter = redirectAfter;
	    running = true;

	    // set inital value in the countdown placeholder
	    $countdown.html( redirectAfter );

	    // show the warning bar
	    $bar.slideDown();

	    // create a timer that runs every second
	    timer = setInterval(function(){
		counter -= 1;

		// if the counter is 0, redirect the user
		if(counter === 0){
		    $bar.html( expiredMessage );
		    needToConfirm = false;
		    window.location.href = redirectTo;
		} else {
		    $countdown.html( counter );
		};
	    }, 1000);
	};
    });

    // if the continue link is clicked..
    $("a", $bar).click(function(){

	// stop the timer
	clearInterval(timer);

	// stop countdown
	running = false;

	// hide the warning bar
	$bar.slideUp();

	return false;
    });
    $('#FiledDocumentFirstname').autocomplete({
	source: '/admin/queued_documents/auto_complete_first_ajax',
	minLength: 2,
	close: function(){
	    populateUserInfo($('#FiledDocumentFirstname').val());
	}
    });
    $('#FiledDocumentLastname').autocomplete({
	source: '/admin/queued_documents/auto_complete_last_ajax',
	minLength: 2,
	close: function(){
	    populateUserInfo($('#FiledDocumentLastname').val());
	}
    });
    $('#FiledDocumentSsn').autocomplete({
	source: '/admin/queued_documents/auto_complete_ssn_ajax',
	minLength: 2,
	close: function(){
	    populateUserInfo($('#FiledDocumentSsn').val());
	}
    });
    $('.add-customer').bind('click', function(){
	$('#customerAddForm').slideDown('slow');
	$('#cusNotfound').hide();
	$('#closeCusNotfound').show();
	return false;
    });
    $('.close-add-customer').bind('click', function(){
	$('#customerAddForm').slideUp('slow');
	$('#cusNotfound').show();
	$('#closeCusNotfound').hide();
	return false;
    });
});

function populateUserInfo(str) {
    var exploded = str.split(',');
    if(exploded[0] && exploded[1] && exploded[2]) {
	$('#FiledDocumentLastname').val($.trim(exploded[0])).removeClass('error');
	$('#FiledDocumentFirstname').val($.trim(exploded[1])).removeClass('error');
	$('#FiledDocumentSsn').val($.trim(exploded[2])).removeClass('error');
    }
}

function populateChildCatsList(childCats) {
    var options = '<option value="">Select 2nd Cat</option>';
    $.each(childCats, function(index, childCat) {
	if(index == cat2) {
	    options += '<option selected="selected" value="' + index + '">' + childCat + '</option>';
	}
	else {
	    options += '<option value="' + index + '">' + childCat + '</option>';
	}
    });
    $('#FiledDocumentCat2').html(options);
}
function populateGrandChildCatsList(grandCats) {
    var options = '<option value="">Select 3rd Cat</option>';
    $.each(grandCats, function(index, grandCat) {
	if(index == cat3) {
	    options += '<option selected="selected" value="' + index + '">' + grandCat + '</option>';
	}
	else {
	    options += '<option value="' + index + '">' + grandCat + '</option>';
	}
    });
    $('#FiledDocumentCat3').html(options);
}
function resetGrandChildCatsList() {
    options = '<option value="">Select 3rd Cat</option>';
    $('#FiledDocumentCat3').html(options);
}