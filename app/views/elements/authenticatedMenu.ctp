<div id="menu">
	<?php
		echo $menu->mainMenu(array
		(
			'Your account' => '/users',
			'Backup' => '/backups/add',
			'Restore' => '/backups/restore',
			'Logout' => '/users/logout')
		);
    ?> 
    <div class="clear"></div>
</div>