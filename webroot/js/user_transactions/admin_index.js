$(document).ready(function(){
	var select = $("#select-module");
	var resetButtons = $(".reset-select");
	var accord = $(".accord");
	var accordBody = $(".accord-body");

	var fromDate = $(".fromPicker");
	var toDate = $(".toPicker");

	var submitOptions = $(".submit-options");

	var query = parseString();
	console.log(query);

	//Checks if other options panel should be open
	var optionsDisplay = $.cookie('optionsPanel');
	if(optionsDisplay == 'block')
	{
		accordBody.show();
	}
	else
	{
		accordBody.hide();
	}

	select.change(function(){
		query.module = $(this).val();
		submit();
	});

	resetButtons.click(function(e){
		query.module = "";
		select.val("");
		submit();
	});

	accord.find(".accord-header").click(function(e){
		accordBody.toggle();

		$.cookie( 'optionsPanel', accordBody.css('display'), { expires : 365 } );
	});

	fromDate.datepicker();
	toDate.datepicker();

	fromDate.change(function(e){
		query.from = $(this).val();
	});

	toDate.change(function(e){
		query.to = $(this).val();
	});

	submitOptions.click(submit);

	function submit()
	{
		var stringQuery = decodeURIComponent($.param(query));
		location.search = stringQuery;
	}
});