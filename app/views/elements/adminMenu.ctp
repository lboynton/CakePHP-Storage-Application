<div id="menu">
	<?php
    echo $menu->mainMenu(array(
		'Status' => '/admins/status',
		'Profiles' => '/profiles',
		'Users' => '/admins/users',
		'Settings' => '/admins/settings',
        'Logout' => '/admins/logout'));
    ?> 
    <div class="clear"></div>
</div>