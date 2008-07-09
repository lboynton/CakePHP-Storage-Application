<?php
class AppController extends Controller
{
	// add custom link helper so it can be used in layouts
	var $helpers = array('Html', 'Link');
	
	/**
	 * Returns true if user is logged in, false otherwise
	 */
	function __userIsLoggedIn()
	{
		return $this->Session->check('User');
	}
	
	/**
	 * Redirects user to login page if they're not logged in
	 */    
    function __validateLoginStatus()
    {
		if(!$this->__userIsLoggedIn())
		{
			$this->Session->setFlash('The URL you\'ve followed requires you to login, please do so below.');
			$this->redirect(array('controller' => 'users', 'action' => 'login'));
		}
    }
}
?>