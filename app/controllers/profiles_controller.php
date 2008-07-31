<?php 
class ProfilesController extends AppController
{
	var $helpers = array('Form', 'Menu');
	
	function beforeFilter()
	{
		$this->Auth->userModel = 'Admin';
	}
	
	function index()
	{
		$this->set('profiles', $this->Profile->find('all'));
	}
	
	function add() 
	{
		if (!empty($this->data)) 
		{
			if ($this->Profile->save($this->data)) 
			{
				$this->flash('The profile has been added','/profiles');
			}
		}
	}
	
	function view($id = null) 
	{
		$this->Profile->id = $id;
		$this->set('profile', $this->Profile->read());
	}
	
	function delete($id) 
	{
		$this->Profile->del($id);
		$this->flash('The profile with id: '.$id.' has been deleted.', '/profiles');
	}
	
	function edit($id = null) 
	{
		$this->Profile->id = $id;
		if (empty($this->data)) 
		{
			$this->data = $this->Profile->read();
		} 
		else 
		{
			if ($this->Profile->save($this->data['Profile'])) 
			{
				$this->flash('The profile has been updated.','/profiles');
			}
		}
	}
}
?>