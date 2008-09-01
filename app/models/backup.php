<?php
class Backup extends AppModel 
{
	var $name = 'Backup';
	var $belongsTo = array
	(
		'User' => array('className' => 'User',
						'foreignKey' => 'user_id',
						'conditions' => '',
						'fields' => '',
						'order' => ''
		)
	);
	var $validate = array 
	(
		'file' => array 
		(
			'valid_data' => array 
			(
				'rule' => array('validateUploadedFile', true),
				'message' => 'An error occurred whilst uploading'
			)
		),
		'name' => array
		(
			'empty' => array
			(
				'rule' => array('custom', '/\S+/'),
				'message' => 'Please enter a name',
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
	function validateUploadedFile($data, $required = false) 
	{
		// Remove first level of array
		$upload_info = array_shift($data);

		// No file uploaded.
		if ($required && filesize($upload_info['tmp_name']) == 0) 
		{
			return false;
		}

		// Check for Basic PHP file errors.
		if ($upload_info['error'] !== 0) 
		{
			return false;
		}
		
		return is_uploaded_file($upload_info['tmp_name']);
	}
}
?>