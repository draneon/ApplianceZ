<?php

class Application_Model_AlertConfig extends Application_Model_ConfigBase
{

    protected $emailAddress;
	protected $smtpHost;

	public function setEmailAddress($addr) {
		$this->emailAddress = $addr;
	}

	public function getEmailAddress() {
		return $this->emailAddress;
	}

    public function setSMTPHost($host) {
        $this->smtpHost = $host;
    }

    public function getSMTPHost() {
        return $this->smtpHost;
    }

}
