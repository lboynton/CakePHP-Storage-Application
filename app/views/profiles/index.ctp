<h2>Profiles</h2>
<?php
	echo $menu->normalMenu
	(
		array
		(
			'New profile' => '/profiles/add',
		),
		array
		(
			'id' => 'subMenu'
		)
	);
?> 
<table>
	<tr>
		<th>Name</th>
        <th>Actions</th>
	</tr>
    <?php foreach ($profiles as $profile): ?>
	<tr>
		<td>
			<?php echo $html->link($profile['Profile']['name'],'/profiles/view/'.$profile['Profile']['id']);?>
        </td>
        <td>
			<?php echo $html->link('Edit', '/profiles/edit/'.$profile['Profile']['id']);?>
            <?php echo $html->link(
				'Delete', 
				"/profiles/delete/{$profile['Profile']['id']}", 
				null, 
				'Please confirm you wish to delete the profile "' . $profile['Profile']['name'] . '"'
			)?>
		</td>
	</tr>
<?php endforeach; ?>
</table>
