<div id="menu">
	<?php
    echo $menu->mainMenu(array(
		'Status' => '/admins/status',
		'Profiles' => '/profiles',
        'Logout' => '/admins/logout'));
    ?> 
    <div class="clear"></div>
</div>