/**
 * @author Brandon Cordell
 * @copyright Complete Technology Solutions 2011
 * @link http://ctsfla.com
 * @package ATLAS V3
 */

var currentPath = function () {
  return window.location.pathname;
}

$(function() {
  var eventCategory = 0,
    currentUrl = currentPath();

  $('body').attr('class', 'js');

  $('#event_categories_dropdown').live('change', function(e) {
    e.preventDefault();

    $.post(currentUrl, $('.event_categories').serialize(), function(data) {
      $('#events').replaceWith(data);
    });

    $("#events").empty().html('<img src="/img/ajaxLoader.gif" height="16" width="16" />');
  });

  $('#event_locations_dropdown').live('change', function(e) {
    e.preventDefault();

    $.post(currentUrl, $('.event_categories').serialize(), function(data) {
      $('#events').replaceWith(data);
    });

    $("#events").empty().html('<img src="/img/ajaxLoader.gif" height="16" width="16" />');
  });

  $('.calnav a, .paging a').live('click', function(e) {
    e.preventDefault();

    var target = $(this).attr('href'),
      content;

    $.post(target, $('.event_categories').serialize(), function(data) {
      $('#events').html(data);
    });

    $("#events").empty().html('<img src="/img/ajaxLoader.gif" height="16" width="16" />');

    currentUrl = target;
  });

  $('#reset_filters').live('click', function(e) {
    e.preventDefault();

    $('#event_locations_dropdown').val(0);
    $('#event_categories_dropdown').val(0);

    $.post(currentUrl, $('.event_categories').serialize(), function(data) {
      $('#events').html(data);
    });

    $("#events").empty().html('<img src="/img/ajaxLoader.gif" height="16" width="16" />');
  });
});
