<?php
class AppController extends Controller
{
	// add custom link helper so it can be used in layouts
	var $helpers = array('Html', 'Link');
	
	// add authentication component for logging in users
	var $components = array('Auth');
	
	function beforeFilter()
	{
		// allow unregistered access to the homepage
		$this->Auth->allow(array('controller' => 'pages', 'action' => 'display', 'home'));
		// controller action access is defined on a per controller basis
		
		$this->Auth->loginRedirect = array('controller' => 'users', 'action' => 'index');
	}
}
?>