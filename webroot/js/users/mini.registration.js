/* 
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2011
 * @link http://ctsfla.com
 */


$(document).ready(function() {
	$('#UserSsn, #UserSsnConfirm, #UserSsn1, #UserSsn2, #UserSsn3, #UserSsn1Confirm, #UserSsn2Confirm, #UserSsn3Confirm ').dPassword();
	$('#UserKioskMiniRegistrationForm').submit(function() {
		$('.self-sign-kiosk-button, .self-sign-kiosk-link').button({
			disabled : true
		});
		return true;
	});
});