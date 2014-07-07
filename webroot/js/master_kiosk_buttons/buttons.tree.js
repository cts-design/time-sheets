$(document).ready(function(){
	var domain = window.location.hostname;

	var $tree 				= $("#masterKioskButtonTree");
	var $parent_id_field 	= $('input[name="data[MasterKioskButton][parent_id]"]');
	var $action				= $('#addform .action');
	var $parent_identify 	= $('#addform .button-identify');
	var $name_field			= $('input[name="data[MasterKioskButton][name]"]');
	var $id_field 			= $('input[name="data[MasterKioskButton][id]"');

	var $edit_button 		= $('.edit-button');
	var $delete_button		= $('.delete-button');
	var $create_button		= $('.create-button');

	var $add_form			= $('#addform');
	var $edit_form			= $('#editform');

	var $confirm_delete_button = $('.confirm-delete-button');

	var name 				= '';
	var id 					= null;

	var href				= '/admin/master_kiosk_buttons/delete/';

	$name_field.focus();

    $tree.bind("select_node.jstree", function (e, data) {
    	$add_form.show();
    	$edit_form.hide();

		id = data.rslt.obj.attr("id");
		var names = data.rslt.obj.text();
		names = names.split('\t\t');

		name = $.trim(names[0]);

		$parent_id_field.val(id);
		$parent_identify.text('for "' + name + '"');

		$name_field.val('');
		$id_field.val('');
    	$action.text('Add');

    	$edit_button.removeAttr('disabled');
    	$delete_button.removeAttr('disabled');

    	$confirm_delete_button.attr('href', href + id);

    	$name_field.focus();

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

    $name_field.keyup(function(){
    	var button_name = $(this).val();

    	if(button_name != '')
    	{
    		$create_button.removeAttr('disabled');
    	}
    	else
    	{
    		$create_button.attr('disabled', 'disabled');
    	}
    });

    $('.create-root-button').click(function(){
    	$add_form.show();
    	$edit_form.hide();
    	$parent_identify.text("");
    	$parent_id_field.val('');
    	$name_field.focus();

    	$.jstree._reference("#masterKioskButtonTree").deselect_all();
		$(this).removeClass('ui-state-focus');

		$edit_button.attr('disabled', 'disabled');
		$delete_button.attr('disabled', 'disabled');


    });

    $('.edit-button').click(function(){
    	$add_form.hide();
    	$edit_form.show();

    	//$action.text('Edit');
    	//$parent_identify.text('');

    	$id_field.val(id);

    	$name_field.val(name);
    	$name_field.focus();
    	$name_field.select();
    });

    $('.expand').toggle(function(){
		$.jstree._reference("#masterKioskButtonTree").open_all();
		$(this).html('Collapse All')
    }, function(){
		$.jstree._reference("#masterKioskButtonTree").close_all();
		$(this).html('Expand All')
    });
});



