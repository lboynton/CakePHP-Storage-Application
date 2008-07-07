<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $title_for_layout?></title>
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
<?php echo $html->css('main'); ?>
<?php echo $html->css('forms'); ?>
<?php echo $scripts_for_layout?>
</head>
<body>
<div id="container">
    <div id="header">
        <div id="menu">
            <?php
            echo $link->menu(array(
				'Home' => '/',
                'Login' => '/users/login',
                'Register' => '/users/add'));
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
