/* 
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 */

$(document).ready(function(){
    $('#UserSelfSignLoginForm').validate({
	errorLabelContainer: "#errorMessage",
	wrapper: "p",
	rules: {
	    'data[User][dob]' : {
		date : true
	    }
	},
	messages : {
	    'data[User][dob]' : {
		date : 'Please enter birth date in this format dd/mm/yyyy'
	    }
	}
    });
})