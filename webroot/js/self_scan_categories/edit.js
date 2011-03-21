/*
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 */


$(document).ready(function(){
    var validator = $('#SelfScanCategoryAdminEditForm').validate({
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
    $.getJSON('/admin/document_filing_categories/get_child_cats',{
	id: $("#SelfScanCategoryCat1").val()
    },
    function(childCats) {
	if(childCats != '') {
	    resetGrandChildCatsList();
	    populateChildCatsList(childCats);
	    $('#SelfScanCategoryCat1').trigger('change');
	    $('#SelfScanCategoryCat2').trigger('change');
	}
    });
    $("#SelfScanCategoryCat1").change(function(){
	$.getJSON('/admin/document_filing_categories/get_child_cats',{
	    id: $(this).val()
	},
	function(childCats) {
	    if(childCats != '') {
		$('#SelfScanCategoryCat2').show().addClass('required');
		resetGrandChildCatsList();
		populateChildCatsList(childCats);
	    }
	    else {
		$('#SelfScanCategoryCat2').hide().removeClass('required');
		$('#SelfScanCategoryCat3').hide().removeClass('required');
	    }
	});
    });
    $("#SelfScanCategoryCat2").change(function(){
	$.getJSON('/admin/document_filing_categories/get_grand_child_cats',{
	    id: $(this).val()
	},
	function(grandCats) {
	    if(grandCats != '') {
		$('#SelfScanCategoryCat3').show().addClass('required');
		populateGrandChildCatsList(grandCats);
	    }
	    else {
		$('#SelfScanCategoryCat3').hide().removeClass('required');
	    }
	});
    });
})
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
    $('#SelfScanCategoryCat2').html(options);
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
    $('#SelfScanCategoryCat3').html(options);
}
function resetGrandChildCatsList() {
    options = '<option value="">Select 3rd Cat</option>';
    $('#SelfScanCategoryCat2').html(options);
}
