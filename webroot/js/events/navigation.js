/**
 * @author Brandon Cordell
 * @copyright Complete Technology Solutions 2011
 * @link http://ctsfla.com
 * @package ATLAS V3
 */

var currentPath,
  queryString;

currentPath = function () {
  return window.location.pathname;
}

queryString = function(url) {
  return url.slice(url.indexOf('?')).split('&')[0];
}

$(function() {
  var eventCategory = 0,
    currentUrl = currentPath(),
    currentQueryString;

  $('body').attr('class', 'js');

  $('#event_categories_dropdown').live('change', function(e) {
    e.preventDefault();

    $.get(currentUrl, $('.event_categories').serialize(), function(data) {
      $('#events').replaceWith(data);
    });

    $("#events").empty().html('<img src="/img/ajaxLoader.gif" height="16" width="16" />');
  });

  $('#event_locations_dropdown').live('change', function(e) {
    e.preventDefault();

    $.get(currentUrl, $('.event_categories').serialize(), function(data) {
      $('#events').replaceWith(data);
    });

    $("#events").empty().html('<img src="/img/ajaxLoader.gif" height="16" width="16" />');
  });

  $('.pagination a').live('click', function(e) {
    e.preventDefault();

    var target = $(this).attr('href'),
      content;

    if (currentQueryString) {
      target += currentQueryString;
    }

    $.get(target, $('.event_categories').serialize(), function(data) {
      $('#events').html(data);
    });

    $("#events").empty().html('<img src="/img/ajaxLoader.gif" height="16" width="16" />');

    currentUrl = target;
  });

  $('.calnav a').live('click', function(e) {
    e.preventDefault();

    var target = $(this).attr('href'),
      content;

    currentQueryString = queryString(target);

    $.get(target, $('.event_categories').serialize(), function(data) {
      $('#events').html(data);
    });

    $("#events").empty().html('<img src="/img/ajaxLoader.gif" height="16" width="16" />');

    currentUrl = target;
  });

  $('#reset_filters').live('click', function(e) {
    e.preventDefault();

    $('#event_locations_dropdown').val(0);
    $('#event_categories_dropdown').val(0);

    $.get(currentUrl, $('.event_categories').serialize(), function(data) {
      $('#events').html(data);
    });

    $("#events").empty().html('<img src="/img/ajaxLoader.gif" height="16" width="16" />');
  });
});
