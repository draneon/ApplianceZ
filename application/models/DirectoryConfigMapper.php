<?php

class Application_Model_DirectoryConfigMapper extends Application_Model_ConfigMapperBase
{

	protected static function getConfigFileName() {
		return 'dirs';
	}

	protected static function getIniToObjectMap() {
		return array(
			'QUEUE_IN' => 'queueIn',
			'QUEUE_COMPRESSED' => 'queueCompressed',
			'QUEUE_FAILED' => 'queueFailed',
            'ERROR_LOG' => 'errorLog'
		);
	}

}

