<?php 
class SiteParameter extends AppModel
{
	var $name = 'SiteParameter';
	var $primaryKey = 'key';
	var $validatae = array
	(
		'key' => array('custom', '/\S+/'),
		'value' => array('custom', '/\S+/'),
		'default_quota' => array
		(
			'rule' => 'numeric',
			'message' => 'The quota must be a numerical value',
			'allowEmpty' => false
		)
	);
	
	function setParam($key, $value)
	{
		$this->id = $key;
		return $this->saveField('value', $value, true);
	}
	
	function getParam($key)
	{
		return array_shift(array_shift($this->findByKey($key, array('fields' => 'value'))));
	}
}
?>