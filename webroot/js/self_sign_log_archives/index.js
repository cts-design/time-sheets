/*
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 */

var allVals = [];
$(document).ready(function() {
	$("#search").accordion({
		collapsible : true,
		active : false
	});
	$('.date').datepicker();
	$.getJSON('/admin/self_sign_log_archives/get_parent_buttons_ajax', {
		ids : ''
	}, function(buttons) {
		if(buttons != null) {
			populateButton1Dropdown(buttons);
		}
	})
	$('.scrollingCheckboxes').change(function() {
		allVals = [];
		resetDropdown2();
		resetDropdown3();
		$('.scrollingCheckboxes :checked').each(function() {
			allVals.push($(this).val());
		});
		$.getJSON('/admin/self_sign_log_archives/get_parent_buttons_ajax', {
			ids : allVals
		}, function(buttons) {
			if(buttons != null) {
				populateButton1Dropdown(buttons);
			}
		})
	});
	$('#SelfSignLogArchiveButton1').change(function() {
		if($('#SelfSignLogArchiveLocations').val() != null) {
			$.getJSON('/admin/self_sign_log_archives/get_child_buttons_ajax', {
				id : $(this).val(),
				location : $('#SelfSignLogArchiveLocations').val()
			}, function(buttons) {
				if(buttons != null) {
					populateButton2Dropdown(buttons);
					resetDropdown3();
				}
			})
		} else {
			$.getJSON('/admin/self_sign_log_archives/get_child_buttons_ajax', {
				id : $(this).val()
			}, function(buttons) {
				if(buttons != null) {
					populateButton2Dropdown(buttons);
					resetDropdown3();
				}
			})
		}
	});
	$('#SelfSignLogArchiveButton2').change(function() {
		$.getJSON('/admin/self_sign_log_archives/get_grand_child_buttons_ajax', {
			id : $(this).val()
		}, function(buttons) {
			if(buttons != null) {
				populateButton3Dropdown(buttons);
			}
		})
	});
	var options = {
		target : '#ajaxContent',
		beforeSubmit : function() {
			$('#processing').show();
		},
		success : function() {
			$('#processing').hide();
		}
	}
	$('#SelfSignLogArchiveIndexForm').ajaxForm(options);
	$('#reset').click(function() {
		$('#SelfSignLogArchiveIndexForm').resetForm();
	})
	$('#report').bind('click', function() {

		$('#searchLocations').val(allVals);
		$('#searchButton1').val($('#SelfSignLogArchiveButton1').val());
		$('#searchButton2').val($('#SelfSignLogArchiveButton2').val());
		$('#searchButton3').val($('#SelfSignLogArchiveButton3').val());
		$('#searchStatus').val($('#SelfSignLogArchiveStatus').val());
		$('#searchDateFrom').val($('#SelfSignLogArchiveDateFrom').val());
		$('#searchDateTo').val($('#SelfSignLogArchiveDateTo').val());
	})
});
function populateButton1Dropdown(buttons) {
	var options = '<option value="">All Buttons</option>';
	if(buttons !== null) {
		$.each(buttons, function(index, button) {
			options += '<option value="' + index + '">' + button + '</option>';
		});
		$('#SelfSignLogArchiveButton1').html(options);
	}
}

function populateButton2Dropdown(buttons) {
	var options = '';
	if(buttons !== null) {
		$.each(buttons, function(index, button) {
			options += '<option value="' + index + '">' + button + '</option>';
		});
		$('#SelfSignLogArchiveButton2').html(options);
	}
}

function populateButton3Dropdown(buttons) {
	var options = '';
	if(buttons !== null) {
		$.each(buttons, function(index, button) {
			options += '<option value="' + index + '">' + button + '</option>';
		});
		$('#SelfSignLogArchiveButton3').html(options);
	}
}

function resetDropdown2() {
	var options = '<option value="">All Buttons</option>';
	$('#SelfSignLogArchiveButton2').html(options);
}

function resetDropdown3() {
	var options = '<option value="">All Buttons</option>';
	$('#SelfSignLogArchiveButton3').html(options);
}