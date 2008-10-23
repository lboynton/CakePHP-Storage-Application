<div id="menu">
	<?php
		echo $menu->mainMenu(array
		(
			'Your account' => '/users',
			'File management' => '/backups',
			'Test' => '/backups/test',
			'Test column' => '/backups/column',
			'User management' => '/admin/users',
			'Settings' => '/admin/site_parameters',
			'Logout' => '/users/logout')
		);
    ?> 
    <div class="clear"></div>
</div>