<?php
class UserDetailsHelper extends AppHelper 
{
    function userLevel($level) 
	{
		switch($level)
		{
			case 0:
				$output = "Normal user";
				break;
			
			case 1:
				$output = "Administrator";
				break;
			
			default:
				$output = "Unkown";			
		}
		
		return $this->output($output);
    }
	
	/**
	 * Displays the appropriate icon for user (administrator or normal user)
	 */
    function icon($type) 
	{
        switch(strtolower($type))
		{
			case 0:
				$output = '<img src="/img/user_orange.png" alt="Normal user" title="Normal user" />';
				break;
			
			case 1:
				$output = '<img src="/img/user_gray.png" alt="Administrator" title="Administrator" />';
		}
		
		return $this->output($output);
    }
}
?>
