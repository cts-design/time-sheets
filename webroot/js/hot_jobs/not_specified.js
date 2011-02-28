$(function() {
	if ($('#HotJobEmployer').val() == 'Not Specified') {
		$('#not_specified_link').attr('checked', true);
	}
	
	$('#not_specified_link').click(function() {
		if ($('#HotJobEmployer').val() != 'Not Specified') {
			$('#HotJobEmployer').val('Not Specified');
		} else {
			$('#HotJobEmployer').val('');
		}
	});
})
