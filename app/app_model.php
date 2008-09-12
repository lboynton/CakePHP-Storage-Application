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
	
	function setValidate($rule = null) 
	{
		if ($rule == null) 
		{
			$rule = 'validate' . Inflector::camelize(Router::getParam('action'));
			
			if (!isset($this->$rule)) 
			{
				$rule = "validate";
			}
		}
		
		$this->validate = $this->$rule;
	}
}
?>