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
		"leaf" => $leaf
    );
}

echo $javascript->object($data);

?>
