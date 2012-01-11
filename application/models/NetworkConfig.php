<?php

class Application_Model_NetworkConfig extends Application_Model_ConfigBase {
    protected $ipMode;
    protected $ipAddress;
    protected $gateway;
    protected $netmask;
    protected $hostname;
    protected $domain;
    protected $nameservers = array();

    const IpMode_Dhcp = 'dhcp';
    const IpMode_Static = 'static';

    public function __construct($params = NULL) {
        // params might not include the domain name, so load it ourselves
        Application_Model_NetworkConfigMapper::loadDomainName($this);
        parent::__construct($params);
    }

    public function getIpMode() {
        return $this->ipMode;
    }

    public function setIpMode($ipMode) {
        if ($ipMode != self::IpMode_Dhcp && $ipMode != self::IpMode_Static) {
            throw new exception('invalid ip mode');
        }
        $this->ipMode = $ipMode;
    }

    public function getIpAddress() {
        return $this->ipAddress;
    }

    public function setIpAddress($ipAddress) {
        $this->ipAddress = $ipAddress;
    }

    public function getNetmask() {
        return $this->netmask;
    }

    public function setNetmask($netmask) {
        $this->netmask = $netmask;
    }

    public function getGateway() {
        return $this->gateway;
    }

    public function setGateway($gateway) {
        $this->gateway = $gateway;
    }

    public function getHostname() {
        return $this->hostname;
    }

    public function setHostname($hostname) {
        $this->hostname = trim($hostname);
    }

    public function getScpPort() {
        return 104;
    }

    public function getDomain() {
        return $this->domain;
    }

    public function setDomain($domain) {
        $this->domain = $domain;
    }

    public function getNameservers() {
        return $this->nameservers;
    }

    public function setNameservers(array $nameservers) {
        $this->nameservers = $nameservers;
    }

}