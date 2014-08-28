/*
  Author : Joseph Shering
  Date : 08/28/2014
*/

$(document).ready(function() {

  var icon = $('#dialog-message .fa');

  if(flash != '') {
    
    if(flash.match(/successfully/)) {
      icon.addClass('.fa-times');
      icon.removeClass('.fa-check');
    }
    else
    {
      icon.addClass('.fa-check');
      icon.removeClass('.fa-times');
    }

    $('.flash-message').html(flash);
    $('#dialog-message').dialog({
      modal: true,
      buttons: {
        Ok: function() {
          $(this).dialog('close');
        }
      }
    });
  }

  // Resets the form and hits submit
  $("#reset_filters").click(function(e){
    e.preventDefault();

    var $events_category  = $('#event_categories_dropdown');
    var $events_location  = $('#event_locations_dropdown');
    var $events_form      = $('.event_categories');

    $events_category.val('');
    $events_location.val('');

    $events_form.submit();
  });
});