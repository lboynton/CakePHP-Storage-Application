<?php
class FileHelper extends AppHelper 
{
	var $helpers = array('Html', 'Image');
	
	/**
	 * Displays the appropriate icon for the file type (currently either file or directory)
	 * @param type The type determines the icon that is shown. Currently type can be file or folder.
	 * @param link Optionally have the icon inside a link
	 */
    function icon($type, $link=null, $name=null) 
	{
        switch(strtolower($type))
		{
			case "folder":
				$output = $this->Image->alpha('folder.png', array('alt' => 'Folder'));
				$title = 'View folder';
				break;
			
			default:
				if($name == null)
				{
					$output = $this->Image->alpha('page_white.png', array('alt' => 'File'));
				}
				else $output = $this->getFileIcon(getFileExtension($name));
				$title = 'View file';
		}
		
		if(isset($link))
		{
			$output = $this->Html->link($output, $link, array('title' => $title, 'class' => 'img'), false, false);
		}
		
		return $this->output($output);
    }
	
	function getFileIcon($extension)
	{
		$extension = strtolower($extension);
		
		$file = APP . 'webroot' . DS . 'img' . DS . 'file' . DS . $extension . '.png';
		
		if(file_exists($file))
		{
			$img = "file/" . $extension . ".png";
		} 
		else 
		{
			$img = "page_white.png";
		}
		
		return $this->Image->alpha($img, array('alt' => 'File'));
	}
}

?>
