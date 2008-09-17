<?php
class AppController extends Controller
{
	// add custom link helper so it can be used in layouts
	var $helpers = array('Html', 'Menu', 'Javascript');
	
	// add authentication component for logging in users
	var $components = array('Auth');
	
	function beforeFilter()
	{
		$this->Auth->authorize = 'controller'; 
	
		// allow unregistered access to the homepage
		$this->Auth->allow(array('controller' => 'pages', 'action' => 'display', 'home'));
		// controller action access is defined on a per controller basis
		
		$this->_redirectToNamedParameters();
	}
	
	function isAuthorized() 
	{
		// Allow access to admin routes only to administrators
		if (isset($this->params[Configure::read('Routing.admin')])) 
		{
			return ((boolean)$this->Auth->user('admin'));
		}
		return true;
    }
	
	function beforeRender()
	{
        $this->_persistValidation();
    } 
	
	/**
	 * Checks for normal GET parameters and redirects them to the named parameter equivalent
	 */
	function _redirectToNamedParameters()
	{
		$namedParams = null;
		
		foreach($this->params['url'] as $param => $paramValue)
		{
			if($param != 'url') $namedParams .= $param . ":" . $paramValue . '/';
		}
		
		if(isset($namedParams))
		{
			if(isset($this->params['prefix']))
			{
				$this->params['action'] = substr($this->params['action'], strlen($this->params['prefix']) + 1);
				
				$this->redirect('/' . $this->params['prefix'] . '/' . $this->params['controller'] . '/' . $this->params['action'] . '/' . $namedParams);
			}
			else
			{
				$this->redirect('/' . $this->params['controller'] . '/' . $this->params['action'] . '/' . $namedParams);
			}
		}
	}
	
	/**
	 * Called with some arguments (name of default model, or model from var $uses),
	 * models with invalid data will populate data and validation errors into the session.
	 *
	 * Called without arguments, it will try to load data and validation errors from session 
	 * and attach them to proper models. Also merges $data to $this->data in controller.
	 * 
	 * @author poLK
	 * @author drayen aka Alex McFadyen
	 * 
	 * Licensed under The MIT License
	 * @license			http://www.opensource.org/licenses/mit-license.php The MIT License
	 */
	function _persistValidation() {
		$args = func_get_args();
		
		if (empty($args)) {
			if ($this->Session->check('Validation')) {
				$validation = $this->Session->read('Validation');
				$this->Session->del('Validation');
				foreach ($validation as $modelName => $sessData) {
					if ($this->name != $sessData['controller']){
						if (in_array($modelName, $this->modelNames)) {
							$Model =& $this->{$modelName};
						} elseif (ClassRegistry::isKeySet($modelName)) {
							$Model =& ClassRegistry::getObject($modelName);
						} else {
							continue;
						}
		
						$Model->data = $sessData['data'];
						$Model->validationErrors = $sessData['validationErrors'];
						$this->data = Set::merge($sessData['data'],$this->data);
					}
				}
			}
		} else {
			foreach($args as $modelName) {
				if (in_array($modelName, $this->modelNames) && !empty($this->{$modelName}->validationErrors)) {
						$this->Session->write('Validation.'.$modelName, array(
														'controller'			=>	$this->name,
														'data' 					=> $this->{$modelName}->data,
														'validationErrors' 	=> $this->{$modelName}->validationErrors
						));
				}
			}
		}
	}
}
?>