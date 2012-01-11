<?php

require_once('ConfigControllerBase.php');

class AlertConfigController extends ConfigControllerBase
{

    public function indexAction()
    {
		$configs = $this->getConfigs();
		$this->view->alertConfig = $configs->alertConfig;
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
		$configs->alertConfig = Application_Model_AlertConfigMapper::load();
		return $configs;
	}

    protected function getConfigsFromParams($params) {
        $configs = new stdClass;
        $configs->alertConfig = new Application_Model_AlertConfig($params);
        return $configs;
    }

	protected function saveConfigs($configs) {
		Application_Model_AlertConfigMapper::save($configs->alertConfig);
	}

}
