<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echo $title_for_layout?></title>
	<?php /* <link rel="shortcut icon" href="<?php echo $html->url('/favicon.ico');?>" type="image/x-icon" /> */ ?>
	<?php echo $html->css(array('base', 'forms', 'tables')); ?>
	
	<!--[if lt IE 7]><?php echo $html->css(array('ie6')); ?>
	<![endif]-->
	<?php echo $javascript->link(array('prototype', 'scriptaculous.js?load=effects,controls', 'forms')); ?>
	<?php echo $scripts_for_layout."\n"; ?>
</head>
<body>
<div id="container">
	<div id="loadingIndicator" style="display:none;">Loading...</div>
	<?php if($session->check('Auth.User')) echo $this->element('searchForm'); ?>
    <div id="header">
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
	<div id="bar"></div>
    <div id="content">
    	<?php echo $content_for_layout ?>
        <div style="clear:both"></div>
    </div>
    <div id="footer"></div>  
</div>
</body>
</html>
