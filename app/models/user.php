<?php 
class User extends AppModel
{
    var $name = 'User';
	var $hasMany = array
	(
        'Backup' => array
		(
            'className'     => 'Backup',
            'foreignKey'    => 'user_id',
        )
    );  
	var $validate = array
	(
		'email' => array
		(			
			'rule' => 'email',
			'required' => true,
			'message' => 'Please enter a valid email address'
		),
        'username' => array
		(
            'unique' => array
			(
                'rule' => 'isUnique',
                'required' => true,
                'message' => 'This username has already been taken, sorry!'
            ),
            'empty' => array
			(
				'rule' => array('minLength', '1'),
				'required' => true,
				'message' => 'Please enter your username'
            )
        ),
		'new_password' => array
		(
			'rule' => array('minLength', '6'),
			'message' => 'Password must be at least 6 characters long',
			'required' => true
        ),
		'confirm_password' => array
		(
			'rule' => array('identicalFieldValues', 'new_password' ),
			'message' => 'Please re-enter your password twice so that the values match',
			'required' => true
        ),
		'password' => array
		(
			'required' => true,
			'message' => 'Please enter your password'
		)
	);
	
	/**
	 * Called after validation, before data is stored in the database
	 */
	function beforeSave()
	{
		// hash the password before storing a new user in the database
		$this->data['User']['password'] = AuthComponent::password($this->data['User']['new_password']);
		
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
}
?>