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
}
?>