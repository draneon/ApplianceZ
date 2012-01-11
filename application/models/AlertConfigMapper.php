<?php

class Application_Model_AlertConfigMapper extends Application_Model_ConfigMapperBase
{

	protected static function getConfigFileName() {
		return 'alert';
	}

	protected static function getIniToObjectMap() {
		return array(
			'EMAIL_ADDRESS' => 'emailAddress',
			'SMTP_HOST' => 'smtpHost'
		);
	}

}

