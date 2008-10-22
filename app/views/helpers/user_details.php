<?php
class UserDetailsHelper extends AppHelper 
{
	var $helpers = array('Html', 'Image');
	
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
	 * @param type The type of icon to show. Currently this can be 0 for normal user, and 1 for administrator
	 * @param link Optionally encase the icon in a link
	 */
    function icon($type, $link = null) 
	{
        switch(strtolower($type))
		{
			case 0:
				$output = $this->Image->alpha('user_orange.png', array('alt' => 'Normal user'));
				$title = 'View user';
				break;
			
			case 1:
				$output = $this->Image->alpha('user_gray.png', array('alt' => 'Administrator'));
				$title = 'View administrator';
		}
		
		if(isset($link))
		{
			$output = $this->Html->link($output, $link, array('title' => $title, 'class' => 'img'), false, false);
		}
		
		return $this->output($output);
    }
}
?>
