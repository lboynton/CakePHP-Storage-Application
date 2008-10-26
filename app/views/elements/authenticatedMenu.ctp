<div id="menu">
	<?php
		echo $menu->mainMenu(array
		(
			'Your account' => '/users',
			'File management' => '/backups',
			'Test' => '/backups/column',
			'Logout' => '/users/logout')
		);
    ?> 
    <div class="clear"></div>
</div>