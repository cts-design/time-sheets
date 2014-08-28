/*
  Author : Joseph Shering
  Date : 08/28/2014
*/

$(document).ready(function() {
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