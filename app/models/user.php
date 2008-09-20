<?php 
class User extends AppModel
{
    var $name = 'User';
	var $actsAs = array('MultipleValidatable');
	var $hasMany = array
	(
        'Backup' => array
		(
            'className'     => 'Backup',
            'foreignKey'    => 'user_id',
        )
    );  
	
	// default validation rules
	var $validate = array
	(
		'email' => array
		(
			'rule' => 'email',
			'required' => true,
			'message' => 'Please enter a valid email address',
		),
        'username' => array
		(
            'unique' => array
			(
                'rule' => 'isUnique',
                'required' => true,
                'message' => 'This username has already been taken, sorry!',
            ),
            'alphanumeric' => array(
                'rule' => 'alphaNumeric',
                'required' => true,
                'message' => 'Username must be alphanumeric',
            ),
            'empty' => array
			(
				'rule' => array('custom', '/\S+/'),
				'required' => true,
				'message' => 'Please enter your username',
            )
        ),
		'new_password' => array
		(
			'rule' => array('minLength', '6'),
			'message' => 'Password must be at least 6 characters long',
			'required' => true,
        ),
		'confirm_password' => array
		(
			'empty' => array
			(
				'rule' => array('minLength', '6'),
				'message' => 'Password must be at least 6 characters long',
				'required' => true,
			),
			'identical' => array
			(
				'rule' => array('identicalFieldValues', 'new_password'),
				'message' => 'Passwords do not match',
				'required' => true,
			)
        ),
		'password' => array // required when logging in
		(
			'required' => true,
			'message' => 'Please enter your password',
			'rule' => array('custom', '/\S+/'),
			'on' => 'update'
		)
	);
	
	// validation set for editing user profile
	var $validateEditDetails = array
	(
		'email' => array
		(
			'rule' => 'email',
			'required' => true,
			'message' => 'Please enter a valid email address'
		)
	);
	
	// validation set for changing the password
	var $validateChangePassword = array
	(
		'old_password' => array
		(
			'rule' => 'matchOldPassword',
			'required' => true,
			'message' => 'Does not match your old password'
		),
		'new_password' => array
		(
			'rule' => array('minLength', '6'),
			'message' => 'Password must be at least 6 characters long',
			'required' => true
        ),
		'confirm_password' => array
		(
			'empty' => array
			(
				'rule' => array('minLength', '6'),
				'message' => 'Password must be at least 6 characters long',
				'required' => true,
			),
			'identical' => array
			(
				'rule' => array('identicalFieldValues', 'new_password'),
				'message' => 'Passwords do not match',
				'required' => true,
			)
        ),
	);
	
	// validation rules for admin edit user
	var $validateAdminUserView = array
	(
		'unit' => array
		(
			'rule' => array('inList', array('b', 'kb', 'mb', 'gb')),
			'message' => 'Unsupported unit specified',
			'required' => true
		)
	);
	
	// validation rules for admin user level change
	var $validateAdminUserLevel = array
	(
		'admin' => array
		(
			'rule' => array('inList', array('0', '1')),
			'message' => 'Incorrect value supplied',
			'required' => true
		)
	);
	
	/**
	 * Called after validation, before data is stored in the database
	 */
	function beforeSave()
	{
		// hash the password before storing a new user in the database
		if(isset($this->data['User']['new_password']))
		{
			$this->data['User']['password'] = AuthComponent::password($this->data['User']['new_password']);
		}
		
		// return true, otherwise save will return false
		return true;
	}
	
	/**
	 * Used for validating two form fields are identical
	 * @return True if both are identical, false otherwise
	 */
    function identicalFieldValues($field=array(), $compare_field=null) 
    {
        return($this->data[$this->name][$compare_field] === array_shift($field)); 
    } 
	
	/**
	 * Checks if the user entered their old password correctly
	 */
	function matchOldPassword($data)
	{
		$user = $this->find('first', array('conditions' => array('id' => 3), 'recursive' => -1));
		
		return AuthComponent::password($this->data['User']['old_password']) == $user['User']['password'];
	}
	
	/**
	 * Method to check if the logged in user's account has been disabled
	 * @return True if disabled, false if not
	 */
	function isAccountDisabled($id)
	{
		$this->recursive = -1;
		
		$user = $this->findById($id, array('fields' => 'disabled'));
		
		return (boolean) $user['User']['disabled'];
	}
}
?>
