/* 
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 */


$(document).ready(function(){

    var validator = $('#FiledDocumentAdminEditForm').validate({
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
    $('#UserFirstname').autocomplete({
	source: '/admin/filed_documents/auto_complete_first',
	minLength: 2,
	close: function(){
	    populateUserInfo($('#UserFirstname').val());
	}
    });
    $('#UserLastname').autocomplete({
	source: '/admin/filed_documents/auto_complete_last',
	minLength: 2,
	close: function(){
	    populateUserInfo($('#UserLastname').val());
       }
    });
    $('#UserSsn').autocomplete({
	source: '/admin/filed_documents/auto_complete_ssn',
	minLength: 2,
	close: function(){
	    populateUserInfo($('#UserSsn').val());
       }
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
function populateUserInfo(str) {
    var exploded = str.split(',');
    if(exploded[0] && exploded[1] && exploded[2]) {
	$('#UserLastname').val($.trim(exploded[0]));
	$('#UserFirstname').val($.trim(exploded[1]));
	$('#UserSsn').val($.trim(exploded[2]));
    }
}