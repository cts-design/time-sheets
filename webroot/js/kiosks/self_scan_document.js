/*
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 */
var timer;
var $bar;
var running;
$(document).ready(function(){
    $('.scan').button();
    $('.save, .re-scan').button({disabled: true});

    $bar = $('#idletimeout'), // id of the warning div
    $countdown = $bar.find('span'), // span tag that will hold the countdown value
    redirectAfter = 10, // number of seconds to wait before redirecting the user
    redirectTo = '/users/logout', // URL to relocate the user to once they have timed out
    expiredMessage = 'You will now be logged out.', // message to show user when the countdown reaches 0
    running = false, // var to check if the countdown is running
    timer; // reference to the setInterval timer so it can be stopped

    // start the idle timer.  the user will be considered idle after 60 seconds (60000 ms)
    $.idleTimer(60000);

    // bind to idleTimer's idle.idleTimer event
   $(document).bind("idle.idleTimer", function(){

	// if the user is idle and a countdown isn't already running
	if( $.data(document,'idleTimer') === 'idle' && !running ){
	    var counter = redirectAfter;
	    running = true;

	    // set inital value in the countdown placeholder
	    $countdown.html( redirectAfter );

	    // show the warning bar
	    $bar.slideDown();

	    // create a timer that runs every second
	    timer = setInterval(function(){
		counter -= 1;

		// if the counter is 0, redirect the user
		if(counter === 0){
		    $bar.html( expiredMessage );
		    needToConfirm = false;
		    window.location.href = redirectTo;
		} else {
		    $countdown.html( counter );
		}
	    }, 1000);
	}
    });
    $(document).unbind('mousemove');
    // if the continue link is clicked..
    $("a", $bar).click(function(){
	stopTimeout();
	return false;
    });

});

function stopTimeout() {
    clearInterval(timer);
    // stop countdown
    running = false;
    // hide the warning bar
    $bar.slideUp();
}

function disableScan() {
    $('.scan').button({disabled: true});
}
function enableReScan(){
    $('.save, .re-scan').button({disabled: false});
}

