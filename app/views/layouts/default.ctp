<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $title_for_layout?></title>
<link rel="shortcut icon" href="<?php echo $html->url('/favicon.ico');?>" type="image/x-icon" />
<?php echo $html->css(array('base', 'forms', 'tables', 'ext-tree-custom', 'ext-dd-custom')); ?>
<?php echo $html->css('aminimal'); ?>
<!--[if IE]>
<?php //echo $html->css(array('ie')); ?>
<![endif]-->
<!--[if lt IE 8]>
<script type="text/javascript" src="/js/IE8.js"></script>
<![endif]-->
<?php echo $javascript->link('prototype'); ?>
<?php echo $javascript->link('scriptaculous'); ?>
<?php echo $javascript->link('forms'); ?>

<?php echo $javascript->link('/js/ext-2.2/adapter/ext/ext-base.js'); ?>
<?php echo $javascript->link('/js/ext-2.2/ext-all.js'); ?>

<?php echo $scripts_for_layout; ?>
</head>
<body>
<div id="container">
    <div id="header"">
		<a href="/"><h1></h1></a>
		<?php 
        if($session->check('Auth.User'))
		{
			if($session->read('Auth.User.admin'))
			{
				echo $this->element('adminMenu');
			}
			else echo $this->element('authenticatedMenu');
		}
        else echo $this->element('unauthenticatedMenu');
        ?> 
    </div>
	<div id="bar">
		<div id="left"></div>
		<div id="right"></div>
	</div>
    <div id="content">
    	<?php echo $content_for_layout ?>
        <div style="clear:both"></div>
    </div>
    <div id="footer"></div>  
</div>
<div id="loadingIndicator" style="display:none;">Loading...</div>
</body>
</html>
