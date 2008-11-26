<div id="menu">
	<?php
    echo $menu->mainMenu(array(
        'Login' => '/users/login',
        'OpenID login' => '/users/openid',
        'Register' => '/users/register'));
    ?> 
    <div class="clear"></div>
</div>