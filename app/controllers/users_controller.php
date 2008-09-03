<?php 
class UsersController extends AppController
{
    var $name = "Users";
    var $helpers = array('Html', 'Form', 'Javascript');
	
	function beforeFilter()
	{
		parent::beforeFilter();
		// allow unregistered access to the register page
		$this->Auth->allow('register');
		$this->Auth->loginRedirect = array('controller' => 'users', 'action' => 'index');
		$this->Auth->autoRedirect = false;
	}
	
    function index()
    {
		$this->helpers[] = "Time";
		$this->helpers[] = "Number";
		$this->helpers[] = "Javascript";
		$this->helpers[] = "Percentage";
		$this->pageTitle = "Account Summary";
		
		$this->set('lastBackup', $this->User->Backup->find
		(
			'all',
			array
			(
				'conditions' => array('Backup.user_id' => $this->Session->read('Auth.User.id'), 'type' => 'file'),
				'fields' => array('created'),
				'limit' => 1,
				'order' => 'Backup.id DESC'
			)
		));
		
		$this->set('backupCount', $this->User->Backup->find('count', array('conditions' => array('Backup.user_id' => $this->Session->read('Auth.User.id'), 'type' => 'file'))));

		$this->set('backupSum', $this->User->Backup->find('all', array('fields'=>'SUM(size) as size', 'conditions' => array('Backup.user_id' => $this->Session->read('Auth.User.id')))));
    }
    
    function login()
    {
		$this->pageTitle = "Login";
		
		// set the default username to the one created during registration if form hasn't been posted
		if(empty($this->data)) $this->set('defaultUsername', $this->Session->read('username'));
		else $this->set('defaultUsername', null);
		
		// redirect if the user is logged in
		if ($this->Auth->user()) 
		{
			$this->redirect('/users');
			return;
		}
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
				
				$this->Session->setFlash('Thank you for registering, please login below.'. 'messages/success');
				$this->redirect(array('action' => 'login'));
			}
			else
			{
				// failed validation
				$this->Session->setFlash('Your account could not be created due to the problems highlighted below.','messages/error');
			}
		}
	}

	function admin_index()
	{
		$this->pageTitle = "Admin index";
	}
}
?>