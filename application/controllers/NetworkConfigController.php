<?php

require_once('ConfigControllerBase.php');

class NetworkConfigController extends ConfigControllerBase
{

    public function indexAction()
    {
		$configs = $this->getConfigs();
        $this->view->netConfig = $configs->netConfig;
    }

	public function saveAction()
	{
		$params = $this->getFormParams();
		$configs = $this->getConfigsFromParams($params);

        // TODO figure out how/if to redirect the client to our new address. probably will have to be client-side
		$this->saveConfigs($configs);

        // attempt to redirect to new address
        //$this->getResponse()->setHeader('Location', 'http://'.$configs->netConfig->getIpAddress());

        $this->_helper->redirector('index');
	}

	protected function getConfigs() {
		$configs = new stdClass;
        $configs->netConfig = Application_Model_NetworkConfigMapper::load();
		return $configs;
	}

    protected function getConfigsFromParams($params) {
        $configs = new stdClass;
        $configs->netConfig = new Application_Model_NetworkConfig($params);
        return $configs;
    }

	protected function saveConfigs($configs) {
        Application_Model_NetworkConfigMapper::save($configs->netConfig);
        Application_Model_NetworkConfigMapper::applyNetworkingChanges();
	}

}
