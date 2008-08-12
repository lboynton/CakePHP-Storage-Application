<?php 
class AdminsController extends AppController
{
	var $helpers = array('Form');
	
	function beforeFilter()
	{
		$this->Auth->userModel = 'Admin';
		$this->Auth->loginRedirect = array('controller' => 'admins', 'action' => 'status');
		$this->Auth->autoRedirect = false;
	}
	
	function login() 
	{
		// redirect if user logged in
		if ($this->Auth->user()) 
		{
			$this->redirect('/admins/status');
			return;
		}
	}
	
	function logout()
	{
		$this->redirect($this->Auth->logout());
	}
	
	function status()
	{
	}
}
?>