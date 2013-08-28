
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
				location.pathname = fullQuery.redirect;
			}, 1);
		}
	},
	error : reportError
};

function reportError(data, error)
{
	console.log(data);
	console.log(error);
}

$(document).ready(function(){
	var path = location.pathname.split(/\//);
	var id = path[ path.length-1 ];
	statusPoll.url += id;
	
	$.ajax(statusPoll);
	setInterval(function(){
		$.ajax(statusPoll);
	}, 4000);
});

function toTitleCase(str)
{
    return str.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
}