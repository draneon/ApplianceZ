<?php

class ConfigControllerBase extends Zend_Controller_Action {

	protected function getFormParams() {
		$params = $this->_getAllParams();
		unset($params['controller'], $params['action'], $params['module']);
		return $params;
	}    

}