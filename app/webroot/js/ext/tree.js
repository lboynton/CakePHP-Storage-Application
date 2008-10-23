Ext.BLANK_IMAGE_URL = '/img/ext/s.gif';

var checkedNodes = new Array();

function showChecked()
{
	alert(checkedNodes.join());	
}

Ext.onReady(function()
{    
    var getnodesUrl = '/backups/get_nodes';
    var reorderUrl = '/backups/reorder';
    var reparentUrl = '/backups/reparent';
    
    var Tree = Ext.tree;
	
	var loader = new Ext.tree.TreeLoader
	({
		dataUrl:getnodesUrl
	});
    
    var tree = new Tree.TreePanel
	({
        el				: 'tree-div',
        autoScroll		: true,
        animate			: true,
        enableDD		: true,
        containerScroll	: true,
        rootVisible		: true,
		border	 		: true,
        loader			: loader
    });
	
	// add listener to changes to checkboxes
	tree.on("checkchange", function (node, checked)
	{
		checkedNodes[node.id] = checked;
	});
    
    var root = new Tree.AsyncTreeNode
	({
        text:'Files',
        draggable:false,
        id:'root'
    });
	
    tree.setRootNode(root);
    
	// track what nodes are moved and send to server to save

	var oldPosition = null;
	var oldNextSibling = null;
	
	tree.on('startdrag', function(tree, node, event)
	{
		oldPosition = node.parentNode.indexOf(node);
		oldNextSibling = node.nextSibling;
	});

	tree.on('movenode', function(tree, node, oldParent, newParent, position)
	{
		if (oldParent == newParent)
		{
			var url = reorderUrl;
			var params = {'node':node.id, 'delta':(position-oldPosition)};
		} 
		else 
		{
			var url = reparentUrl;
			var params = {'node':node.id, 'parent':newParent.id, 'position':position};
		}
		
		// we disable tree interaction until we've heard a response from the server
		// this prevents concurrent requests which could yield unusual results
		
		tree.disable();
    
		Ext.Ajax.request(
		{
			url:url,
			params:params,
			success:function(response, request) 
			{
				// if the first char of our response is zero, then we fail the operation,
				// otherwise we re-enable the tree
				
				if (response.responseText.charAt(0) != 1)
				{
					request.failure();
				} 
				else 
				{
					tree.enable();
				}
			},
			failure:function() 
			{
				// we move the node back to where it was beforehand and
				// we suspendEvents() so that we don't get stuck in a possible infinite loop
				
				tree.suspendEvents();
				oldParent.appendChild(node);
				
				if (oldNextSibling)
				{
					oldParent.insertBefore(node, oldNextSibling);
				}
				
				tree.resumeEvents();
				tree.enable();
				
				alert("Sorry, your changes could not be saved. Please try again later.");
			}
		});
	});

    tree.render();
    root.expand();

	}
);