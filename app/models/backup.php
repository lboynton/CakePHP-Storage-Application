<?php
class Backup extends AppModel 
{
	var $name = 'Backup';
	var $belongsTo = array('User');
	var $actsAs = array('Tree', 'MultipleValidatable');
	
	// default validation applies when uploading files and creating folders
	var $validate = array 
	(
		'name' => array
		(
			'empty' => array
			(
				'rule' => array('custom', '/\S+/'),
				'message' => 'Please enter a name for the file',
			),
		),
		'file' => array 
		(
			'file_size' => array
			(
				'rule' => array('validateFileSize', true),
				'message' => 'Please select a non-empty file.',
			),
			'valid_data' => array 
			(
				'rule' => 'validateUploadedFile',
				'message' => 'Sorry, an error occurred whilst uploading.',
			),
			'duplicates' => array
			(
				'rule' => 'checkForDuplicates',
				'message' => 'The selected file was skipped as it is already present in this folder.'
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
		$this->rmRecursive(BACKUP_ROOT_DIR . $id);
	}
	
	/**
	 * @description Remove recursively. (Like `rm -r`)
	 * @see Comment by davedx at gmail dot com on { http://us2.php.net/manual/en/function.rmdir.php }
	 * @param file {String} The file or folder to be deleted.
	 **/
	function rmRecursive($file) 
	{
		if (is_dir($file) && !is_link($file)) 
		{
			foreach(glob($file.'/*') as $sf) 
			{
				if ( !$this->rmRecursive($sf) ) 
				{
					$this->log("Failed to remove $sf\n");
					return false;
				}
			}
			return rmdir($file);
		} 
		else 
		{
			return unlink($file);
		}
	}
	
	function checkForDuplicates($data)
	{		
		// fail validation if the file is already there
		if($this->isDuplicateFile($this->data['Backup']['name'], $this->data['Backup']['parent_id'], $this->data['Backup']['hash'])) return false;
		
		$extension = getFileExtension($this->data['Backup']['name']);
		$name = stripFileExtension($this->data['Backup']['name'], $extension);
		$suffix = "";
		$counter = 1;
		$newName = $name . '.' . $extension;
		
		// check if the filename is there with a different hash	
		while(!$this->uniqueFilename($newName, $this->data['Backup']['parent_id']))
		{
			$suffix = '(' . $counter . ')';
			$newName = $name . $suffix . '.' . $extension;
			if($this->isDuplicateFile($newName, $this->data['Backup']['parent_id'], $this->data['Backup']['hash'])) return false;
			$counter++;
		}
		
		$this->data['Backup']['name'] = $newName;
		
		return true;
	}
	
	function incrementFilename()
	{
		$this->data['Backup']['name'] .= 'a';
	}
	
	/**
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
	 * Checks if the uploaded file is already present in the given folder. It checks for matching file name, hash and parent folder.
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
}
?>