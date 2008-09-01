<?php
class BackupFoldersController extends AppController 
{
	var $name = 'BackupFolders';
	
	function index() 
	{
		$this->data = $this->BackupFolder->generatetreelist(null, null, null, '&nbsp;&nbsp;&nbsp;');
		debug ($this->data); die;
	}
}
?>
