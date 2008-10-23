<?php

$data = array();

foreach ($nodes as $node)
{
	if($node['Backup']['type'] == "file") $leaf = true;
	else $leaf = false;
	
    $data[] = array
	(
        "text" => $node['Backup']['name'], 
        "id" => $node['Backup']['id'], 
        "cls" => "folder",
		"leaf" => $leaf,
		"checked" => false,
		"checkbox.name" => 'data[Backup][ids][' . $node['Backup']['id'] . ']',
		"name" => $node['Backup']['name'],
		"size" => $node['Backup']['size'],
		"cls" => 'master-task',
    	"iconCls" => 'task-folder'
    );
}

echo $javascript->object($data);

?>
