$(document).ready(function(){
	var select = $("#select-module");

	select.change(function(){
		var value = { module : $(this).val() };

		var queryString = decodeURIComponent($.param(value));

		location.search = queryString;

	});
});