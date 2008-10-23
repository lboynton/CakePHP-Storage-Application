/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.BLANK_IMAGE_URL = '/img/ext/s.gif';

Ext.onReady(function()
{
    var tree = new Ext.tree.ColumnTree(
	{
        height: 400,
        rootVisible:false,
        autoScroll:true,
        title: 'Files',
        renderTo: 'tree-div',
        
        columns:
		[{
            header:'Name',
            width:'39%',
            dataIndex:'name'
        },
		{
            header:'Size',
            width:'19%',
            dataIndex:'size'
        },
		{
			header:'Created',
            width:'20%',
            dataIndex:'duration'
        },
		{
            header:'Modified',
            width:'20%',
            dataIndex:'user'
        }],

        loader: new Ext.tree.TreeLoader
		({
            dataUrl:'/backups/get_nodes',
            uiProviders:
			{
                'col': Ext.tree.ColumnNodeUI
            }
        }),

        root: new Ext.tree.AsyncTreeNode
		({
            text:'Files'
        })
    });
});