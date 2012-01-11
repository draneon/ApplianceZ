<?php

class Application_Model_DirectoryConfig extends Application_Model_ConfigBase
{

    protected $queueIn;
	protected $queueCompressed;
	protected $queueFailed;
    protected $errorLog;
    protected $binDir;

	public function setQueueIn($queueIn) {
		$this->queueIn = $queueIn;
	}

	public function getQueueIn() {
		return $this->queueIn;
	}

	public function setQueueCompressed($queueCompressed) {
		$this->queueCompressed = $queueCompressed;
	}

	public function getQueueCompressed() {
		return $this->queueCompressed;
	}

	public function setQueueFailed($queueFailed) {
		$this->queueFailed = $queueFailed;
	}

	public function getQueueFailed() {
		return $this->queueFailed;
	}

    public function getErrorLog() {
        return $this->errorLog;
    }

    public function setErrorLog($errorLog) {
        $this->errorLog = $errorLog;
    }

    public function getBinDir() {
        return $this->binDir;
    }
    
}
