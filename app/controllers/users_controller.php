<?php 
class UsersController extends AppController
{
    var $name = "Users";
    var $helpers = array('Html', 'Form', 'Javascript');
	var $components = array('Number');
	
	// pagination defaults
	var $paginate = array
	(
		'limit' => 50,
		'order' => array('username' => 'asc')
	);
	
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
		$this->pageTitle = "Your Account";
		
		if(!empty($this->data))
		{
			if($this->data['User']['action'] == 'updateDetails') $this->_update_details();
			elseif($this->data['User']['action'] == 'changePassword') $this->_change_password();
		}
		
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
			// check if account has been disabled
			if($this->User->isAccountDisabled($this->Auth->user('id')))
			{
				$this->Session->setFlash('Sorry, your account has been disabled.', 'messages/error');
				$this->redirect($this->Auth->logout());
			}
			else
			{
				$this->User->id = $this->Auth->user('id');
				$this->User->saveField('last_login', date("Y-m-d H:i:s"));
				$this->redirect('/users');
			}
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
			if($this->User->save($this->data, true, array('real_name', 'email', 'username', 'password')))
			{
				// passed validation
				
				// login after registering
				//$this->Session->write('User', $this->User->findByUsername($this->data['User']['username']));
				
				// store the username in the session for speedier login
				$this->Session->write('username', $this->data['User']['username']);
				
				// clear POST data
				$this->data = null;
				
				$this->Session->setFlash('Thank you for registering, please login below.', 'messages/success');
				$this->redirect(array('action' => 'login'));
			}
			else
			{
				// failed validation
				$this->Session->setFlash('Your account could not be created due to the problems highlighted below.','messages/error');
			}
		}
	}
	
	function _update_details()
	{
		$this->User->id = $this->Session->read('Auth.User.id');
		
		// use the validation set for editing user details instead of the default validation set
		$this->User->useValidationRules('EditDetails');
		
		if($this->User->save($this->data, true, array('real_name', 'email')))
		{
			$this->Session->setFlash('Your details have been updated.', 'messages/success');
			
			// update the session data
			$this->Session->write('Auth.User.real_name', $this->data['User']['real_name']);
			$this->Session->write('Auth.User.email', $this->data['User']['email']);
		}
		else 
		{
			$this->Session->setFlash('Your details could not be updated.', 'messages/error');
		}
	}
	
	function _change_password()
	{
		$this->User->id = $this->Session->read('Auth.User.id');
		
		// use the change password validation set instead of default
		$this->User->useValidationRules('ChangePassword');
		
		// use save() instead of saveField() which seems to bugger validation up
		if($this->User->save($this->data, true, array('password')))
		{
			$this->Session->setFlash('Your password has been updated.', 'messages/success');
			
			// clear POST data
			$this->data = null;
		}
		else 
		{
			$this->Session->setFlash('Your password could not be updated.', 'messages/error');
		}
	}

	function admin_index()
	{
		$this->pageTitle = "Users";
		$this->helpers[] = "Number";
		$this->helpers[] = "Time";
		$this->helpers[] = "UserDetails";
		
		if(!empty($this->data))
		{
			// filter  applied
			$users = $this->paginate('User', array
			(
				'User.real_name LIKE' => $this->data('query')
			));
		}
		else
		{
			$users = $this->paginate('User');
		}
		
		$this->set(compact('users'));
	}
	
	function admin_login()
	{
		$this->redirect('/users/login');
	}
	
	function admin_view($id)
	{
		$this->User->useValidationRules('AdminUserView');
		
		if(!empty($this->data))
		{
			$this->User->id = $id;

			// convert quota to bytes
			$this->data['User']['quota'] = $this->Number->convert($this->data['User']['quota'], $this->data['User']['unit'], 'b');
			
			if($this->User->save($this->data, true, array('quota')))
			{
				$this->Session->setFlash('User settings updated.', 'messages/success');
				$this->redirect('/admin/users');
			}
			else $this->Session->setFlash('User settings could not be updated.', 'messages/error');
		}
		
		$this->helpers[] = "Number";
		$this->helpers[] = "Time";
		$this->helpers[] = "UserDetails";
		$this->helpers[] = "Percentage";
		
		$this->User->id = $id;
		$this->User->recursive = -1;
		$user = $this->User->findById($id);
		$this->set('user', $user);
		$this->set('quota', $this->Number->convert($user['User']['quota'], 'b', 'mb'));
		$this->set('backupCount', $this->User->Backup->find('count', array('conditions' => array('type' => 'file', 'user_id' => $id))));
		$this->set('backupSum', $this->User->Backup->find('all', array('fields' => 'SUM(size) as size', 'conditions' => array('user_id' => $id))));
	}
	
	function admin_user_level($id)
	{
		if(!empty($this->data))
		{
			$this->User->id = $id;
			
			if($this->User->save($this->data)) $this->Session->setFlash('User level changed.', 'messages/success');
			else $this->Session->setFlash('Could not change user level, incorrect user level supplied.', 'messages/error');
		}
		
		$this->redirect('/admin/users/view/' . $id);
	}
	
	function admin_disable()
	{
		if(!empty($this->data))
		{
			if($this->data['User']['disableAccount'] == 1)
			{
				$this->User->id = $this->data['User']['id'];
				$this->User->saveField('disabled', 1);
				$this->Session->setFlash('The user account has been disabled.', 'messages/success');
			}
			else 
			{
				$this->User->id = $this->data['User']['id'];
				$this->User->saveField('disabled', 0);
				$this->Session->setFlash('The user account has been enabled.', 'messages/success');
			}
		}
		
		$this->redirect('/admin/users');
	}
	
	function admin_delete()
	{	
		if(!empty($this->data))
		{
			if($this->data['User']['deleteAccount'] == 1)
			{
				$this->User->id = $this->data['User']['id'];
				$this->User->Backup->emptyStore($this->data['User']['id']);
				$this->User->delete();
				$this->Session->setFlash('The user has been successfully deleted.', 'messages/success');
			}
			else 
			{
				$this->Session->setFlash('Please select the checkbox if you wish to delete the user.', 'messages/error');
				$this->redirect($this->referer());
			}
		}
		
		$this->redirect('/admin/users');
	}
}
?>