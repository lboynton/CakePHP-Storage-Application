<div id="menu">
	<?php
		echo $menu->mainMenu(array
		(
			'Your account' => '/users',
			'File management' => '/backups',
			'User management' => '/admin/users',
			'Logout' => '/users/logout')
		);
    ?> 
    <div class="clear"></div>
</div>