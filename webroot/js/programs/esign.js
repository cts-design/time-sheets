
/*
*	Object that get's passed to $.ajax method
*/
var statusPoll = {
	url : '/programs/esign_get_status/',
	type: 'GET',
	dataType : 'json',
	success : function(data){
		console.log(data);
		if(data.output.User.signature != "0")
		{
			$(".waiting").hide();

			$(".status").text("E-Signature Recieved");
			$(".status").css('color', '#60b05a');

			setTimeout(function(){
				var fullQuery = parseString();

				if(typeof fullQuery.redirect !== 'undefined' || fullQuery.redirect !== '')
				{
					location.pathname = fullQuery.redirect;
				}
				else
				{
					location.pathname = '/users/dashboard';
				}
			}, 1000);
		}
	},
	error : reportError
};

/*
*	$.ajax object error function
*/
function reportError(data, error)
{
	console.log(data);
	console.log(error);
}

$(document).ready(function(){
	var path = location.pathname.split(/\//);
	var id = path[ path.length-1 ];

	//Add's the program id to the end of the url for redirecting
	statusPoll.url += id;
	
	$.ajax(statusPoll);

	setInterval(function(){
		$.ajax(statusPoll);
	}, 4000);
});

/*
*	Used to capitalize letters
*/
function toTitleCase(str)
{
    return str.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
}