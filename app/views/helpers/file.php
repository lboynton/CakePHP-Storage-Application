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
			case "folder":
				$output = '<img src="/img/folder.png" alt="Folder" title="Folder" />';
				break;
			
			default:
				$output = '<img src="/img/page_white.png" alt="File" title="File" />';
		}
		
		return $this->output($output);
    }
}

?>
