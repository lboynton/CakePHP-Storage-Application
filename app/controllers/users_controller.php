<?php 
App::import('Sanitize');

class UsersController extends AppController
{
    var $name = "Users";
    var $helpers = array('Html', 'Form', 'Javascript');
	var $components = array('Number', 'Filter', 'RequestHandler', 'Ticket', 'Email', 'Openid');
	var $uses = array('User', 'SiteParameter');
	
	// user pagination defaults
	var $paginate = array
	(
		'limit' => 50,
		'order' => array('username' => 'asc'),
		'recursive' => -1
	);
	
	public function beforeFilter()
	{
		parent::beforeFilter();
		
		// allow unregistered access to the register and forgot password pages
		$this->Auth->allow('register', 'forgot_password', 'reset_password', 'login');
		$this->Auth->loginRedirect = array('controller' => 'users', 'action' => 'index');
		$this->Auth->autoRedirect = false;
        $this->Auth->loginError = 'Login failed';
		
		//$this->Security->requirePost('admin_user_level',  'admin_disable', 'admin_delete', 'admin_perform_action');
	}

    public function index()
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
    
    public function logout()
    {
		$this->redirect($this->Auth->logout());
    }
	
    public function login()
    {
        $this->pageTitle = "Login";

        $this->User->useValidationRules('Login');
        
        $returnTo = 'http://'.$_SERVER['SERVER_NAME'].'/users/login';

        if ($this->RequestHandler->isPost())
        {
            $this->User->set( $this->data );

            if($this->User->validates())
            {
                $this->makeOpenIDRequest($this->data['User']['username'], $returnTo);
            }
        }

        if ($this->isOpenIDResponse())
        {
            $this->handleOpenIDResponse($returnTo);
        }

        // check if user has been authenticated
        if($this->Auth->user())
        {
            $user = $this->User->getUser($this->Auth->user());

            // update the Auth session data
            foreach(array_shift($user) as $key => $value)
            {
                $this->Session->write('Auth.User.' . $key, $value);
            }

            // check if account has been disabled
			if($this->User->isAccountDisabled($this->Auth->user('id')))
			{
				$this->Session->setFlash('Sorry, you cannot currently log in because your account has been disabled.', 'messages/error');
				$this->redirect($this->Auth->logout());
			}
            else
            {
                // update the last login timestamp
                $this->User->id = $this->Auth->user('id');
                $this->User->saveField('last_login', date("Y-m-d H:i:s"));

                // redirect user to their account page
                $this->redirect('/users');
            }
        }
    }

    private function makeOpenIDRequest($openid, $returnTo)
    {
        try
        {
            // try to authenticate with the OpenID provider and request sreg extension details
            // email is required
            // fullname is optional
            $this->Openid->authenticate($openid, $returnTo, 'http://'.$_SERVER['SERVER_NAME'], array('email'), array('fullname'));
        } 
        catch (Exception $e)
        {
            // empty
        }
    }

    private function isOpenIDResponse()
    {
        return (count($_GET) > 1);
    }

    private function handleOpenIDResponse($returnTo)
    {
        $response = $this->Openid->getResponse($returnTo);
        $this->Auth->login($response);
    }

    private function _update_details()
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

	public function admin_index()
	{
		$this->pageTitle = "Users";
		$this->helpers[] = "Number";
		$this->helpers[] = "Time";
		$this->helpers[] = "UserDetails";
		
		if(isset($this->data['User']['show'])) $this->paginate['limit'] = $this->data['User']['show'];
		if(isset($this->params['named']['show'])) $this->paginate['limit'] = $this->params['named']['show'];
		$this->set('show', $this->paginate['limit']);
		
		if(isset($this->data['User']['advanced'])) $this->set('advanced', $this->data['User']['advanced']);
		else $this->set('advanced', false);
		
		@$this->data['User'][$this->data['User']['field']] = $this->data['User']['query'];
		
		$filter = $this->Filter->process($this, array('username', 'real_name', 'email', 'disabled', 'admin'));
		$this->set('url', $this->Filter->url . '/show:' . $this->paginate['limit']);
		$this->set('users', $this->paginate(null, $filter));

		if(isset($this->data['User']['field'])) $this->set('field', $this->data['User']['field']);
		else $this->set('field', 'real_name');

		if($this->RequestHandler->isAjax()) 
		{
			$filter = $this->Filter->process($this, array('username', 'real_name', 'email', 'disabled', 'admin'));
			$this->set('url', $this->Filter->url . '/show:' . $this->paginate['limit']);
			$this->set('users', $this->paginate(null, $filter));
            $this->viewPath = 'elements'.DS.'users';
            $this->render('paging');            
        }
	}
	
	public function admin_login()
	{
		$this->redirect('/users/login');
	}
	
	public function admin_view($id)
	{
		$this->User->useValidationRules('AdminUserView');
		
		if(!empty($this->data))
		{
			$this->User->set($this->data);
			$this->User->id = $id;
			
			if($this->User->validates())
			{
				// convert quota to bytes
				$this->data['User']['quota'] = $this->Number->convert($this->data['User']['quota'], $this->data['User']['unit'], 'b');
				$this->User->save($this->data, true, array('quota'));
				$this->Session->setFlash('User settings updated.', 'messages/success');
				$this->redirect('/admin/users');
			}
			else $this->Session->setFlash('User settings could not be updated, please check below for errors.', 'messages/error');
		}
		
		$this->helpers[] = "Number";
		$this->helpers[] = "Time";
		$this->helpers[] = "UserDetails";
		$this->helpers[] = "Percentage";
		
		$this->User->id = $id;
		$this->User->recursive = -1;
		$user = $this->User->findById($id);
		$this->set('user', $user);
		$this->set('quota', $this->Number->toReadableSize($user['User']['quota']));
		$this->set('backupCount', $this->User->Backup->find('count', array('conditions' => array('type' => 'file', 'user_id' => $id))));
		$this->set('backupSum', $this->User->Backup->find('all', array('fields' => 'SUM(size) as size', 'conditions' => array('user_id' => $id))));
	}
	
	public function admin_user_level($id)
	{
		if(!empty($this->data))
		{
			$this->User->id = $id;
			
			if($this->User->save($this->data)) $this->Session->setFlash('User level changed.', 'messages/success');
			else $this->Session->setFlash('Could not change user level, incorrect user level supplied.', 'messages/error');
		}
		
		$this->redirect('/admin/users/view/' . $id);
	}
	
	public function admin_disable()
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
	
	public function admin_delete()
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
	
	public function admin_perform_action()
	{
		if(!empty($this->data))
		{
			switch($this->data['User']['action'])
			{			
				case "quota":
					$this->Session->write('User.ids', $this->data['User']['ids']);
					$this->redirect('/admin/users/quota');
					break;
				
				// using value of submit button, other two use radio buttons
				case "Save":
					$this->_disable_accounts($this->data['User']['disable_ids']);
					break;
					
				case "delete":
					$this->_delete_accounts($this->data['User']['ids']);
					break;				
			}
		}
		
		$this->redirect('/admin/users');
	}
	
	public function admin_quota()
	{	
		$this->User->useValidationRules('AdminUserView');
		
		if(!empty($this->data))
		{
			$this->User->set($this->data);
			
			if($this->User->validates())
			{
				$this->data['User']['quota'] = $this->Number->convert($this->data['User']['quota'], $this->data['User']['unit'], 'b');

				foreach($this->Session->read('User.ids') as $id => $value)
				{
					if($value != 1) continue;
					
					$this->User->id = $id;
					
					// convert quota to bytes
					$this->User->save($this->data, true, array('quota'));
				}
				
				$this->Session->delete('User.ids');
				$this->Session->setFlash('User settings updated.', 'messages/success');
				$this->redirect('/admin/users');
			}
			else $this->Session->setFlash('User settings could not be updated, please check below for errors.', 'messages/error');
		}
	}
	
	private function _disable_accounts($ids)
	{
		foreach($ids as $id => $value)
		{
			$value = intval($value);
			
			if($value != 0 && $value != 1) continue;
			
			$this->User->id = $id;
			$this->User->saveField('disabled', $value);
		}
		
		$this->Session->setFlash('The selected user accounts have been enabled/disabled.', 'messages/success');
	}
	
	private function _delete_accounts($ids)
	{
		foreach($ids as $id => $value)
		{
			$value = intval($value);
			
			if($value == 1) 
			{
				$this->User->id = $id;
				$this->User->delete();
			}
		}
		
		$this->Session->setFlash('The selected user accounts have been deleted.', 'messages/success');
	}
}
?>