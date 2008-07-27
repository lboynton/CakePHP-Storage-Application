<div id="menu">
	<?php
	// check for administrator and include settings link
	if($session->read('Auth.User.id') == 0)
	{
		echo $link->menu(array
		(
			'Summary' => '/users',
			'Backup' => '/backups/add',
			'Restore' => '/backups/restore',
			'Settings' => '/settings',
			'Logout' => '/users/logout')
		);
	}
	else
	{
		echo $link->menu(array
		(
			'Summary' => '/users',
			'Backup' => '/backups/add',
			'Restore' => '/backups/restore',
			'Logout' => '/users/logout')
		);
	}
    ?> 
    <div class="clear"></div>
</div>