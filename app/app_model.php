<?php
class AppModel extends Model
{
	function userLoggedIn()
	{
		return $this->Session->check('Auth.User');
	}
	
	function adminLoggedIn()
	{
		return $this->Session->check('Auth.Admin');
	}
}
?>