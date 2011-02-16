/*
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 */
$(document).ready(function(){
    var categories = {}
    $("#documentCategoryTree").bind("move_node.jstree", function(e, data) {
	$("#documentCategoryTree li").each(function(index) {
	    categories[index] = $(this).attr('id');
	})
	$.get('/admin/documentFilingCategories/reorder_categories_ajax', categories);
    }).bind("delete_node.jstree", function (e, data) {
	window.location = '/admin/document_filing_categories/delete/' + data.rslt.obj.attr("id");
    }).bind("select_node.jstree", function (e, data) {
	$("#DocumentFilingCategoryParentId").attr('value', data.rslt.obj.attr("id"));
	$("#editCategory").attr('href', '/admin/document_filing_categories/edit/' + data.rslt.obj.attr("id"));
	node = '#' + data.rslt.obj.attr("id");
    }).jstree({
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
		    if(!p) return false;
		    p = p == -1 ? this.get_container() : p;
		    if(p === m.np) return true;
		    if(p[0] && m.np[0] && p[0] === m.np[0]) return true;
		    return false;
		}
	    }
	},
	ui : {
	    select_limit : 1
	},
	cookies: {
	    auto_save: true,
	    save_selected: 'doc_cat_tree_selected',
	    save_opened: 'doc_cat_tree_open',
	    cookie_options: {
		expires: 7,
		path: "/",
		domain: domain,
		secure: false
	    }
	},
	plugins : [ "themes", "html_data", "ui", "cookies", "types", "dnd", "crrm" ]
    });
    $('#deleteCategory').click(function(){
	$.jstree._reference("#documentCategoryTree").delete_node(node.toString());
	return false;
    });
    $('#addCategory').click(function(){
	$.jstree._reference("#documentCategoryTree").deselect_all();
	$("#DocumentFilingCategoryParentId").attr('value', '');
	$.cookie('doc_cat_tree_selected', null, {
	    path: '/',
	    domain: domain
	});
	$.cookie('doc_cat_tree_open', null ,  {
	    path: '/',
	    domain: domain
	});
	return false;
    });
    $('.expand').toggle(function(){
	$.jstree._reference("#documentCategoryTree").open_all();
	$(this).html('Collapse All');
    },
    function(){
	$.jstree._reference("#documentCategoryTree").close_all();
	$(this).html('Expand All');
    });
});