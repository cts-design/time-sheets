/*
 * @author Brandon Cordell
 * @copyright Complete Technology Solutions 2013
 * @link http://ctsfla.com
 */
$(function() {
  var $registrationForm = $('#ChildRegistrationForm'),
    $submitButton = $registrationForm.find('input[type="submit"]');

  $submitButton.button();
  $registrationForm.submit(function() {
    $submitButton.button('disable');
    return true;
  });

  $('.go-back').click(function(e){
  	e.preventDefault();

  	window.history.back();
  });
});
