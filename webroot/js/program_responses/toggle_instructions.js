$(document).ready(function(){
	$('#Toggle').show();
	$('#Toggle').toggle(function(){
		$('#Instructions').hide();
		$('#Toggle').html('Show Instructions');	
	},
	function() {
		$('#Instructions').show();
		$('#Toggle').html('Hide Instructions');
	})
})