<?php
$data = array();

foreach ($nodes as $node)
{
	if($node['Backup']['type'] == "file") $leaf = true;
	else $leaf = false;
	
	if($node['Backup']['type'] == "folder") $size = '';
	else $size = $number->toReadableSize($node['Backup']['size']);
	
    $data[] = array
	(
        "id" => $node['Backup']['id'], 
		"name" => $node['Backup']['name'],
		"size" => $size,
		"created" => $time->niceShort($node['Backup']['created']),
		"modified" => $time->niceShort($node['Backup']['modified']),
		"uiProvider" => 'col',
		"leaf" => $leaf
    );
}

echo $javascript->object($data);
?>