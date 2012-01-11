#!/usr/bin/php
<?php

require_once('bootstrap.php');

$dirConfig = Application_Model_DirectoryConfigMapper::load(); /* @var $dirConfig Application_Model_DirectoryConfig */
$log = trim(Misc_Util::readErrorLogFile($dirConfig->getErrorLog()));

if ($log) {
    $body = $log."\n-----------------------------------\n".
        "Configuration:\n";
    $body .= Application_Model_SendConfigMapper::load()."\n\n";
    $body .= Application_Model_NetworkConfigMapper::load()."\n\n";
    $body .= $dirConfig."\n\n";
    foreach (Application_Model_Queue::getAllQueues() as $type => $q) {
        if ($q->getFileCount()) {
            $body .= "In Queue \"".$type."\"\n";
            $body .= $q."\n\n";
        }
    }

    $alertConfig = Application_Model_AlertConfigMapper::load();
    // sendEmail can throw an exception. I'll count on that to halt the script so
    // the log doesn't get cleaned below
    Misc_Util::sendEmail($alertConfig->getEmailAddress(), 'DICOM Router Error Logs', $body, $alertConfig->getSMTPHost());
}

// truncate the log file
$fd = fopen($dirConfig->getErrorLog(), 'w');
fclose($fd);
