/* 
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 */

$(document).ready(function(){
    var validator = $('#SelfScanCategoryAdminAddForm').validate({
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

    $("#SelfScanCategoryCat1").change(function(){
	$.getJSON('/admin/document_filing_categories/get_child_cats_ajax',{
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
	$.getJSON('/admin/document_filing_categories/getGrandChildCatsAjax',{
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
	$('#SelfScanCategoryHasChildren').change(function(){
	    if($(this).val() == 'yes') {
		$('#SelfScanCategoryQueueCatId').removeClass('required');
		$('#SelfScanCategoryCat1').removeClass('required');
		$('.hide').hide();

	    }
	    else {
		$('#SelfScanCategoryQueueCatId').addClass('required');
		$('#SelfScanCategoryCat1').addClass('required');
		$('.hide').show();
	    }
	})
});

function populateChildCatsList(childCats) {
    if(childCats != '') {
	var options = '<option value="">Select 2nd Cat</option>';
    }
    $.each(childCats, function(index, childCat) {
	options += '<option value="' + index + '">' + childCat + '</option>';
    });
    $('#SelfScanCategoryCat2').html(options);
}

function populateGrandChildCatsList(grandCats) {
    var options = '<option value="">Select 3rd Cat</option>';
    $.each(grandCats, function(index, grandCat) {
	options += '<option value="' + index + '">' + grandCat + '</option>';
    });
    $('#SelfScanCategoryCat3').html(options);
}

function resetGrandChildCatsList() {
    options = '<option value="">Select 3rd Cat</option>';
    $('#SelfScanCategoryCat3').html(options);
}