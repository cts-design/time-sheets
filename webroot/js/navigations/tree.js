/**
 * @author Brandon Cordell
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
Ext.BLANK_IMAGE_URL = "/img/ext/default/s.gif";

Ext.onReady(function() {
    var win; // holds the Ext.Window object
    var getNodesUrl = '/admin/navigations/get_nodes/';
    var addNodeUrl = '/admin/navigations/add_node/';
    var reorderUrl = '/admin/navigations/reorder/';
    var reparentUrl = '/admin/navigations/reparent/';
    var renameNodeUrl = '/admin/navigations/rename_node/';
    var deleteNodeUrl = '/admin/navigations/delete_node/';


    /* Start add/remove button */
    var addLinkButton = new Ext.Button({
        applyTo: 'add-button-div',
        text: 'Add Link',
        cls: 'left',
        handler: function() {

            var selectedNode = tree.selModel.selNode;
            var parentId;
            
            if (!selectedNode || !selectedNode.parentNode || selectedNode.parentNode.isRoot == true) {
                parentId = tree.root.firstChild.attributes.id;
            } else {
                parentId = selectedNode.parentNode.attributes.id;
            }

            Ext.MessageBox.prompt('New Link', 'Please enter the name of the new link', function(btn, text) {
                if (btn == 'cancel' || text == '') {
                    return;
                }

                var newNodeName = text;

                Ext.MessageBox.prompt('New Link', 'Please enter the url of the ' + newNodeName + ' link', function(b,t) {
                    if (b == 'cancel' || t == '') {
                        return;
                    }

                    var newNodeUrl = t;

                    tree.disable();

                     var params = {'title':newNodeName, 'link':newNodeUrl, 'parent_id':parentId};

                    Ext.Ajax.request({
                        url:addNodeUrl,
                        params:params,
                        success:function(response, request) {
                            if (response.responseText.charAt(0) == 0){
                               request.failure();
                            } else {
                                console.log(response);

                                var newNode = new Ext.tree.TreeNode({id: response, text: newNodeName, leaf: true});

                                if (!selectedNode || !selectedNode.parentNode || selectedNode.parentNode.isRoot == true) {
                                    tree.root.firstChild.appendChild(newNode);
                                } else {
                                    selectedNode.parentNode.appendChild(newNode);
                                }
                                
                                tree.enable();
                            }
                        },
                        failure:function() {

                            // we move the node back to where it was beforehand and
                            // we suspendEvents() so that we don't get stuck in a possible infinite loop

                            tree.suspendEvents();

                            tree.resumeEvents();
                            tree.enable();

                            Ext.MessageBox.show({
                                title: 'Error',
                                msg: 'Unable to save your changes',
                                buttons: Ext.MessageBox.OK,
                                icon: Ext.MessageBox.ERROR
                            });
                        }

                    });
                    });

                

                
            });
        }
    });

    var removeLinkButton = new Ext.Button({
        applyTo: 'remove-button-div',
        text: 'Remove Selected Link',
        cls: 'left',
        handler: function() {
            var selectedNode = tree.selModel.selNode;

            if (!selectedNode) {
                Ext.MessageBox.show({
                            title: 'Error',
                            msg: 'Please select a link to delete',
                            buttons: Ext.MessageBox.OK,
                            icon: Ext.MessageBox.ERROR
                });
            } else if (!selectedNode.parentNode || selectedNode.parentNode.isRoot == true) {
                Ext.MessageBox.show({
                            title: 'Error',
                            msg: 'Cannot delete link positions',
                            buttons: Ext.MessageBox.OK,
                            icon: Ext.MessageBox.ERROR
                });
            } else {
                tree.disable();
                
                var params = {'id':selectedNode.id};

                Ext.Ajax.request({
                    url:deleteNodeUrl,
                    params:params,
                    success:function(response, request) {
                        if (response.responseText.charAt(0) != 1){
                            request.failure();
                        } else {
                            selectedNode.destroy();
                            tree.enable();
                        }
                    },
                    failure:function() {

                        // we move the node back to where it was beforehand and
                        // we suspendEvents() so that we don't get stuck in a possible infinite loop

                        tree.suspendEvents();

                        tree.resumeEvents();
                        tree.enable();

                        Ext.MessageBox.show({
                            title: 'Error',
                            msg: 'Unable to delete your link. Please try again.',
                            buttons: Ext.MessageBox.OK,
                            icon: Ext.MessageBox.ERROR
                        });
                    }

                });
            }
        }
    });
    /* End add/remove button */

    /* Start tree */
    var Tree = Ext.tree;

    var tree = new Tree.TreePanel({
        el: 'tree-div',
        autoScroll: true,
        animate: true,
        enableDD: true,
        containerScroll: true,
        rootVisible: true,
        loader: new Ext.tree.TreeLoader({
            dataUrl: getNodesUrl
        }),
        title: 'Site Navigation'
    });

    var root = new Tree.AsyncTreeNode({
        editable: false,
        text: 'Navigation Positions',
        draggable: false,
        id: 'root'
    });

    // track what nodes are moved and send to server to save
    var oldPosition = null;
    var oldNextSibling = null;

    tree.on('startdrag', function(tree, node, event){
        oldPosition = node.parentNode.indexOf(node);
        oldNextSibling = node.nextSibling;
    });

    tree.on('movenode', function(tree, node, oldParent, newParent, position){

        if (oldParent == newParent){
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
            success:function(response, request) {

                // if the first char of our response is zero, then we fail the operation,
                // otherwise we re-enable the tree

                if (response.responseText.charAt(0) != 1){
                    request.failure();
                } else {
                    tree.enable();
                }
            },
            failure:function() {

                // we move the node back to where it was beforehand and
                // we suspendEvents() so that we don't get stuck in a possible infinite loop

                tree.suspendEvents();
                oldParent.appendChild(node);
                if (oldNextSibling){
                    oldParent.insertBefore(node, oldNextSibling);
                }

                tree.resumeEvents();
                tree.enable();

                Ext.MessageBox.show({
                    title: 'Error',
                    msg: 'Unable to save your changes',
                    buttons: Ext.MessageBox.OK,
                    icon: Ext.MessageBox.ERROR
                });
            }

        });

    });


    tree.setRootNode(root);
    tree.render();
    root.expand();

    var tree_editor = new Ext.tree.TreeEditor(tree, {/* fieldconfig here */ }, {
        allowBlank:false,
        blankText:'A name is required',
        selectOnFocus:true
    });

    tree_editor.on('complete', function(treeEditor, newValue, oldValue) {
        
        if (newValue === oldValue) {
            return false;
        }

        tree.disable();

        var nodeId = treeEditor.editNode.attributes.id;

        var params = {'id': nodeId, 'title':newValue};

        Ext.Ajax.request({
            url:renameNodeUrl,
            params:params,
            success:function(response, request) {
                if (response.responseText.charAt(0) != 1){
                    request.failure();
                } else {
                    tree.enable();
                }
            },
            failure:function() {
                tree.suspendEvents();
                tree.resumeEvents();
                tree.enable();

                Ext.MessageBox.show({
                    title: 'Error',
                    msg: 'Unable to save your changes',
                    buttons: Ext.MessageBox.OK,
                    icon: Ext.MessageBox.ERROR
                });
            }

        });
    })
    /* End tree */
});