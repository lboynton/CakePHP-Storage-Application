<?php
class BackupFoldersController extends AppController 
{
	var $name = 'BackupFolders';
	var $helpers = array('Html', 'Form', 'Ajax');
	var $components = array('RequestHandler');
	
	function index() 
	{
		$this->data = $this->BackupFolder->generatetreelist(null, null, null, '&nbsp;&nbsp;&nbsp;');
		debug ($this->data); die;
	}
	
	function add()
	{
		if(isset($this->data))
		{
			//print_r($this->data); return;
			
			$this->data['BackupFolder']['user_id'] = $this->Session->read('Auth.User.id');
			
			if(empty($this->data['BackupFolder']['parent_id'])) $this->data['BackupFolder']['parent_id'] = null;
			
			if($this->BackupFolder->save($this->data))
			{
				$this->Session->setFlash('Folder "' . $this->data['BackupFolder']['name'] . '" added.', 'messages/success');
			}
			else 
			{
				$this->_persistValidation('BackupFolder'); 
				$this->Session->setFlash('The folder could not be created.', 'messages/error');
			}
			
			$this->redirect('/backups'); 
		}
	}
	
	function rename($id)
	{
		// check user owns this folder and it exists, if not redirect them
		if($this->BackupFolder->find('count', array('conditions' => array('BackupFolder.id' => $id, 'BackupFolder.user_id' => $this->Session->read('Auth.User.id')))) == 1)
		{
			// check for POST data
			if(isset($this->data))
			{
				$this->BackupFolder->id = $id;
				
				// if this is from an ajax call, we want to show the new file name (or old file name if it fails validation)
				if($this->RequestHandler->isAjax()) 
				{
					$this->BackupFolder->save(array('name' => $this->data['BackupFolder']['name']), true);
					$this->layout = 'ajax';
				}
				else
				{
					if($this->BackupFolder->save(array('name' => $this->data['BackupFolder']['name']), true))
					{
						$this->Session->setFlash('Folder successfully renamed.', 'messages/info');
						// this is the non-ajax form method, redirect the user
						$this->redirect('/backups');
					}
					else $this->Session->setFlash('The folder could not be renamed, please check the error below.', 'messages/error');
				}
			}
			
			// get the file name
			$this->set('folder', $this->BackupFolder->findById($id));
		}
		else $this->redirect('/backups');
	}
}
?>
