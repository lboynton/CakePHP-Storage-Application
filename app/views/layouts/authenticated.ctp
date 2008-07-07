<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $title_for_layout?></title>
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
<?php echo $html->css(array('main','forms','tables')); ?>
<?php echo $scripts_for_layout?>
</head>
<body>
<div id="container">
    <div id="header">
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
    </div>
    <div id="content">
    	<?php echo $content_for_layout ?>
    </div>
    <div id="footer"></div>
</div>
</body>
</html>
