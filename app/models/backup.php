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
			'duplicate' => array
			(
				'rule' => 'isDuplicateFile',
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
	
	/**
	 * Checks if the uploaded file is already present in the given folder. It checks for matching file name, hash and folder.
	 * @return True if the file is already present and is unchanged. Returns false otherwise.
	 */	
	function isDuplicateFile($data)
	{
		return !$this->find('first', array
		(
			'conditions' => array
			(
				'Backup.parent_id' => $this->data['Backup']['parent_id'],
				'Backup.hash' => $this->data['Backup']['hash'],
				'Backup.name' => $this->data['Backup']['name']
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
	 * @return True if the file is too big
	 */
	function isTooBig($data)
	{
	
	}
}
?>