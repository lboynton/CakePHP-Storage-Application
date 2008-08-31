<?php 
uses('sanitize');

class BackupsController extends AppController
{
	var $name = "Backups";
	var $helpers = array('Html', 'Form', 'Ajax');
    var $paginate = array
	(
        'limit' => 25,
        'order' => array('type' => 'asc', 'name' => 'asc')
    );
  	var $components = array('RequestHandler');
		
	function index()
	{
		$this->helpers[] = "Time";
		$this->helpers[] = "Number";
		$this->helpers[] = "File";
		$this->pageTitle = "File Management";
		
		if(isset($this->params['named']['query'])) $query = Sanitize::escape($this->params['named']['query']);
		else $query = "";
		
		$this->set('query', $query);
		
		$directoriesList[''] = '/';
		
		$directoriesList[] = $this->Backup->find('list', array
		(
			'conditions' => array('type' => 'directory', 'user_id' => $this->Session->read('Auth.User.id')),
			'fields' => array('name', 'name')
		));
		
		$this->set('directoriesList', $directoriesList);

		$backups = $this->paginate('Backup', "name LIKE '%$query%' AND user_id = {$this->Session->read('Auth.User.id')}");
		$this->set(compact('backups'));
	}
	
	/**
	 * Add file to backup
	 */
	 function add() 
	 {
		if (!empty($this->data))
		{
			//$this->data = Sanitize::clean($this->data);
			
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
							$this->data['Backup']['modified'] = date('Y-m-d H:i:s');
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
						$this->Backup->create();
						
						print_r($this->data);
						
						// date isn't automagically inserted by Cake for some reason
						$this->data['Backup']['created'] = date('Y-m-d H:i:s');
						$this->data['Backup']['modified'] = date('Y-m-d H:i:s');
						$this->data['Backup']['name'] = $file['File']['name'];
						$this->data['Backup']['size'] = filesize($file['File']['tmp_name']);
						$this->data['Backup']['hash'] = md5($this->data['Backup']['data']);
						$this->data['Backup']['user_id'] = $this->Session->read('Auth.User.id');
						$this->data['Backup']['path'] = $file['path'];
						
						if($this->Backup->save($this->data))
						{
							$this->_createBackupDirectory();
							move_uploaded_file($file['File']['tmp_name'], BACKUP_ROOT_DIR . $this->Session->read('Auth.User.id') . DS . $this->Backup->id);
							$this->Session->setFlash('The files have been uploaded.', 'messages/success');
							$this->redirect('/backups');
						}
						else
						{
							$this->Session->setFlash('There was an error uploading the file');
						}
					}
				}
			}
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
			$this->redirect('/backups');
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
			$this->redirect('/backups');
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
			
			$this->Session->setFlash("The file \"{$file['Backup']['name']}\" has been deleted.", 'messages/info');
		}
		
		$this->redirect('/backups');
	}
	
	function deleteAll()
	{
		if($this->data['Backup']['deleteAll'] == 1)
		{
			$this->Backup->deleteAll(array('Backup.user_id' => $this->Session->read('Auth.User.id')));
			$this->_unlinkWildcard(BACKUP_ROOT_DIR . $this->Session->read('Auth.User.id') . DS . "*");
			$this->Session->setFlash("All files in the backup have been deleted.");
		}
		else $this->Session->setFlash("No files have been deleted, please select the checkbox to delete all files.", 'messages/info');
		
		$this->redirect('/users');
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
				$this->Backup->user_id = $this->Session->read('Auth.User.id');
				$this->Backup->saveField('name', $this->data['Backup']['Name']);
				
				// if this is from an ajax call, we want to show the new file name
				if($this->RequestHandler->isAjax()) 
				{
					$this->layout = 'ajax';
					$this->set('file', $this->Backup->findById($id));
				}
				else
				{
					// this is the non-ajax form method, redirect the user
					$this->Session->setFlash('File successfully renamed.');
					$this->redirect('/backups');
				}
			}
			
			$this->set('file', $this->Backup->findById($id));
		}
		else $this->redirect('/backups');
	}
	
	function add_folder()
	{
		if(isset($this->data))
		{
			$this->data['Backup']['type'] = 'directory';
			$this->data['Backup']['user_id'] = $this->Session->read('Auth.User.id');

			if($this->Backup->save($this->data))
			{
				$this->Session->setFlash('Folder "' . $this->data['Backup']['name'] . '" added.', 'messages/success');
			}
			else $this->Session->setFlash('Folder could not be added');
			
			$this->redirect('/backups');
		}
	}
	
	function test()
	{
		//print_r($this->data); return;
		
		// perform download action
		if($this->data['Backup']['action'] == "download")
		{
			$this->_downloadFiles($this->data['Backup']['ids']);
			exit;
		}
		
		// perform delete action
		foreach($this->data['Backup']['ids'] as $key => $value) 
		{
			if($value != 0 && $this->data['Backup']['action'] == "delete") 
			{
				$this->Backup->deleteAll(array('Backup.id' => $key, 'Backup.user_id' => $this->Session->read('Auth.User.id')));
			}
		}
		
		$this->Session->setFlash('The selected files have been deleted.', 'messages/info');
		$this->redirect('/backups');
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
		if(file_exists(BACKUP_ROOT_DIR . $this->Session->read('Auth.User.id')))
		{
			$this->log('Not creating backup store for use with ID ' . $this->Session->read('Auth.User.id') . ', directory already present.', LOG_DEBUG);
			return true;
		}
		
		if(mkdir(BACKUP_ROOT_DIR . $this->Session->read('Auth.User.id'), 0777, true))
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
	
	/**
	 *
	 */
	function _downloadFiles($files)
	{
		$zip = new ZipArchive();
		$filename = BACKUP_ROOT_DIR . $this->Session->read('Auth.User.id') . DS . "download.zip";
		
		if(file_exists($filename)) unlink($filename);

		if ($zip->open($filename, ZIPARCHIVE::CREATE)!==TRUE) 
		{
			exit("cannot open <$filename>\n");
		}
		
		foreach($files as $id => $value)
		{
			$file = $this->Backup->findById($id);
			
			if($value != 0) $zip->addFile(BACKUP_ROOT_DIR . $this->Session->read('Auth.User.id') . DS . $id, $file['Backup']['path'] . DS . $file['Backup']['name']);
		}
		
		$zip->close();
		
		header('Content-type: application/zip');
		header('Content-length: ' . filesize($filename));
		header('Content-Disposition: attachment; filename="download.zip"');
		$fp = fopen($filename, 'r');
		echo fread($fp, filesize($filename));
		fclose($fp);
		exit();
	}
}
?>
