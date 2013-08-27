$(document).ready(function(){
	var select = $("#select-module");
	var resetButtons = $(".reset-select");

	select.change(function(){
		var value = { module : $(this).val() };

		var queryString = decodeURIComponent($.param(value));

		location.search = queryString;

	});

	resetButtons.click(function(e){
		var oldValue = select.val();
		var newValue = "";

		select.val(newValue);

		if(oldValue != newValue)
			select.trigger('change');
	});
});