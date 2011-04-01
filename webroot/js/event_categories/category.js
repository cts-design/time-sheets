/**
 * @author Brandon Cordell
 * @copyright Complete Technology Solutions 2011
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
 
$(function() {
	var eventCategory = 0;
	var currentUrl    = '/events/';
	
	$('body').attr('class', 'js');
	
	$('#event_categories_dropdown').live('change', function(e) {
		e.preventDefault();
		
		eventCategory =	$(this).attr('value');
		$.post(currentUrl, { event_categories_dropdown: eventCategory }, function(data) {
			$(".allEvents").empty().html('<img src="/img/ajaxLoader.gif" height="16" width="16" />');

			var content = $(data).find('.allEvents');
			$('.allEvents').html(content);
		});
	});
	
	$('.previousMonth').live('click', function(e) {
		e.preventDefault();

		var target = $(this).attr('href');
		$.post(target, {}, function(data) {
			$(".allEvents").empty().html('<img src="/img/ajaxLoader.gif" height="16" width="16" />');

			var content = $(data).find('.allEvents');
			$('.allEvents').html(content);
		});
		currentUrl = target;
	});
	
	$('.nextMonth').live('click', function(e) {
		e.preventDefault();
		
		var target = $(this).attr('href');
		$.post(target, {}, function(data) {
			$(".allEvents").empty().html('<img src="/img/ajaxLoader.gif" height="16" width="16" />');
			
			var content = $(data).find('.allEvents');
			$('.allEvents').html(content);
		});
		currentUrl = target;
	});
	
	$('.paging a').live('click', function(e) {
		e.preventDefault();
		
		var target = $(this).attr('href');
		$.post(target, {}, function(data) {
			$(".allEvents").empty().html('<img src="/img/ajaxLoader.gif" height="16" width="16" />');
			
			var content = $(data).find('.allEvents');
			$('.allEvents').html(content);
		});
		currentUrl = target;
	});
})
