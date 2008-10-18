<?php 
class SiteParameter extends AppModel
{
	var $name = 'SiteParameter';
	var $primaryKey = 'key';
	var $validate = array
	(
		'default_quota' => array
		(
			'rule' => 'numeric',
			'message' => 'The quota must be a numerical value',
			'allowEmpty' => false
		),
		'upload_limit' => array
		(
			'rule' => 'numeric',
			'message' => 'The upload limit must be a numerical value',
			'allowEmpty' => false
		)
	);
	
	/**
	 * Sets a site parameter. If the supplied key is already present in the table it will be overwritten, else a new record will be created.
	 * @param key The name of the parameter to set
	 * @param value The value to give to the parameter
	 * @return True if the parameter and value could be saved, false if for some reason it could not be saved (usually because it failed validation).
	 */
	function setParam($key, $value)
	{
		$this->data['SiteParameter'][$key] = $value;
		
		return $this->save(array
		(
			'SiteParameter' => array
			(
				'key' => $key,
				'value' => $value
			)
		));
	}
	
	/**
	 * Gets the parameter for the given key.
	 * @param key The name of the parameter to retrieve
	 * @return The parameter, or false if the key is not present.
	 */
	function getParam($key)
	{
		$param = $this->findByKey($key, array('fields' => 'value'));
		
		if($param)
			return array_shift(array_shift($param));
		else
			return false;
	}
}
?>