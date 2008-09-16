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
}
?>
