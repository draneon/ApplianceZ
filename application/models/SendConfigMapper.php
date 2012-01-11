<?php

class Application_Model_SendConfigMapper extends Application_Model_ConfigMapperBase
{

	protected static function getConfigFileName() {
		return 'send';
	}

	protected static function getIniToObjectMap() {
		return array(
			'IMAGE_STORE_HOST' => 'storeHost',
			'IMAGE_STORE_PORT' => 'storePort',
            'CALLING_AE_TITLE' => 'callingAeTitle',
            'SITE_CODE' => 'siteCode',
            'COMPRESSION_ENABLED' => 'imageCompressionEnabled'
		);
	}

}

