<?php
class Backup extends AppModel 
{
	var $name = 'Backup';
	var $belongsTo = array('User');
	var $actsAs = array('Tree', 'MultipleValidatable');
	
	// default validation applies when uploading files
	var $validate = array 
	(
		'file' => array 
		(
			'valid_data' => array 
			(
				'rule' => 'validateUploadedFile',
				'message' => 'Sorry, an error occurred whilst uploading. Please check the file is not too big.',
			),
			'duplicates' => array
			(
				'rule' => 'checkForDuplicates',
				'message' => 'The selected file was skipped as it is already present in this folder.'
			),
			'file_size' => array
			(
				'rule' => array('validateFileSize', true),
				'message' => 'Please select a non-empty file.',
				'required' => true
			)
		)
	); 
	
	var $validateNewFolder = array
	(
		'name' => array
		(
			'empty' => array
			(
				'rule' => array('custom', '/\S+/'),
				'message' => 'Please enter a name for the folder.',
				'required' => true
			),
			'uniqueName' => array
			(
				'rule' => 'validateNewFolder',
				'message' => 'The filename is already present in this folder.',
			)	
		)
	);
	
	// validation set for renaming files and folders
	var $validateRename = array
	(
		'name' => array
		(
			'empty' => array
			(
				'rule' => array('custom', '/\S+/'),
				'message' => 'Please enter a name for the file',
				'required' => true
			),
			'uniqueName' => array
			(
				'rule' => 'validateRename',
				'message' => 'The filename is already present in this folder.',
			)
		)
	);
	
	// validation set for moving files or folders
	var $validateMove = array
	(
		// the folder the file or folder is being moved to must not contain a file or folder of the same name
		'parent_id' => array
		(
			'uniqueName' => array
			(
				'rule' => 'validateMove',
				'message' => '',
				'required' => true
			)
		)
	);
	
	/**
	 * Custom validation rule for uploaded files.
	 *
	 *  @param Array $data CakePHP File info.
	 *  @param Boolean $required Is this field required?
	 *  @return Boolean
	 */
	function validateUploadedFile($data) 
	{
		// Remove first level of array
		$upload_info = array_shift($data);
		
		// Check for Basic PHP file errors.
		if ($upload_info['error'] !== 0) 
		{
			$this->log("Error whilst uploading file, error code: " . $upload_info['error']);
			
			return false;
		}
		
		return is_uploaded_file($upload_info['tmp_name']);
	}
	
	/**
	 * Custom validation rule for uploaded files.
	 *
	 *  @param Array $data CakePHP File info.
	 *  @param Boolean $required Is this field required?
	 *  @return Boolean
	 */
	function validateFileSize($data, $required = false)
	{
		// Remove first level of array
		$upload_info = array_shift($data);

		// No file uploaded.
		if ($required && filesize($upload_info['tmp_name']) == 0) 
		{
			return false;
		}
		
		return true;
	}
	
	/**
	 * Recursively deletes all the files and folders in the user's file store from the file system. The storage folder will have to be recreated
	 * after calling this function.
	 * @param $id The id of the user who's store should be deleted
	 */
	function emptyStore($id)
	{
		// delete all files and folders from the database
		$this->deleteAll(array('Backup.user_id' => $id));
		
		// delete files from filesystem
		rmRecursive(BACKUP_ROOT_DIR . $id);
	}
	
	/**
	 * Method for checking for duplicate files and renaming files appropriately. It will first check to see if there is already a file in the folder present 
	 * that has the same hash. If not, it will check if the filename is unique in the folder. If the filename is not unique, the filename is changed, which is
	 * then checked for uniqueness. This process will continue until a unique name is found.
	 */
	function checkForDuplicates($data)
	{		
		// fail validation if the file is already there
		if($this->isDuplicateFile($this->data['Backup']['name'], $this->data['Backup']['parent_id'], $this->data['Backup']['hash'])) return false;
		
		// get extension with the dot prefixed
		$extension = getFileExtension($this->data['Backup']['name'], true);
		
		// get the name of the file without the dot and extension
		$name = stripFileExtension($this->data['Backup']['name'], $extension);
		$counter = 1;
		$newName = $name . $extension;
		
		// check if the filename is there with a different hash	
		while(!$this->uniqueFilename($newName, $this->data['Backup']['parent_id']))
		{
			$suffix = '(' . $counter . ')';
			$newName = $name . $suffix . $extension;
			if($this->isDuplicateFile($newName, $this->data['Backup']['parent_id'], $this->data['Backup']['hash'])) return false;
			$counter++;
		}
		
		$this->data['Backup']['name'] = $newName;
		
		return true;
	}
	
	/**
	 * Checks if the given filename is unique in the given folder
	 * @param name The name to check the uniqueness of
	 * @param parent_id The folder to check in
	 * @return True if filename is unique
	 */
	function uniqueFilename($name, $parent_id)
	{
		return !(boolean) $this->find('first', array
		(
			'conditions' => array
			(
				'Backup.parent_id' => $parent_id,
				'Backup.name' => $name
			)
		));
	}
	
	/**
	 * Checks if the file is already present in the given folder. It checks for matching file name, hash and parent folder.
	 * @param name The name of the file
	 * @param parent_id The id of the folder to check in
	 * @param hash The hash of the file to determine if the file contents are the same
	 * @return True if the file is already present and is unchanged. Returns false otherwise.
	 */	
	function isDuplicateFile($name, $parent_id, $hash)
	{
		return (boolean) $this->find('first', array
		(
			'conditions' => array
			(
				'Backup.parent_id' => $this->data['Backup']['parent_id'],
				'Backup.hash' => $this->data['Backup']['hash'],
				'Backup.name' => $name
			),
			'recursive' => false
		));
	}
	
	function validateNewFolder($data)
	{
		return !(boolean) $this->find('count', array
		(
			'conditions' => array
			(
				'Backup.parent_id' => $this->data['Backup']['parent_id'],
				'Backup.name' => $data['name'],
				'Backup.user_id' => $this->data['Backup']['user_id']
			)	
		));
	}
	
	/**
	 * Checks if a rename is valid by checking if the file name is unique in the given folder
	 */
	function validateRename($data)
	{
		$this->recursive = -1;
		$file = $this->findById($this->id);
		
		return !(boolean) $this->find('count', array
		(
			'conditions' => array
			(
				'Backup.parent_id' => $file['Backup']['parent_id'],
				'Backup.name' => $data['name'],
				'Backup.id <>' => $this->id,
				'Backup.user_id' => $file['Backup']['user_id']
			)	
		));
	}
	
	/**
	 * Checks if moving a file or folder is valid by checking if a file or folder is already present in the destination folder
	 */
	function validateMove($data)
	{
		$this->recursive = -1;
		
		// get the file that is to be moved
		$file = $this->findById($this->id);
				
		if(empty($data['parent_id'])) $data['parent_id'] = null;

		// see if there are any files in the destination folder with the same name
		// if so, we want to return false to indicate validation failed
		return !(boolean) $this->find('count', array
		(
			'conditions' => array
			(
				'Backup.parent_id' => $data['parent_id'],
				'Backup.name' => $file['Backup']['name'],
				'Backup.user_id' => $file['Backup']['user_id']
			)	
		));
	}
	
	/**
	 * Checks if the file fits within the quota
	 * @return False if the file is too big to fit in the quota
	 */
	function checkQuota($data)
	{
		// check if file can be stored within the quota limit
		/*
		$quota = $this->Session->read('Auth.User.quota');
		$usage = $this->Backup->find('all', array('fields'=>'SUM(size) as size', 'conditions' => array('Backup.user_id' => $this->Session->read('Auth.User.id'))));
		
		if($usage[0][0]['size'] + $this->data['Backup']['size'] > $quota)
		{
			$this->Session->setFlash('Sorry, there is not enough space to store this file.', 'messages/error');
			$this->redirect($this->referer());
			return;
		}*/
	}
	
	/**
	 * Checks if the name of the folder already exists, if so returns the ID of it. Else it creates a new folder with the specified name
	 * and returns the newly created folder's ID.
	 * @return The ID of the folder
	 */
	function createFolder($folderName, $parentId, $userId)
	{
		//echo "Creating folder: " . $folderName . ', parent_id: ' . $parentId . '<br />';
		
		// look for a folder matching the name and parent id
		$folder = $this->find('first', array
		(
			'conditions' => array
			(
				'Backup.name' => $folderName,
				'Backup.parent_id' => $parentId,
				'Backup.user_id' => $userId
			)
		));
		
		// return the folder id if it exists
		if($folder) return $folder['Backup']['id'];
		
		$this->create();
		
		// create a new folder
		$newFolder = array
		(
			'Backup' => array
			(
				'name' => $folderName,
				'type' => 'folder',
				'parent_id' => $parentId,
				'user_id' => $userId
			)
		);
		
		$this->save($newFolder);
		
		return $this->id;
	}
	
	/**
	 * Used for file searches to retrieve the name of the parent folder to improve search listings
	 * @param backups Array of files
	 */
	function getParentFolderNames($backups)
	{
		// go through each file in the search results
		for($i = 0; $i < count($backups); $i++)
		{
			// if the file's parent is null then it's in the root storage folder
			if($backups[$i]['Backup']['parent_id'] == null)
			{
				$backups[$i]['Backup']['folder_name'] = 'Storage';
			}
			else
			{
				// find the folder corresponding to the parent_id
				$folder = $this->findById($backups[$i]['Backup']['parent_id']);
				
				$backups[$i]['Backup']['folder_name'] = $folder['Backup']['name'];
			}
		}
		
		return $backups;
	}
	
	/**
	 * Checks if the given user owns the given file/folder
	 * @return True if the file/folder belongs to the logged in user, false otherwise
	 */
	function userOwnsFile($id, $user)
	{
		return (boolean) $this->find('count', array('conditions' => array('Backup.id' => $id, 'Backup.user_id' => $user)));
	}
	
	/**
	 * Creates the root directory for the user's backups
	 * @return boolean True if the backup directory is present, false otherwise
	 */
	function createBackupDirectory($user_id)
	{
		if(file_exists(BACKUP_ROOT_DIR . $user_id))
		{
			$this->log('Not creating backup store for user with ID ' . $user_id . ', directory already present.', LOG_DEBUG);
			return true;
		}
		
		if(mkdir(BACKUP_ROOT_DIR . $user_id, 0777, true))
		{
			$this->log('Created backup store for user with ID ' . $user_id, LOG_DEBUG);
			return true;
		}
		else
		{
			$this->log('Could not create backup store for user with ID ' . $user_id . '. Please check permissions.');
			return false;
		}
	}
	
	function getRootFolderId($user_id)
	{
		$folder = $this->find('first', array('conditions' => array('user_id' => $user_id, 'parent_id' => null)));
		
		// create root folder in the database if it doesn't exist
		if(!$folder)
		{
			$this->save
			(
				array
				(
					'user_id' => $user_id,
					'parent_id' => null,
					'type' => 'root_folder',
					'name' => $user_id . '_root_folder'
				)
			);
			
			return $this->id;
		}
		
		return $folder['Backup']['id'];
	}
}
?>