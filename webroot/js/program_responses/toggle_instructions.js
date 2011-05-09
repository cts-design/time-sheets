$(document).ready(function(){
	$('#Toggle').show();
	$('#Toggle').toggle(function(){
		$('#Instructions').show();
		$('#Toggle').html('Hide Instructions');
	},
	function() {
		$('#Instructions').hide();
		$('#Toggle').html('Show Instructions');
	}
	)
})