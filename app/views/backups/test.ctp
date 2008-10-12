<h2>Files</h2>
<script type="text/javascript">

Ext.BLANK_IMAGE_URL = '<?php echo $html->url('/js/ext-2.2/resources/images/default/s.gif') ?>';

Ext.onReady(function()
{    
    var getnodesUrl = '<?php echo $html->url('/backups/getnodes/') ?>';
    var reorderUrl = '<?php echo $html->url('/backups/reorder/') ?>';
    var reparentUrl = '<?php echo $html->url('/backups/reparent/') ?>';
    
    var Tree = Ext.tree;
    
    var tree = new Tree.TreePanel({
        el:'tree-div',
        autoScroll:true,
        animate:true,
        enableDD:true,
        containerScroll: true,
        rootVisible: false,
        loader: new Ext.tree.TreeLoader({
            dataUrl:getnodesUrl
        })
    });
    
    var root = new Tree.AsyncTreeNode({
        text:'Files',
        draggable:false,
        id:'root'
    });
    tree.setRootNode(root);
    
    tree.render();
    root.expand();

});

</script>

<div id="tree-div" style="height:400px;"></div>
