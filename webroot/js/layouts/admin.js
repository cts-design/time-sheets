/* 
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 */

Ext.SSL_SECURE_URL = "about:blank";

$(document).ready(function(){
    $('th a').on('click', function(){
	$(this).parent().addClass('ui-state-active')
    })
    if($('th a').hasClass('desc') || $('th a').hasClass('asc')) {
	$('.asc, .desc').parent().addClass('ui-state-hover');
    }
    $('input:submit').button();
    $('.actions a, button ').button();
    $('.actions').buttonset();
    $('.edit').button({
	icons:{
	    primary: 'ui-icon-pencil'
	}
    });
$('.permissions').button({
    icons:{
	primary: 'ui-icon-locked'
    }
});
$('.add').button({
    icons:{
	primary: 'ui-icon-plus'
    }
});
$('.delete').button({
    icons:{
	primary: 'ui-icon-trash'
    }
});
$('.buttons').button({
    icons:{
	primary: 'ui-icon-edit'
    }
});
$('.docs').button({
    icons:{
	primary: 'ui-icon-document'
    }
});
$('.activity').button({
    icons:{
	primary: 'ui-icon-clipboard'
    }
});
$('.restore').button({
    icons:{
	primary: 'ui-icon-arrowreturnthick-1-w'
    }
});
$('.view').button({
    icons:{
	primary: 'ui-icon-search'
    }
});
$('.questions').button({
  icons: {
    primary: 'ui-icon-help'
  }
});
$('.responses').button({
  icons: {
    primary: 'ui-icon-comment'
  }
});
if($('.actions ul').text() == '') {
    $('div.actions').hide();
}
});
