<?php
class MultipleValidatableBehavior extends ModelBehavior {
	var $__default = array();
	var $__useRules = array();

	function setup(&$model, $settings = array()) {
		$this->__default[$model->alias] = $model->validate;
	}

	function beforeValidate(&$model) {

	 	$actionSet = 'validate' . Inflector::camelize(Router::getParam('action'));

		if (isset($this->__useRules[$model->alias])) {
			$param = 'validate' . $this->__useRules[$model->alias];
			$model->validate = $model->{$param};
		} elseif (isset($model->{$actionSet})) {
			$param = $actionSet;
			$model->validate = $model->{$param};
		}
	}

	function useValidationRules(&$model, $param) {
		$this->__useRules[$model->alias] = $param;
	}

	function resetValidationRules(&$model) {
		$model->validate = $this->__default[$model->alias];
	}

}
?>