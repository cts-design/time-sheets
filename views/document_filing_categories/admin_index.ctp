<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
?>
<?php echo $this->Html->script('ext/adapter/ext/ext-base-debug', array('inline' => FALSE));?>
<?php echo $this->Html->script('ext-all-debug', array('inline' => FALSE));?>


<script type="text/javascript">
   var getnodesUrl = "<?php echo $html->url('/admin/document_filing_categories') ?>";
   var reorderUrl = "<?php echo $html->url('/admin/document_filing_categories/reorder_categories_ajax') ?>";
   var reparentUrl = "<?php echo $html->url('/admin/document_filing_categories/reparent_categories') ?>";
   Ext.state.Manager.setProvider(new Ext.state.CookieProvider());		
Ext.onReady(function(){
	Ext.QuickTips.init();
	
	var addCat = new Ext.Button({
		text: 'Add New Category',
		tooltip: 'Add Document Filing Category.',
		icon: '/img/icons/add.png',
		handler: function() {						
			console.log(parent);
			if(catName.isValid()){
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
						try {o = Ext.decode(response.responseText);}
						catch(e) {
							Ext.Msg.alert('Error','Unable to save category, please try again.');
							return;
						}
						if(o.success !== true) {
							Ext.Msg.alert('Error', o.message);
						}
						else {
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
	var catName = new Ext.form.TextField({
		width: 175,
		allowBlank: false
	});
	
    var tb = new Ext.Toolbar({
    	width: 300,
    	items: [catName, addCat]
    });	
    
	var tree = new Ext.tree.TreePanel({
		renderTo: 'documentFilingCategoryTree',
		id: 'docCatTree',
		useArrows: true,
		width: 300,
		stateful: true,
		stateId: 'docCatTree',
		stateEvents: ['expandnode', 'collapsenode', 'click'], 
		getState: function () {
            var nodes = [];
            var lastnode;
            this.getRootNode().eachChild(function (child) {
                //function to store state of tree recursively
			    var storeTreeState = function (node, expandedNodes) {
			        if (node.isExpanded() && node.childNodes.length > 0) {
			            expandedNodes.push(node.getPath());
			            node.eachChild(function (child) {
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
            
            alert("Oh no! Your changes could not be saved!");
        }
    
    });

});
	
});	
</script>


<div id="crumbWrapper">
    <span>You are here > </span>
    <?php echo $crumb->getHtml('Document Filing Categories', null, 'unique') ; ?>
</div>

<div id="documentFilingCategoryTree"></div>
