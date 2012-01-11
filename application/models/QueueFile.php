<?php

class Application_Model_QueueFile
{

    protected $filePath;
    protected $status;

    const Status_Compressed = 'compressed';
    const Status_Failed = 'failed';

    public static $All_Status = array(self::Status_Compressed, self::Status_Failed);
    public static $Textual_Status = array(
        self::Status_Compressed => 'Queued for uploading',
        self::Status_Failed => 'Failed'
    );
    
    public function setFilePath($path) {
        $this->filePath = $path;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public function getFileName() {
        return basename($this->filePath);
    }

    public function getDate() {
        return filemtime($this->filePath);
    }

    public function getSize() {
        return filesize($this->filePath);
    }

}

