/**
 * @author Brandon Cordell
 * @copyright Complete Technology Solutions 2011
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
 
$(function() {
	var eventCategory = null;
	var currentUrl    = '/events/';

	$('body').attr('class', 'js');
	
	$('#event_categories_dropdown').live('change', function(e) {
		e.preventDefault();
		
		eventCategory =	$(this).attr('value');
		$.post(currentUrl, { event_categories_dropdown: eventCategory }, function(data) {
			$(".events").empty().html('<img src="/img/ajaxLoader.gif" height="16" width="16" />');
			$('.sub_content').html(data);
		});
	});
	
	$('.previousMonth').live('click', function(e) {
		e.preventDefault();
		
		var target = $(this).attr('href');
		$.post(target, {  }, function(data) {
			$(".events").empty().html('<img src="/img/ajaxLoader.gif" height="16" width="16" />');
			$('.sub_content').html(data);
		});
		currentUrl = target;
	});
	
	$('.nextMonth').live('click', function(e) {
		e.preventDefault();
		
		var target = $(this).attr('href');
		$.post(target, {  }, function(data) {
			$(".events").empty().html('<img src="/img/ajaxLoader.gif" height="16" width="16" />');
			$('.sub_content').html(data);
		});
		currentUrl = target;
	});
})
