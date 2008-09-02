<?php 
uses('sanitize');

class BackupsController extends AppController
{
	var $name = "Backups";
	var $helpers = array('Html', 'Form', 'Ajax');
    var $paginate = array
	(
        'limit' => 25,
        'order' => array('Backup.name' => 'asc')
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
		
		if(isset($this->params['named']['folder'])) $folder = Sanitize::escape($this->params['named']['folder']);
		
		// get a list of all the directories for the select boxes
		$directoriesList = $this->Backup->BackupFolder->find('list', array('conditions' => array('BackupFolder.user_id' => $this->Session->read('Auth.User.id'))));
		// add root folder with null folder id
		$directoriesList[''] = 'filestorage';
		
		ksort($directoriesList);
		
		$this->set('directoriesList', $directoriesList);
		
		// get all backups and folders to display in the table
		if(isset($folder))
		{
			// only get files and folder which are in the specified folder
			$backups = $this->paginate('Backup', "Backup.name LIKE '%$query%' AND Backup.user_id = {$this->Session->read('Auth.User.id')} AND backup_folder_id = '$folder'", array('recursive'=>2));
			$this->set('directories', $this->Backup->BackupFolder->find('all', array('conditions' => array('BackupFolder.user_id' => $this->Session->read('Auth.User.id'), 'parent_id' => $folder))));
			
			// get the path of the current folder
			$this->set('path', $this->Backup->BackupFolder->getpath($folder));
		}
		else
		{
			// get the files and folders which are in the root folder (ie folder ID is null)
			$backups = $this->paginate('Backup', "Backup.name LIKE '%$query%' AND Backup.user_id = {$this->Session->read('Auth.User.id')} AND backup_folder_id IS NULL");
			$this->set('directories', $this->Backup->BackupFolder->find('all', array('conditions' => array('BackupFolder.user_id' => $this->Session->read('Auth.User.id'), 'parent_id' => null))));
		}
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
			
			//print_r($this->data); return;
			
			// folder id will be empty to indicate the root folder
			if(empty($this->data['Backup']['backup_folder_id'])) $this->data['Backup']['backup_folder_id'] = null;
			
			$zip = zip_open($this->data['Backup']['file']['tmp_name']);
			
			// see if the file is a zip
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
						
						$this->Session->setFlash('The files have been uploaded.', 'messages/success');
					}
					else
					{
						$this->Session->setFlash('There was an error uploading the file.', 'messages/error');
					}
				}
			}
			else
			{
				$this->Backup->create();
								
				// date isn't automagically inserted by Cake for some reason
				$this->data['Backup']['created'] = date('Y-m-d H:i:s');
				$this->data['Backup']['modified'] = date('Y-m-d H:i:s');
				$this->data['Backup']['name'] = $this->data['Backup']['file']['name'];
				$this->data['Backup']['size'] = filesize($this->data['Backup']['file']['tmp_name']);
				$this->data['Backup']['hash'] = md5(file_get_contents($this->data['Backup']['file']['tmp_name']));
				$this->data['Backup']['user_id'] = $this->Session->read('Auth.User.id');
				
				if($this->Backup->save($this->data))
				{
					$this->_createBackupDirectory();
					move_uploaded_file($this->data['Backup']['file']['tmp_name'], BACKUP_ROOT_DIR . $this->Session->read('Auth.User.id') . DS . $this->Backup->id);
					$this->Session->setFlash('The file has been uploaded.', 'messages/success');
				}
				else
				{
					$this->_persistValidation('Backup'); 
					$this->Session->setFlash('There was an error uploading the file.', 'messages/error');
				}
			}
			
			$this->redirect('/backups');
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
			$file = $this->Backup->findById($id);		
			Configure::write('debug', 0);
			$fp = fopen(BACKUP_ROOT_DIR . $this->Session->read('Auth.User.id') . DS . $file['Backup']['id'], 'r');
		
			header('Content-type: file');
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
	
	/**
	 * Deletes all files and folders for this user
	 */
	function deleteAll()
	{
		if($this->data['Backup']['deleteAll'] == 1)
		{
			// delete all file metadata from the database
			$this->Backup->deleteAll(array('Backup.user_id' => $this->Session->read('Auth.User.id')));
			
			// delete all folders
			$this->Backup->BackupFolder->deleteAll(array('BackupFolder.user_id' => $this->Session->read('Auth.User.id')));
			
			// delete all files in this user's file storage
			$this->_unlinkWildcard(BACKUP_ROOT_DIR . $this->Session->read('Auth.User.id') . DS . "*");
			
			$this->Session->setFlash("All files in the backup have been deleted.", 'messages/info');
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
				
				// if this is from an ajax call, we want to show the new file name
				if($this->RequestHandler->isAjax()) 
				{
					$this->Backup->saveField('name', $this->data['Backup']['name'], true);
					$this->layout = 'ajax';
				}
				else
				{
					if($this->Backup->save(array('name' => $this->data['Backup']['name']), true))
					{
						$this->Session->setFlash('File successfully renamed.', 'messages/info');
						
						// this is the non-ajax form method, redirect the user
						$this->redirect('/backups');
					}
					else $this->Session->setFlash('The file could not be renamed', 'messages/error');
				}
			}
			
			// get the file name
			$this->set('file', $this->Backup->findById($id));
		}
		else $this->redirect('/backups');
	}
	
	/**
	 * Called when the user selects multiple files/folders
	 */
	function perform_action()
	{
		//print_r($this->data); return;
		
		if(isset($this->data))
		{
			if(!isset($this->data['Backup']['ids'])) $this->data['Backup']['ids'] = array();
			if(!isset($this->data['BackupFolder']['ids'])) $this->data['BackupFolder']['ids'] = array();
			
			// perform appropriate action
			switch($this->data['Backup']['action'])
			{
				case "download":
					// perform download action
					$this->_downloadFiles($this->data['Backup']['ids'], $this->data['BackupFolder']['ids']);
					exit;
					break;
					
				case "delete":
					// perform delete action
					$this->_deleteFiles($this->data['Backup']['ids'], $this->data['BackupFolder']['ids']);
					break;
			}
		}
		
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
	
	function _deleteFiles($files, $folders)
	{
		foreach($files as $id => $value) 
		{
			if($value == 1) 
			{
				$this->Backup->deleteAll(array('Backup.id' => $id, 'Backup.user_id' => $this->Session->read('Auth.User.id')));
				
				$absoluteFile = BACKUP_ROOT_DIR . $this->Session->read('Auth.User.id') . DS . $id;
			
				if(unlink($absoluteFile))
				{
					$this->log('Deleted file: ' . $absoluteFile, LOG_DEBUG);
				}
				else
				{
					$this->log("Could not delete file: " . $absoluteFile);
				}
			}
		}
		
		foreach($folders as $id => $value) 
		{
			if($value == 1) 
			{
				// delete all files in this folder
				//$this->BackupFolder->Backup->deleteAll(array('backup_folder_id' => $id));
				$this->Backup->BackupFolder->id = $id;
				$this->Backup->BackupFolder->delete();
				
				/**
				 * Need to check the user_id here
				 *
				 */
			}
		}
		
		$this->Session->setFlash('The selected folders/files have been deleted.', 'messages/info');
	}
}
?>
