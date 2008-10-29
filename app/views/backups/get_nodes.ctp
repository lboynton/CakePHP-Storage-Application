<?php
$data = array();

foreach ($nodes as $i => $node)
{
	if($node['Backup']['type'] == 'file') $leaf = true;
	else $leaf = false;
	
	if($node['Backup']['type'] == 'folder') $size = '';
	else $size = $number->toReadableSize($node['Backup']['size']);
	
	if($i % 2 == 0) $class = 'altrow';
	else $class = '';
	
    $data[] = array
	(
        'id' => $node['Backup']['id'], 
		'name' => $node['Backup']['name'],
		'size' => $size,
		'created' => $time->niceShort($node['Backup']['created']),
		'modified' => $time->niceShort($node['Backup']['modified']),
		'uiProvider' => 'col',
		'leaf' => $leaf,
		'cls' => $class,
		'checked' => false
    );
}

echo $javascript->object($data);
?>