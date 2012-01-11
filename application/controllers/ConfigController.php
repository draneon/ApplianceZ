<?php

require_once('ConfigControllerBase.php');

class ConfigController extends ConfigControllerBase
{

    public function indexAction()
    {
		$configs = $this->getConfigs();
		$this->view->sendConfig = $configs->sendConfig;
    }

	public function saveAction()
	{
		$params = $this->getFormParams();
		$configs = $this->getConfigsFromParams($params);

        $this->saveConfigs($configs);

        $this->_helper->redirector('index');
	}

	protected function getConfigs() {
		$configs = new stdClass;
		$configs->sendConfig = Application_Model_SendConfigMapper::load();
		return $configs;
	}

    protected function getConfigsFromParams($params) {
        $configs = new stdClass;
        $configs->sendConfig = new Application_Model_SendConfig($params);
        return $configs;
    }

	protected function saveConfigs($configs) {
		Application_Model_SendConfigMapper::save($configs->sendConfig);
	}

}
