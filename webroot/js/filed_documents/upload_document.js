/* 
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 */
$(document).ready(function(){
    var validator = $('#FiledDocumentupload_documentForm').validate({
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
    $("#FiledDocumentCat1").change(function(){
	$.getJSON('/admin/document_filing_categories/get_child_cats_ajax',{
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
	$.getJSON('/admin/document_filing_categories/get_grand_child_cats_ajax',{
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
})
function populateChildCatsList(childCats) {
    var options = '<option value="">Select 2nd Cat</option>';
    $.each(childCats, function(index, childCat) {
	options += '<option value="' + index + '">' + childCat + '</option>';
    });
    $('#FiledDocumentCat2').html(options);
}
function populateGrandChildCatsList(grandCats) {
    var options = '<option value="">Select 3rd Cat</option>';
    $.each(grandCats, function(index, grandCat) {
	options += '<option value="' + index + '">' + grandCat + '</option>';
    });
    $('#FiledDocumentCat3').html(options);
}
function resetGrandChildCatsList() {
    options = '<option value="">Select 3rd Cat</option>';
    $('#FiledDocumentCat3').html(options);
}
