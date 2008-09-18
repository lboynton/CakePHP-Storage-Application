<?php
class FileHelper extends AppHelper 
{
	var $helpers = array('Html');
	
	/**
	 * Displays the appropriate icon for the file type (currently either file or directory)
	 * @param type The type determines the icon that is shown. Currently type can be file or folder.
	 * @param link Optionally have the icon inside a link
	 */
    function icon($type, $link=null) 
	{
        switch(strtolower($type))
		{
			case "folder":
				$output = '<img src="/img/folder.png" alt="Folder" />';
				$title = 'View folder';
				break;
			
			default:
				$output = '<img src="/img/page_white.png" alt="File" />';
				$title = 'View file';
		}
		
		if(isset($link))
		{
			$output = $this->Html->link($output, $link, array('title' => $title, 'class' => 'img'), false, false);
		}
		
		return $this->output($output);
    }
}

?>
