/*
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2011
 * @link http://ctsfla.com
 */
var getnodesUrl = '/admin/document_filing_categories';
var reorderUrl = '/admin/document_filing_categories/reorder_categories_ajax';
var reparentUrl = '/admin/document_filing_categories/reparent_categories';

Ext.state.Manager.setProvider(new Ext.state.CookieProvider());
Ext.BLANK_IMAGE_URL = '/img/ext/default/s.gif';

var addCat = new Ext.Button({
	text: 'Add New Category',
	tooltip: 'Add Document Filing Category.',
	icon: '/img/icons/add.png',
	handler: function() {
		if(catName.isValid()) {
			var name = catName.getValue();
			var parent = tree.getSelectionModel().getSelectedNode();
			Ext.Ajax.request({
				url: '/admin/document_filing_categories/add',
				params: {
					parentId: parent.id,
					catName: name,
					parentPath: parent.getPath()
				},
				scope: this,
				success: function(response, options) {
					var o = {};
					try {
						o = Ext.decode(response.responseText);
					} catch(e) {
						Ext.Msg.alert('Error','Unable to save category, please try again.');
						return;
					}
					if(o.success !== true) {
						Ext.Msg.alert('Error', o.message);
					} else {
						Ext.Msg.alert('Success', o.message);
						tree.getRootNode().reload();
						tree.expandPath(o.node);
						tree.selectPath(o.node);
					}
				},
				failure: function() {
					Ext.Msg.alert('Error','Unable to save category, please try again.');
				}
			});
		}
	}
});

var disableCat = new Ext.Button({
	text: 'Disable Category'

})

var enableCat = new Ext.Button({
	text: 'Enable Category'

})

var catName = new Ext.form.TextField({
	width: 175,
	allowBlank: false
});

var tb = new Ext.Toolbar({
	width: 400,
	items: [catName, addCat, disableCat]
});

var tree = new Ext.tree.TreePanel({
	id: 'docCatTree',
	useArrows: true,
	width: 400,
	stateful: true,
	stateId: 'docCatTree',
	stateEvents: ['expandnode', 'collapsenode', 'click'],
	getState: function () {
		var nodes = [];
		var lastnode;
		this.getRootNode().eachChild( function (child) {
			//function to store state of tree recursively
			var storeTreeState = function (node, expandedNodes) {
				if (node.isExpanded() && node.childNodes.length > 0) {
					expandedNodes.push(node.getPath());
					node.eachChild( function (child) {
						storeTreeState(child, expandedNodes);
					});
				}
			};
			storeTreeState(child, nodes);
		});
		return {
			expandedNodes: nodes
		}
	},
	applyState: function (state) {
		var that = this;
		this.getLoader().on('load', function () {
			var nodes = state.expandedNodes;
			for (var i = 0; i < nodes.length; i++) {
				if (typeof nodes[i] != 'undefined') {
					that.expandPath(nodes[i]);
				}
			}
		});
	},
	animate: true,
	enableDD: true,
	containerScroll: true,
	border: false,
	tbar: tb,
	dataUrl: '/admin/document_filing_categories',
	root: {
		nodeType: 'async',
		expanded: true,
		text: 'Document Filing Catgories',
		draggable: false,
		id: 'source'
	}
});
var oldPosition = null;
var oldNextSibling = null;

tree.on('startdrag', function(tree, node, event) {
	oldPosition = node.parentNode.indexOf(node);
	oldNextSibling = node.nextSibling;
});
tree.on('movenode', function(tree, node, oldParent, newParent, position) {

	if (oldParent == newParent) {
		var url = reorderUrl;
		var params = {'node':node.id, 'delta':(position-oldPosition)};
	} else {
		var url = reparentUrl;
		var params = {'node':node.id, 'parent':newParent.id, 'position':position};
	}

	// we disable tree interaction until we've heard a response from the server
	// this prevents concurrent requests which could yield unusual results

	tree.disable();

	Ext.Ajax.request({
		url:url,
		params:params,
		success: function(response, request) {

			// if the first char of our response is zero, then we fail the operation,
			// otherwise we re-enable the tree

			if (response.responseText.charAt(0) != 1) {
				request.failure();
			} else {
				tree.enable();
			}
		},
		failure: function() {

			// we move the node back to where it was beforehand and
			// we suspendEvents() so that we don't get stuck in a possible infinite loop

			tree.suspendEvents();
			oldParent.appendChild(node);
			if (oldNextSibling) {
				oldParent.insertBefore(node, oldNextSibling);
			}

			tree.resumeEvents();
			tree.enable();

			alert("Oh no! Your changes could not be saved!");
		}
	});
});

var treeEditor = new Ext.tree.TreeEditor(tree, {}, {
	allowBlank:false,
	maxWidth: 150,
	blankText:'A name is required'
});

var confirmEdit = function() {
	var returnVal = false; 
	Ext.MessageBox.confirm(
		'Edit Category?', 
		'Editing this category name can affect the entire system.',
		function (button){
			if(button == 'no') {
				returnVal = false;
				treeEditor.cancelEdit();
			}
			else {
				treeEditor.removeListener('beforecomplete', confirmEdit);
				treeEditor.completeEdit();
				returnVal = true;
			}
		}
	);
	return returnVal;		
}

var editComplete = function(){
	treeEditor.addListener('beforecomplete', confirmEdit);
}

treeEditor.on('beforecomplete', confirmEdit);
treeEditor.on('complete', editComplete);

Ext.onReady( function() {
	Ext.QuickTips.init();
	tree.render('documentFilingCategoryTree');
});	