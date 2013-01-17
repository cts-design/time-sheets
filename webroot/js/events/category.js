/**
 * @author Brandon Cordell
 * @copyright Complete Technology Solutions 2011
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
$(function() {
  var eventCategory = 0,
    currentUrl    = '/events/';

console.log(window);

  $('body').attr('class', 'js');

  $('#event_categories_dropdown').live('change', function(e) {
    e.preventDefault();

    eventCategory = $(this).attr('value');

    $.post(currentUrl, { event_categories_dropdown: eventCategory }, function(data) {
      $("#events").empty().html('<img src="/img/ajaxLoader.gif" height="16" width="16" />');

      var content = $(data).find('#events');
      $('#events').html(content);
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
