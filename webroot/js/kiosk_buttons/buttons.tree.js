$(document).ready( function() {
	var domain = window.location.hostname;
	buttonId = null;
	$("#enableButton").hide();
	$("#disableButton").hide();
	$("#EditMessageButton").hide();
	$("#masterKioskButtonTree").jstree({
		themes : {
			dots : true,
			icons : false
		},
		ui : {
			select_limit : 1
		},
		cookies : {
			save_selected : false,
			save_opened : 'kiosk_master_open',
			auto_save: true
		},
		plugins : [ "themes", "html_data", "ui", "cookies", "types" ]
	})
	$("#kioskButtonTree").jstree({
		themes : {
			dots : true,
			icons : false
		},
		dnd : {
			"drop_target" : false,
			"drag_target" : false
		},
		"crrm" : {
			"move" : {
				"check_move" : function (m) {
					var p = this._get_parent(m.o);
					if(!p)
						return false;
					p = p == -1 ? this.get_container() : p;
					if(p === m.np)
						return true;
					if(p[0] && m.np[0] && p[0] === m.np[0])
						return true;
					return false;
				}
			}
		},
		ui : {
			select_limit : 1
		},
		cookies : {
			save_selected : false,
			save_opened : 'kiosk_secondary_open',
			auto_save: true,
			cookie_options: {
				expires: 7,
				path: "/",
				domain: domain,
				secure: false
			}
		},
		plugins : [ "themes", "html_data", "ui", "cookies", "types", "dnd", "crrm" ]
	})
	$("#masterKioskButtonTree").bind("select_node.jstree", function (e, data) {
		var tree1 = $.jstree._reference("kioskButtonTree");
		if(tree1 != null) {
			tree1.deselect_all();
		}
		$("#enableButton").show();
		$("#disableButton").hide();
		$("#EditMessageButton").hide();
		$("#enableButton").attr('href', '/admin/kiosk_buttons/enable_button/' + data.rslt.obj.attr("id"));
	});
	$("#kioskButtonTree").bind("select_node.jstree", function (e, data) {
		$.jstree._reference("#masterKioskButtonTree").deselect_all();
		$("#disableButton").show();
		$("#enableButton").hide();
		$("#EditMessageButton").show();
		$("#disableButton").attr('href', '/admin/kiosk_buttons/disable_button/' + data.rslt.obj.attr("id"));
		buttonId = data.rslt.obj.attr("id");
	});
	var buttons = {}
	$("#kioskButtonTree").bind("move_node.jstree", function(e, data) {
		$("#kioskButtonTree li").each( function(index) {
			buttons[index] = $(this).attr('id');
		})
		$.get('/admin/kioskButtons/reorder_buttons_ajax', buttons);
	});
	$('.expand-master').toggle( function() {
		$.jstree._reference("#masterKioskButtonTree").open_all();
		$(this).html('Collapse All')
	}, function() {
		$.jstree._reference("#masterKioskButtonTree").close_all();
		$(this).html('Expand All')
	});
	$('.expand').toggle( function() {
		$.jstree._reference("#kioskButtonTree").open_all();
		$(this).html('Collapse All')
	}, function() {
		$.jstree._reference("#kioskButtonTree").close_all();
		$(this).html('Expand All')
	});
	$( "#LogoutMessageForm" ).dialog({
		title: 'Edit Button Logout Message',
		autoOpen: false,
		modal: true,
		draggable: false,
		resizable: false,
		buttons: {
			Save: function() {
					var message = $("#KioskButtonLogoutMessage").val();				
					$.post("/admin/kiosk_buttons/edit_logout_message", { message: message, id: buttonId },
					   function(data) {
					   	 $("#LogoutMessageForm").dialog("close");
					   	 $("#Notify p").html(data.message);
					   	 $("#Notify").dialog("open");
					   }, "json");
			},
			Cancel: function() {
				$("#LogoutMessageForm").dialog("close");
			}
		}
	})
	$('#EditMessageButton').click( function() {
		$.get("/admin/kiosk_buttons/edit_logout_message/" + buttonId, function(data){
			$("#KioskButtonLogoutMessage").val(data.logout_message);
			$("#LogoutMessageForm").dialog("open");
		}, "json");			
		return false;
	})
	$("#Notify").dialog({
		title: 'Edit Button Logout Message',
		autoOpen: false,
		modal: true,
		draggable: false,
		resizable: false		
	});
});