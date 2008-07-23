<?php 
class UsersController extends AppController
{
    var $name = "Users";
    var $helpers = array('Html', 'Form', 'Javascript');
	
	function beforeFilter()
	{
		// allow unregistered access to the register page
		$this->Auth->allow('register');
	}
    
    function index()
    {
		$this->pageTitle = "Summary";
    }
    
    function login()
    {
		// handled by auth component
		
		// set the default username to the one created during registration if form hasn't been posted
		if(empty($this->data)) $this->set('defaultUsername', $this->Session->read('username'));
		else $this->set('defaultUsername', null);
    }
    
    function logout()
    {
		$this->redirect($this->Auth->logout());
    }
	
	function register() 
	{
		// check for POST data
		if (!empty($this->data)) 
		{
			// create user with defaults
			//
			//
			// verify this step is required
			//
			//
			$this->User->create();

			// try to store the data
			if($this->User->save($this->data))
			{
				// passed validation
				
				// login after registering
				//$this->Session->write('User', $this->User->findByUsername($this->data['User']['username']));
				
				// store the username in the session for speedier login
				$this->Session->write('username', $this->data['User']['username']);
				
				// clear POST data
				$this->data = null;
				
				$this->Session->setFlash('Thank you for registering, please login below.');
				$this->redirect(array('action' => 'login'));
			}
			else
			{
				// failed validation
				$this->Session->setFlash('Your account could not be created due to the problems highlighted below.','default', array('class' => 'error'));
			}
		}
	}
	
	function admin_index()
	{
		$this->pageTitle = "Admin index";
	}
}
?>