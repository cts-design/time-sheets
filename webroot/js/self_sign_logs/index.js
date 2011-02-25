/* 
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 */

var locationId = '';
var service = '';
var refreshId = 0;
$(document).ready(function(){
    $.ajaxSetup({
	cache: false
    });
    refreshId = setInterval('loadLogs()', 10000);
    $('body').delegate('#SelfSignLogLocations', 'change', function() {
	locationId = $('#SelfSignLogLocations').val();
	service = '';
	if($(this).val().length != 0) {
	    $.getJSON('/admin/self_sign_logs/get_services_ajax',
	    {
		id: $(this).val()
		},
	    function(services) {
		if(services !== null) {
		    populateServicesList(services);
		}
	    });
	}
	else {
	    $.getJSON('/admin/self_sign_logs/get_services_ajax',
		function(services) {
		    if(services !== null) {
			populateServicesList(services);
		    }
		});
	}
	loadLogs();
    });
    $('body').delegate('.scrollingCheckboxes', 'change', function() {
	var allVals = [];
	 $('.scrollingCheckboxes :checked').each(function() {
	   allVals.push($(this).val());
	 });
	if(allVals != null) {
	    service = allVals;
	}
	if($(this).val() == null) {
	    service = '';
	}
	loadLogs();
    });
    loadLogs();
    $(".toggle").live('mouseover mouseout', function(event) {
	if(event.type == 'mouseover'){
	    clearInterval(refreshId);
	}
	if(event.type == 'mouseout') {
	    refreshId = setInterval('loadLogs()', 10000);
	}
    });

    $('.toggle').live('click', function(){
	var url = $(this).attr('href');
	$.post(url, function(data){
	    loadLogs();
	});
	return false;
    })
});

function loadLogs() {
    $('#selfSignLogs').load('/admin/self_sign_logs/get_logs_ajax?location='+locationId + '&service='+service);
}

function populateServicesList(services) {

    var checks = '';
    $.each(services, function(index, service) {

	checks += '<div class="input checkbox"><input type="checkbox" value="' + index + '" name="data[SelfSignLog][Services][]"/>';

	checks += '<label for="data[SelfSignLog][Services][]">' + service + '</label></div>';
	});
    $('.scrollingCheckboxes').html(checks);
}

