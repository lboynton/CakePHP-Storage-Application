<?php
class SiteParametersController extends AppController
{
	var $name = 'SiteParameters';
	var $helpers = array('Html', 'Form');
	var $components = array('Number');
	
	function admin_index()
	{
		if(!empty($this->data))
		{
			// convert quota to bytes
			$this->data['SiteParameter']['default_quota'] = $this->Number->convert($this->data['SiteParameter']['default_quota'], $this->data['SiteParameter']['unit'], 'b');
			
			if($this->SiteParameter->setParam('default_quota', $this->data['SiteParameter']['default_quota']))
			{
				$this->Session->setFlash('The new settings have been applied.', 'messages/success');
			}
			else $this->Session->setFlash('The settings could not be saved. Please check the errors below.', 'messages/error');
		}
		
		$this->set('quota', $this->Number->toReadableSize($this->SiteParameter->getParam('default_quota')));
	}
}
?>