<?php 
uses('sanitize');

class BackupsController extends AppController
{
	var $name = "Backups";
	var $helpers = array('Html', 'Form', 'Ajax');
	var $components = array('RequestHandler', 'Security');
	var $uses = array('Backup', 'SiteParameter');
	
	// pagination defaults
	var $paginate = array
	(
		'limit' => 25,
		'order' => array('Backup.type' => 'desc', 'Backup.name' => 'asc'), // order by type and name
		'recursive' => -1
	);
	
	function beforeFilter()
	{
		parent::beforeFilter();
		
		// ensure ajax methods are posted
		$this->Security->requirePost('get_nodes', 'reorder', 'reparent');
	}

	function test()
	{

	}
	
	function get_nodes()
	{
		// retrieve the node id that Ext JS posts via ajax
		$parent = intval($this->params['form']['node']);
		
		if(empty($parent)) $parent = $this->Backup->getRootFolderId($this->Auth->user('id'));
		
		// find all the nodes underneath the parent node defined above
		// the second parameter (true) means we only want direct children
		$nodes = $this->Backup->children($parent, true, null, 'type DESC, name ASC');
		
		// send the nodes to our view
		$this->set(compact('nodes'));
	}
	
	function reorder()
	{
		// retrieve the node instructions from javascript
		// delta is the difference in position (1 = next node, -1 = previous node)
		
		$node = intval($this->params['form']['node']);
		$delta = intval($this->params['form']['delta']);
		
		if ($delta > 0) {
			$this->Backup->movedown($node, abs($delta));
		} elseif ($delta < 0) {
			$this->Backup->moveup($node, abs($delta));
		}
		
		// send success response
		exit('1');
		
	}
	
	function reparent(){
		
		$node = intval($this->params['form']['node']);
		$parent = intval($this->params['form']['parent']);
		$position = intval($this->params['form']['position']);
		
		if(empty($node)) $node = $this->Backup->getRootFolderId($this->Auth->user('id'));
		
		// save the Backup node with the new parent id
		// this will move the Backup node to the bottom of the parent list
		
		$this->Backup->id = $node;
		$this->Backup->saveField('parent_id', $parent);
		
		// If position == 0, then we move it straight to the top
		// otherwise we calculate the distance to move ($delta).
		// We have to check if $delta > 0 before moving due to a bug
		// in the tree behavior (https://trac.cakephp.org/ticket/4037)
		
		if ($position == 0){
			$this->Backup->moveup($node, true);
		} else {
			$count = $this->Backup->childcount($parent, true);
			$delta = $count-$position-1;
			if ($delta > 0){
				$this->Backup->moveup($node, $delta);
			}
		}
		
		// send success response
		exit('1');
		
	} 
	
	/**
	 * Display a table containing the files and folders
	 */
	function index()
	{
		$this->helpers[] = "Time";
		$this->helpers[] = "Number";
		$this->helpers[] = "File";
		$this->pageTitle = "File Management";
		
		Sanitize::clean($this->params['named']);
		
		// get the search query if set, else set it to empty string to find all files and folders
		if(isset($this->params['named']['query'])) $query = $this->params['named']['query'];
		else $query = "";
		
		if(isset($this->params['named']['view']))
		{
			// get the id of the item to view
			$view = $this->params['named']['view'];
			
			if(!empty($view))
			{
				// try to find the item with the specified id that matches this user's id
				$file = $this->Backup->find('first', array('conditions' => array('id' => $view, 'user_id' => $this->Session->read('Auth.User.id')), 'recursive' => -1));
				
				if(!$file)
				{
					// file doesn't exist or belongs to another user
					$this->Session->setFlash('Could not find any files or folders matching that ID.', 'messages/error');
				}
				elseif($file['Backup']['type'] == 'file')
				{
					// show the file
					$this->_viewFile($file);
				}
				elseif($file['Backup']['type'] == 'folder')
				{
					// set the folder we want to view
					$folder = $view;
				}
			}
		}

		if(!empty($query))
		{
			if(isset($this->params['named']['searchFolder']) && $this->params['named']['searchFolder'] == "0")
			{
				$conditions = array
				(
					'Backup.name LIKE' => '%' . $query . '%',
					'Backup.user_id' => $this->Auth->user('id')
				);
			}
			else
			{
				if(empty($this->params['named']['searchFolder'])) $this->params['named']['searchFolder'] = null;
				
				$conditions = array
				(
					'Backup.name LIKE' => '%' . $query . '%',
					'Backup.user_id' => $this->Auth->user('id'),
					'Backup.parent_id' => $this->params['named']['searchFolder']
				);
			}
		}
		elseif(isset($folder))
		{
			$conditions = array
			(
				'Backup.user_id' => $this->Auth->user('id'),
				'Backup.parent_id' => $folder
			);
		}
		else
		{
			$conditions = array
			(
				'Backup.user_id' => $this->Auth->user('id'),
				'Backup.parent_id' => $this->Backup->getRootFolderId($this->Auth->user('id'))
			);
		}
		
		// get all files and folders to display in the table
		if(isset($folder))
		{
			// only get files and folder which are in the specified folder
			$backups = $this->paginate('Backup', $conditions);
			
			// get the path of the current folder
			$this->set('path', $this->Backup->getpath($folder));
			
			// pass the folder_id to the view
			$this->set('folder_id', $folder);
			
			// get the folders in this folder
			$folders = $this->Backup->find('list', array
			(
				'conditions' => array
				(
					'type' => 'folder',
					'user_id' => $this->Session->read('Auth.User.id'),
					'parent_id' => $folder
				)
			));
			
			// add parent folder to list of folders to move to
			$parent = $this->Backup->getparentnode($folder);	
			$folders[$parent['Backup']['id']] = '&uarr; ' . $parent['Backup']['name'];
			$folders[''] = '&larr; Storage';
		}
		else
		{
			// get the files and folders which are in the root folder (ie folder ID is null)
			$backups = $this->paginate('Backup', $conditions);
			
			// set the folder_id to empty string to indicate root folder, and pass this to the view
			$this->set('folder_id', '');
			
			// get the folders in the root folder
			$folders = $this->Backup->find('list', array
			(
				'conditions' => array
				(
					'type' => 'folder',
					'user_id' => $this->Session->read('Auth.User.id'),
					'parent_id' => null
				)
			));
		}
		
		// if we are displaying search results find out the name of the parent folder of all the files to display in the search listing
		if(!empty($query)) $backups = $this->Backup->getParentFolderNames($backups);
		
		// pass the array of folders to the view for moving files to different folders
		$this->set('folders', $folders);
		
		// pass the query to the view
		$this->set('query', $query);
		
		// pass the upload limit to the view
		$this->set('upload_limit', $this->SiteParameter->getParam('upload_limit'));
		
		// pass the paginated backups to the view
		$this->set(compact('backups'));
	}
	
	/**
	 * Add file to backup form submitted
	 */
	function add() 
	{
		if (!empty($this->data))
		{		
			if(!$this->Backup->createBackupDirectory($this->Auth->user('id')))
			{
				$this->Session->setFlash('Sorry, due to a misconfiguration no files can currently be stored.', 'messages/error');
				$this->redirect($this->referer());
			}
			
			// folder id will be empty to indicate the root folder
			if(empty($this->data['Backup']['parent_id'])) $this->data['Backup']['parent_id'] = $this->Backup->getRootFolderId($this->Auth->user('id'));
			
			$zip = zip_open($this->data['Backup']['file']['tmp_name']);

			// see if the file is a zip
			// is_resoucre alone is not enough as at least odt and ods files are treated as zips by PHP
			if(is_resource($zip) && getFileExtension($this->data['Backup']['file']['name']) == "zip")
			{
				$this->_uploadArchive($zip);
			}
			else
			{
				$this->_uploadFile();
			}
			
			$this->redirect($this->referer());
		}
	}
	
	/**
	 * Called when the add folder form is submitted
	 */
	function add_folder()
	{
		$this->Backup->useValidationRules('NewFolder');
		
		$this->data['Backup']['user_id'] = $this->Session->read('Auth.User.id');
		$this->data['Backup']['type'] = 'folder';
		
		// set parent_id to null for the root folder
		if(empty($this->data['Backup']['parent_id'])) $this->data['Backup']['parent_id'] = $this->Backup->getRootFolderId($this->Auth->user('id'));
		
		// set the data to the model to check if the data is valid
		$this->Backup->set($this->data);
		
		// set the validation errors as a flash message
		if (!$this->Backup->validates()) 
		{
			// join the validation errors and display them in the flash message
			$this->Session->setFlash(join(' ', $this->Backup->invalidFields()), 'messages/error');
			$this->redirect($this->referer());
		}
		
		if($this->Backup->save($this->data))
		{
			$this->Session->setFlash('Folder "' . $this->data['Backup']['name'] . '" added.', 'messages/success');
		}
		else 
		{
			$this->Session->setFlash('The folder could not be created.', 'messages/error');
		}
		
		$this->redirect($this->referer());
	}
	
	/**
	 * Deletes all files and folders for this user
	 */
	function deleteAll()
	{
		if($this->data['Backup']['deleteAll'] == 1)
		{
			// delete all files in this user's file storage and database
			$this->Backup->emptyStore($this->Session->read('Auth.User.id'));
			
			$this->Session->setFlash("All files in the backup have been deleted.", 'messages/info');
		}
		else $this->Session->setFlash("No files have been deleted, please select the checkbox to delete all files.", 'messages/info');
		
		$this->redirect('/users');
	}
	
	function rename($id)
	{
		// apply the appropriate validation set so the view is updated with any required fields
		$this->Backup->setValidate();
		
		// check user owns this file and it exists, if not redirect them
		if($this->Backup->find('count', array('conditions' => array('Backup.id' => $id, 'Backup.user_id' => $this->Session->read('Auth.User.id')))) == 1)
		{
			// check for POST data
			if(isset($this->data))
			{
				$this->Backup->id = $id;
				$this->Backup->useValidationRules('Rename');
				
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
						$this->redirect($this->referer());
					}
					else $this->Session->setFlash('The file could not be renamed.', 'messages/error');
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
		if(isset($this->data))
		{
			if(!isset($this->data['Backup']['ids'])) $this->data['Backup']['ids'] = array();
			
			// perform appropriate action
			switch($this->data['Backup']['action'])
			{
				case "download":
					// perform download action
					$this->_downloadFiles($this->data['Backup']['ids']);
					exit;
					break;
					
				case "delete":
					// perform delete action
					$this->_deleteFiles($this->data['Backup']['ids']);
					break;
					
				case "move":
					// perform move file action
					$this->_moveFiles($this->data['Backup']['ids'], $this->data['Backup']['folder']);
					break;
			}
		}
		
		$this->redirect($this->referer());
	}
	
	//
	// Private functions
	//
	
	/**
	 * Checks if the name of the folder already exists, if so returns the ID of it. Else it creates a new folder with the specified name
	 * and returns the newly created folder's ID.
	 * @param path The path of folders to add
	 * @param parent_id The parent folder to store the new folders in, defaults to root folder
	 * @return The ID of the folder
	 */
	function _createFolders($path, $parent_id = null)
	{
		// if the dot folder name is given then the file is destined for the root folder, which has a null id
		if($path == '.') return $parent_id;
		
		$ds = getDirectorySeparator($path);
		
		$folderNames = explode($ds, $path);
		
		foreach($folderNames as $folderName)
		{
			$parent_id = $this->Backup->createFolder($folderName, $parent_id, $this->Auth->user('id'));
		}
		
		return $parent_id;
	}
	
	
	/**
	 * Called when the add file form is submitted. Uploads the file to the user's storage.
	 */
	function _uploadFile() 
	{
		$this->Backup->create();
		$this->data['Backup']['name'] = Sanitize::escape($this->data['Backup']['file']['name']);
		$this->data['Backup']['size'] = filesize($this->data['Backup']['file']['tmp_name']);
		$this->data['Backup']['hash'] = md5(file_get_contents($this->data['Backup']['file']['tmp_name']));
		$this->data['Backup']['user_id'] = $this->Session->read('Auth.User.id');
		$this->data['Backup']['type'] = 'file';
		
		// check if file can be stored within the quota limit
		$quota = $this->Session->read('Auth.User.quota');
		$usage = $this->Backup->find('all', array('fields'=>'SUM(size) as size', 'conditions' => array('Backup.user_id' => $this->Session->read('Auth.User.id'))));
		
		if($usage[0][0]['size'] + $this->data['Backup']['size'] > $quota)
		{
			$this->Session->setFlash('Sorry, there is not enough space to store this file.', 'messages/error');
			$this->redirect($this->referer());
			return;
		}
		
		if($this->Backup->save($this->data))
		{
			move_uploaded_file($this->data['Backup']['file']['tmp_name'], BACKUP_ROOT_DIR . $this->Session->read('Auth.User.id') . DS . $this->Backup->id);
			$this->Session->setFlash('The file has been uploaded.', 'messages/success');
		}
		else
		{
			$this->_persistValidation('Backup'); 
			$this->Session->setFlash(join(' ', $this->Backup->invalidFields()), 'messages/error');
		}
	}
	
	/**
	 * Called when the add file form is submitted. Extracts the archive and stores the files and (non-empty) folders.
	 */
	function _uploadArchive($zip) 
	{
		// remember the base parent folder as the data array will change
		$base_parent_id = $this->data['Backup']['parent_id'];
		
		while ($zip_entry = zip_read($zip))
		{
			if(zip_entry_filesize($zip_entry) <= 0) continue;
			
			$this->data['Backup']['parent_id'] = $this->_createFolders(dirname(zip_entry_name($zip_entry)), $base_parent_id);
			
			$this->Backup->create();
			$this->data['Backup']['name'] = Sanitize::escape(basename(zip_entry_name($zip_entry)));
			$this->data['Backup']['size'] = zip_entry_filesize($zip_entry);
			$this->data['Backup']['data'] = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
			$this->data['Backup']['hash'] = md5($this->data['Backup']['data']);
			$this->data['Backup']['user_id'] = $this->Session->read('Auth.User.id');
			$this->data['Backup']['type'] = 'file';
			
			// check if file can be stored within the quota limit
			$quota = $this->Session->read('Auth.User.quota');
			$usage = $this->Backup->find('all', array('fields'=>'SUM(size) as size', 'conditions' => array('Backup.user_id' => $this->Session->read('Auth.User.id'))));
			
			if($usage[0][0]['size'] + $this->data['Backup']['size'] > $quota)
			{
				$this->Session->setFlash('Sorry, not all files could be uploaded as one or more were too big.', 'messages/error');
				$this->redirect($this->referer());
				return;
			}
			
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
	 * Downloads multiple files and folders by placing them into a zip archive
	 */
	function _downloadFiles($selectedFiles)
	{
		$zip = new ZipArchive();
		$filename = BACKUP_ROOT_DIR . $this->Session->read('Auth.User.id') . DS . "download.zip";
		
		// delete old zip file if present
		if(file_exists($filename)) unlink($filename);
		
		if ($zip->open($filename, ZIPARCHIVE::CREATE)!==TRUE) 
		{
			exit("cannot open <$filename>\n");
		}
		
		$folders = array();
		
		// add selected folders to zip
		foreach($folders as $id => $value)
		{
			// skip any unselected folders
			if($value == 0) continue;
			
			// add files in the folder
			$files = $this->Backup->find('all', array
			(
				'conditions' => array
				(
					'backup_folder_id' => $id,
					'user_id' => $this->Session->read('Auth.User.id')
				),
				'recursive' => -1
			));
			
			// get path
			$paths = $this->Backup->BackupFolder->getpath($id);
			$path = "";
			
			foreach($paths as $patha)
			{
				$path .= $patha['BackupFolder']['name'] . DS;
			}
			
			foreach($files as $file)
			{
				$zip->addFile(BACKUP_ROOT_DIR . $this->Session->read('Auth.User.id') . DS . $file['Backup']['id'], $path . $file['Backup']['name']);
			}
			
			// add files in the folder's child folders
			$childFolders = $this->Backup->BackupFolder->children($id);
			
			foreach($childFolders as $childFolder)
			{
				// add files in each child folder
				$files = $this->Backup->find('all', array
				(
					'conditions' => array
					(
						'backup_folder_id' => $childFolder['BackupFolder']['id'],
						'user_id' => $this->Session->read('Auth.User.id')
					),
					'recursive' => -1
				));
				
				//print_r($files); return;
				
				// get path
				$paths = $this->Backup->BackupFolder->getpath($childFolder['BackupFolder']['id']);
				$path = "";
				
				//print_r($paths); return;
				
				foreach($paths as $patha)
				{
					$path .= $patha['BackupFolder']['name'] . DS;
				}
				
				foreach($files as $file)
				{
					$zip->addFile(BACKUP_ROOT_DIR . $this->Session->read('Auth.User.id') . DS . $file['Backup']['id'], $path . $file['Backup']['name']);
				}
			}
		}
		
		// add selected files to zip
		foreach($selectedFiles as $id => $value)
		{
			// skip unselected files
			if($value == 0) continue;
			
			$file = $this->Backup->findById($id);
			
			if($file['Backup']['type'] == 'folder')
			{
				$folderChildren = $this->Backup->children($file['Backup']['id']); 
				
				foreach($folderChildren as $child)
				{
					$zip->addFile(BACKUP_ROOT_DIR . $this->Session->read('Auth.User.id') . DS . $child['Backup']['id'], $this->_getVirtualPath($child['Backup']['parent_id']) . $child['Backup']['name']);
				}
			}
			else
			{
				$zip->addFile(BACKUP_ROOT_DIR . $this->Session->read('Auth.User.id') . DS . $id, $this->_getVirtualPath($file['Backup']['parent_id']) . $file['Backup']['name']);
			}
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
	
	function _deleteFiles($files)
	{
		foreach($files as $id => $value)
		{
			if($value == 1 && $this->Backup->userOwnsFile($id, $this->Session->read('Auth.User.id'))) 
			{
				$this->Backup->id = $id;
				$file = $this->Backup->findById($id);
				
				// if the id belongs to a folder, delete all the child files
				if($file['Backup']['type'] == 'folder')
				{
					$children = $this->Backup->children();
					
					foreach($children as $child)
					{
						if($child['Backup']['type'] != 'folder')
						{
							// delete child file from file system
							unlink(BACKUP_ROOT_DIR . $this->Session->read('Auth.User.id') . DS . $child['Backup']['id']);
						}
					}
				}
				else
				{
					// delete the file from file system
					unlink(BACKUP_ROOT_DIR . $this->Session->read('Auth.User.id') . DS . $id);			
				}

				// delete file or folder from database			
				$this->Backup->delete();
			}
		}
		
		$this->Session->setFlash('The selected files and folders have been deleted.', 'messages/info');
	}
	
	function _moveFiles($files, $folder_id)
	{
		$error = false;
		
		foreach($files as $id => $value)
		{
			if($value == 1 && $this->Backup->userOwnsFile($id, $this->Session->read('Auth.User.id'))) 
			{
				$this->Backup->id = $id;
				
				$this->Backup->useValidationRules('Move');
				
				// check user owns the folder, or, that the user is moving it to the root folder
				if($folder_id != "" && !$this->Backup->userOwnsFile($folder_id, $this->Session->read('Auth.User.id'))) continue;
				
				// try to move the file. This will fail if a folder is moved into itself.
				if(!$this->Backup->save(array('parent_id' => $folder_id)))
				{
					$error = true;
				}
			}
		}
		
		if($error) $this->Session->setFlash('Some files or folders could not be moved, please check there are no files with the same name in the destination folder.', 'messages/error');
		else $this->Session->setFlash('The selected files and folders have been moved.', 'messages/info');		
	}
	
	/**
	 * Gets the virtual path of the given file/folder. Folder names are defined by the user.
	 */
	function _getVirtualPath($id)
	{
		// get the path of the file
		$folders = $this->Backup->getpath($id);
		
		$path = "";
		
		if(!$folders) return $path;
		
		foreach($folders as $folder)
		{
			$path .= $folder['Backup']['name'] . DS;
		}
		
		return $path;
	}
	
	function _viewFile($file)
	{
		Configure::write('debug', 0);
		$fp = fopen(BACKUP_ROOT_DIR . $this->Session->read('Auth.User.id') . DS . $file['Backup']['id'], 'r');
	
		header('Content-type: unkown');
		header('Content-length: ' . $file['Backup']['size']);
		header('Content-Disposition: inline; filename="'.$file['Backup']['name'].'"');
		echo fread($fp, $file['Backup']['size']);
		fclose($fp);
		exit();
	}
}
?>