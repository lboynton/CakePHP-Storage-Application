<div id="menu">
	<?php
    echo $link->menu(array(
        'Summary' => '/users',
        'Backup' => '/backups/add',
        'Restore' => '/backups/restore',
        'Logout' => '/users/logout'));
    ?> 
    <div class="clear"></div>
</div>