$(document).ready(function(){
	var domain = window.location.hostname;

    $("#masterKioskButtonTree").bind("select_node.jstree", function (e, data) {

		node = '#' + data.rslt.obj.attr("id");
		$("#MasterKioskButtonParentId").attr('value', data.rslt.obj.attr("id"));
		$("#editButton").attr('href', '/admin/master_kiosk_buttons/edit/' + data.rslt.obj.attr("id"));

    }).bind("delete_node.jstree", function (e, data) {
		window.location = '/admin/master_kiosk_buttons/delete/' + data.rslt.obj.attr("id");
    }).jstree({
	themes : {
	    dots : true,
	    icons : false
	},
	ui : {
	    select_limit : 1
	},
	cookies: {
		auto_save: true,
		save_selected: 'master_kiosk_buttons_selected',
		save_opened: 'master_kiosk_buttons_opened',
		cookie_options: {
		    expires: 7,
		    path: "/",
		    domain: domain,
		    secure: false
		}
	},
	plugins : [ "themes", "html_data", "ui", "cookies", "types" ]
    });
    $("#addButton").click(function(){
	$("#MasterKioskButtonParentId").attr('value', '');
	$.cookie('master_kiosk_buttons_selected', null, {domain: domain, path: '/'});
	$.cookie('master_kiosk_buttons_opened', null, {domain: domain, path: '/'});
	$.jstree._reference("#masterKioskButtonTree").deselect_all();
	$(this).removeClass('ui-state-focus');
    });
    $("#deleteButton").click(function(){
	$.jstree._reference("#masterKioskButtonTree").delete_node(node.toString());
	$(this).removeClass('ui-state-focus');
	return false;
    });
    $('.expand').toggle(function(){
	$.jstree._reference("#masterKioskButtonTree").open_all();
	$(this).html('Collapse All')
    },
    function(){
	$.jstree._reference("#masterKioskButtonTree").close_all();
	$(this).html('Expand All')
    });
});