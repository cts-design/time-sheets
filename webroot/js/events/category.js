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

    $("#events").empty().html('<img src="/img/ajaxLoader.gif" height="16" width="16" />');

    $.post(currentUrl, { event_categories_dropdown: $(this).attr('value') }, function(data) {
      $('#events').replaceWith(data);
    });
  });

  $('.previousMonth, .nextMonth, .paging a').live('click', function(e) {
    e.preventDefault();

    var target = $(this).attr('href'),
      content;

    $("#events").empty().html('<img src="/img/ajaxLoader.gif" height="16" width="16" />');

    $.post(target, {}, function(data) {
      $('#events').html(data);
    });

    currentUrl = target;
  });
});
