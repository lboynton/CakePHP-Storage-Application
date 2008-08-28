<?php
class FileHelper extends AppHelper 
{
	/**
	 * Displays the appropriate icon for the file type (currently either file or directory)
	 */
    function icon($type) 
	{
        switch(strtolower($type))
		{
			case "directory":
				$output = '<img src="/img/folder.png" alt="Directory">';
				break;
			
			default:
				$output = '<img src="/img/page_white.png" alt="File">';
		}
		
		return $this->output($output);
    }
}

?>
