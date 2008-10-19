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
			// convert values to bytes
			$this->data['SiteParameter']['default_quota'] = $this->Number->convert($this->data['SiteParameter']['default_quota'], $this->data['SiteParameter']['unit'], 'b');
			$this->data['SiteParameter']['upload_limit'] = $this->Number->convert($this->data['SiteParameter']['upload_limit'], 'mb', 'b');
			
			if( $this->SiteParameter->setParam('default_quota', $this->data['SiteParameter']['default_quota']) &&
				$this->SiteParameter->setParam('upload_limit', $this->data['SiteParameter']['upload_limit']))
			{
				$this->Session->setFlash('The new settings have been applied.', 'messages/success');
			}
			else $this->Session->setFlash('The settings could not be saved. Please check the errors below.', 'messages/error');
		}
		
		$this->set('quota', $this->Number->toReadableSize($this->SiteParameter->getParam('default_quota')));
		$this->set('upload_limit', $this->Number->toReadableSize($this->SiteParameter->getParam('upload_limit')));
	}
}
?>