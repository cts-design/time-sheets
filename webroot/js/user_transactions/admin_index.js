$(document).ready(function(){
	var select = $("#select-module");

	select.chance(function(){
		var value = { module : $(this).val() };

		var queryString = decodeURIComponent($.param(value));

		console.log(queryString);

	});
});