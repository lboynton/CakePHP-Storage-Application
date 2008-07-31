<div id="menu">
	<?php
    echo $menu->mainMenu(array(
        'Home' => '/',
        'Login' => '/users/login',
        'Register' => '/users/register',
		'Admin' => '/admins/login'));
    ?> 
    <div class="clear"></div>
</div>