<?php

class Application_Model_SendConfig extends Application_Model_ConfigBase
{

    protected $storeHost;
	protected $storePort;
    protected $callingAeTitle;
    protected $siteCode;
    protected $imageCompressionEnabled;

	public function setStoreHost($host) {
		$this->storeHost = $host;
	}

	public function getStoreHost() {
		return $this->storeHost;
	}

	public function setStorePort($port) {
		$this->storePort = $port;
	}

	public function getStorePort() {
		return $this->storePort;
	}

    public function getCallingAeTitle() {
        return $this->callingAeTitle;
    }

    public function setCallingAeTitle($callingAeTitle) {
        $this->callingAeTitle = $callingAeTitle;
    }

    public function getSiteCode() {
        return $this->siteCode;
    }

    public function setSiteCode($siteCode) {
        $this->siteCode = trim($siteCode);
    }

    public function getImageCompressionEnabled() {
        return $this->imageCompressionEnabled;
    }

    public function setImageCompressionEnabled($imageCompressionEnabled) {
        $this->imageCompressionEnabled = (bool)$imageCompressionEnabled;
    }

}

