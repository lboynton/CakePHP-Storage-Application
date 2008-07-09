<?php 
class UsersController extends AppController
{
    var $name = "Users";
    var $helpers = array('Html', 'Form');
    
    function index()
    {
		$this->__validateLoginStatus(); // require authentication
		$this->pageTitle = "Summary";
    }
    
    function login()
    {
		$this->pageTitle = "Login";
		
        if(empty($this->data) == false)
        {
            if(($user = $this->User->validateLogin($this->data['User'])) == true)
            {
                $this->Session->write('User', $user);
                $this->flash('You\'ve successfully logged in.','index');
            }
            else
            {
                $this->Session->setFlash('Sorry, the information you\'ve entered is incorrect.', 'default', array('class' => 'error'));
            }
        }
    }
    
    function logout()
    {
        $this->Session->destroy('user');
        $this->Session->setFlash('You\'ve successfully logged out.');
        $this->redirect('/');
    }

	function add()
	{
        if (!empty($this->data)) 
		{
			if($this->data['User']['password'] != $this->data['User']['confirmPassword'])
			{
				$this->Session->setFlash('The passwords do not match','default', array('class' => 'error'));
			}
			else
			{
				$this->User->set($this->data);
				
				if ($this->User->validates()) 
				{
					$this->data['User']['password'] = md5($this->data['User']['password']);
					$this->User->create();
				
					if ($this->User->save($this->data)) 
					{
						// login after registering
						//$this->Session->write('User', $this->User->findByUsername($this->data['User']['username']));
						$this->Session->write('username', $this->data['User']['username']);
						$this->Session->setFlash('Thank you for registering, please login below.');
						$this->redirect(array('action' => 'login'));
					} 
				}
				else 
				{
					$this->Session->setFlash('Your account could not be created due to the errors noted below. Please try again.','default', array('class' => 'error'));
				}
			}
        } 
	}
}
?>