<?php 
class AdminsController extends AppController
{
	var $helpers = array('Form');
	
	function beforeFilter()
	{
		$this->Auth->userModel = 'Admin';
	}
	
	function login() { /* handled by Auth component */ }
	
	function logout()
	{
		$this->redirect($this->Auth->logout());
	}
}
?>