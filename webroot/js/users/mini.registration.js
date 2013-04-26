/* 
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2011
 * @link http://ctsfla.com
 */


$(document).ready(function() {
	$('#UserKioskMiniRegistrationForm').submit(function() {
		$('.self-sign-kiosk-button, .self-sign-kiosk-link').button({
			disabled : true
		});
		return true;
	});
});
