<?php
class Backup extends AppModel 
{
	var $name = 'Backup';
	var $belongsTo = array('User');
	var $actsAs = array('Tree');
	var $validate = array 
	(
		'name' => array
		(
			'empty' => array
			(
				'rule' => array('custom', '/\S+/'),
				'message' => '', // we do not need this to display when uploading files or creating folders
			)
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
			'duplicate' => array
			(
				'rule' => 'isDuplicateFile',
				'message' => 'The selected file was skipped as it is already present in this folder.',
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
	
	function isDuplicateFile($data)
	{
		return !$this->find('first', array
		(
			'conditions' => array
			(
				'Backup.parent_id' => $this->data['Backup']['parent_id'],
				'Backup.hash' => $this->data['Backup']['hash'],
				'Backup.user_id' => $this->Auth->user('id'),
			)
		));
	}
}
?>