
var statusPoll = {
	url : '/programs/esign_get_status/',
	type: 'GET',
	dataType : 'json',
	success : function(data){
		if(data.output.User.signature != "0")
		{
			$(".waiting").hide();

			var status = data.output.ProgramResponse.status;
			status = status.replace(/\_/g, " ");
			status = toTitleCase(status);

			$(".status").text(status);
			$(".status").css('color', '#60b05a');
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