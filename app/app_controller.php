<?php
class AppController extends Controller
{
	// add custom link helper so it can be used in layouts
	var $helpers = array('Html', 'Menu');
	
	// add authentication component for logging in users
	var $components = array('Auth');
	
	function beforeFilter()
	{
		$this->Auth->authorize = 'controller'; 
	
		// allow unregistered access to the homepage
		$this->Auth->allow(array('controller' => 'pages', 'action' => 'display', 'home'));
		// controller action access is defined on a per controller basis
	}
	
	function isAuthorized() 
	{
		// Allow access to admin routes only to administrators
		if (isset($this->params[Configure::read('Routing.admin')])) 
		{
			return ((boolean)$this->Auth->user('admin'));
		}
		return true;
    }
}
?>