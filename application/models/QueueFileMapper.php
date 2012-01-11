<?php

class Application_Model_QueueFileMapper
{

	public static function findAll($dirConfig) { /* @var $dirConfig Application_Model_DirectoryConfig */
        $dirToStatus = self::getDirToStatusMap($dirConfig);
        $queueFiles = array();
        foreach ($dirToStatus as $dir => $status) {
        }
        
        return $queueFiles;
	}

    public static function findByStatus($status, $dirConfig) {
        $statusToDir = self::getStatusToDirMap($dirConfig);
        return self::findByDir($statusToDir[$status], $status);
    }

    protected static function findByDir($dir, $status) {
        $queueFiles = array();
        foreach (glob($dir.'/*') as $file) {
            $queueFile = new Application_Model_QueueFile();
            $queueFile->setFilePath($file);
            $queueFile->setStatus($status);
            $queueFiles[] = $queueFile;
        }
        return $queueFiles;
    }

    protected static function getDirToStatusMap($dirConfig) {
		return array(
            $dirConfig->getQueueCompressed() => Application_Model_QueueFile::Status_Compressed,
            $dirConfig->getQueueFailed() => Application_Model_QueueFile::Status_Failed
		);
    }

    protected static function getStatusToDirMap($dirConfig) {
        return array_flip(self::getDirToStatusMap($dirConfig));
    }

}

