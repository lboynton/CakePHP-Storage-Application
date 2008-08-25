<?php 
class BackupsController extends AppController
{
	var $name = "Backups";
	var $helpers = array('Html', 'Form', 'Ajax');
    var $paginate = array
	(
        'limit' => 25,
        'order' => array('name' => 'asc')
    );
  	var $components = array('RequestHandler');
		
	function restore()
	{
		$this->helpers[] = "Time";
		$this->helpers[] = "Number";
		$this->pageTitle = "Restore";
		
		// redirect the query to named parameter
		if(isset($this->params['url']['query'])) 
		{
			$this->redirect('/' . $this->params['url']['url'] . "/query:{$this->params['url']['query']}");
			return;
		}
		
		App::import('Sanitize'); 
		if(isset($this->params['named']['query'])) $query = Sanitize::escape($this->params['named']['query']);
		else $query = "";
		
		$this->set('query', $query);

		$backups = $this->paginate('Backup', "name LIKE '%$query%' AND user_id = {$this->Session->read('Auth.User.id')}");
		$this->set(compact('backups'));
	}
	
	/**
	 * Add file to backup
	 */
	 function add() 
	 {
	 	$this->pageTitle = "Backup";
		
		if (!empty($this->data))
		{
			foreach($this->data['Backup'] as $file)
			{
				if(is_uploaded_file($file['File']['tmp_name']))
				{
					$zip = zip_open($file['File']['tmp_name']);
					
					if(is_resource($zip))
					{
						while ($zip_entry = zip_read($zip))
						{
							if(zip_entry_filesize($zip_entry) <= 0) break;
							
							$this->_createBackupDirectory();
							
							$this->Backup->create();
							
							// date isn't automagically inserted by Cake for some reason
							$this->data['Backup']['created'] = date('Y-m-d H:i:s');
							
							$this->data['Backup']['name'] = zip_entry_name($zip_entry);	
							$this->data['Backup']['size'] = zip_entry_filesize($zip_entry);
							$this->data['Backup']['data'] = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
							$this->data['Backup']['hash'] = md5($this->data['Backup']['data']);
							$this->data['Backup']['user_id'] = $this->Session->read('Auth.User.id');
							
							if($this->Backup->save($this->data))
							{
								$fp = fopen(BACKUP_ROOT_DIR . $this->Session->read('Auth.User.id') . DS . $this->Backup->id, 'wb');
								fwrite($fp, $this->data['Backup']['data']);
								fclose($fp);
							}
						}
					}
					else
					{
						if($file['File']['size'] <= 0) break;
						
						$this->Backup->create();
						
						// date isn't automagically inserted by Cake for some reason
						$this->data['Backup']['created'] = date('Y-m-d H:i:s');
						
						$this->data['Backup']['name'] = $file['File']['name'];
						$this->data['Backup']['size'] = $file['File']['size'];
						$this->data['Backup']['data'] = fread(fopen($file['File']['tmp_name'], "r"), $file['File']['size']);
						$this->data['Backup']['hash'] = md5($this->data['Backup']['data']);
						$this->data['Backup']['user_id'] = $this->Session->read('Auth.User.id');
						
						if($this->Backup->save($this->data))
						{
							$this->_createBackupDirectory();
							move_uploaded_file($file['File']['tmp_name'], BACKUP_ROOT_DIR . $this->Session->read('Auth.User.id') . DS . $this->Backup->id);
						}
					}
				}
			}
			
			$this->Session->setFlash('The selected files have been backed up.');
			$this->redirect('/backups/restore');
		}
    }

	function download($id) 
	{
		if($this->Backup->find('count', array('conditions' => array('Backup.id' => $id, 'Backup.user_id' => $this->Session->read('Auth.User.id')))) == 1)
		{
			Configure::write('debug', 0);
			$file = $this->Backup->findById($id);
			$fp = fopen(BACKUP_ROOT_DIR . $this->Session->read('Auth.User.id') . DS . $file['Backup']['id'], 'r');
		
			header('Content-type: ' . $file['Backup']['type']);
			header('Content-length: ' . $file['Backup']['size']);
			header('Content-Disposition: attachment; filename="'.$file['Backup']['name'].'"');
			echo fread($fp, $file['Backup']['size']);
			fclose($fp);
			exit();
		}
		else
		{
			$this->redirect('/backups/restore');
		}
	}
	
	function view($id)
	{
		if($this->Backup->find('count', array('conditions' => array('Backup.id' => $id, 'Backup.user_id' => $this->Session->read('Auth.User.id')))) == 1)
		{
			Configure::write('debug', 0);
			$file = $this->Backup->findById($id);
			$fp = fopen(BACKUP_ROOT_DIR . $this->Session->read('Auth.User.id') . DS . $file['Backup']['id'], 'r');
		
			header('Content-type: ' . $file['Backup']['type']);
			header('Content-length: ' . $file['Backup']['size']);
			header('Content-Disposition: inline; filename="'.$file['Backup']['name'].'"');
			echo fread($fp, $file['Backup']['size']);
			fclose($fp);
			exit();
		}
		else
		{
			$this->redirect('/backups/restore');
		}
	}
	
	function delete($id)
	{
		if($this->Backup->find('count', array('conditions' => array('Backup.id' => $id, 'Backup.user_id' => $this->Session->read('Auth.User.id')))) == 1)
		{
			$file = $this->Backup->findById($id);
			
			$this->Backup->del($id);
			
			$absoluteFile = BACKUP_ROOT_DIR . $this->Session->read('Auth.User.id') . DS . $file['Backup']['id'];
			
			if(unlink(BACKUP_ROOT_DIR . $this->Session->read('Auth.User.id') . DS . $file['Backup']['id']))
			{
				$this->log('Deleted file: ' . $absoluteFile, LOG_DEBUG);
			}
			else
			{
				$this->log("Could not delete file: " . $absoluteFile);
			}
			
			$this->Session->setFlash("The file \"{$file['Backup']['id']}\" has been deleted.");
		}
		
		$this->redirect('/backups/restore');
	}
	
	function deleteAll()
	{
		if($this->data['Backup']['deleteAll'] == 1)
		{
			$this->Backup->deleteAll(array('Backup.user_id' => $this->Session->read('Auth.User.id')));
			$this->_unlinkWildcard(BACKUP_ROOT_DIR . $this->Session->read('Auth.User.id') . DS . "*");
			$this->Session->setFlash("All files in the backup have been deleted.");
			$this->redirect('/users');
		}
	}
	
	function rename($id)
	{
		// check user owns this file and it exists, if not redirect them
		if($this->Backup->find('count', array('conditions' => array('Backup.id' => $id, 'Backup.user_id' => $this->Session->read('Auth.User.id')))) == 1)
		{
			// check for POST data
			if(isset($this->data))
			{
				$this->Backup->id = $id;
				$this->Backup->saveField('name', $this->data['Backup']['Name']);
				
				// if this is from an ajax call, we want to show the new file name
				if($this->RequestHandler->isAjax()) 
				{
					$this->set('file', $this->Backup->findById($id));
				}
				else
				{
					// this is the non-ajax form method, redirect the user
					$this->Session->setFlash('File successfully renamed.');
					$this->redirect('/backups/restore');
				}
			}
			
			$this->set('file', $this->Backup->findById($id));
		}
		else $this->redirect('/backups/restore');
	}
	
	function test()
	{
		print_r($this->data);
	}
	
	//
	// Private functions
	//
	 
	/**
	 * Provides deletion of files using wildcards
	 */
	function _unlinkWildcard($str)
	{
		foreach(glob($str) as $file) 
		{
			$absoluteFile = BACKUP_ROOT_DIR . $this->Session->read('Auth.User.id') . DS . basename($file);
			
			if(unlink($file))
			{
				$this->log('Deleted file: ' . $absoluteFile, LOG_DEBUG);
			}
			else
			{
				$this->log("Could not delete file: " . $absoluteFile);
			}
		} 
	}
	
	/**
	 * Creates the root directory for the user's backups
	 */
	function _createBackupDirectory()
	{
		if(file_exists(BACKUP_ROOT_DIR . "{$this->Session->read('Auth.User.id')}"))
		{
			$this->log('Not creating backup store for use with ID ' . $this->Session->read('Auth.User.id') . ', directory already present.', LOG_DEBUG);
			return true;
		}
		
		if(mkdir(BACKUP_ROOT_DIR . "{$this->Session->read('Auth.User.id')}", 0777, true))
		{
			$this->log('Created backup store for user with ID ' . $this->Session->read('Auth.User.id'), LOG_DEBUG);
			return true;
		}
		else
		{
			$this->log('Could not create backup store for user with ID ' . $this->Session->read('Auth.User.id'));
			return false;
		}
	}
}
?>