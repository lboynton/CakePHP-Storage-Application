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
			'rule' => array('identicalFieldValues', 'new_password' ),
			'message' => 'Please enter your password again',
			'required' => true,
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
			'rule' => array('identicalFieldValues', 'new_password'),
			'message' => 'Please re-enter your password twice so that the values match',
			'required' => true
        ),
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
}
?>
