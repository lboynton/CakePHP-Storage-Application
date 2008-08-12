<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $title_for_layout?></title>
<link rel="shortcut icon" href="<?php echo $html->url('/favicon.ico');?>" type="image/x-icon" />
<?php echo $html->css(array('main','forms','tables','corners')); ?>
<!--[if lt IE 7]>
<?php echo $html->css(array('ie6')); ?>
<![endif]-->
<!--[if lt IE 8]>
<script type="text/javascript" src="/js/ie8.js"></script>
<![endif]-->
<script type="text/javascript" src="/js/prototype.js"></script>
<script type="text/javascript" src="/js/forms.js"></script>
<?php echo $scripts_for_layout; ?>
</head>
<body>
<div id="container">
    <div id="header">
        <div class="topLeft"></div>
    	<div class="topRight"></div>
    	<h1></h1>
		<?php 
        if($session->check('Auth.User')) echo $this->element('authenticatedMenu');
		elseif($session->check('Auth.Admin')) echo $this->element('adminMenu');
        else echo $this->element('unauthenticatedMenu');
        ?> 
    </div>
    <div id="content">
    	<?php echo $content_for_layout ?>
        <div style="clear:both"></div>
        <div class="bottomLeft"></div>
        <div class="bottomRight"></div>
    </div>
    <div id="footer">
    	<div class="bottomLeft"></div>
    	<div class="bottomRight"></div>
    </div>  
</div>
</body>
</html>
