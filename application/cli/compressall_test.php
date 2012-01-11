#!/usr/bin/php
<?php
require_once 'bootstrap.php';

$sendConfig = Application_Model_SendConfigMapper::load(); /* @var $sendConfig Application_Model_SendConfig */
$dirConfig = Application_Model_DirectoryConfigMapper::load(); /* @var $dirConfig Application_Model_DirectoryConfig */

foreach (glob($dirConfig->getQueueIn().'/*') as $inFile) {
    $outFile = $dirConfig->getQueueCompressed().'/'.basename($inFile);

    try {
        $siteCode = $sendConfig->getSiteCode();
        if (strlen($siteCode)) {
            DICOM_Util::insertTag($inFile, DICOM_Util::Tag_NovaradSiteCode, $siteCode);
        }

        if ($sendConfig->getImageCompressionEnabled()) {
            DICOM_Util::toJPEG2000($inFile, $outFile);
        } else {
            rename($inFile, $outFile);
        }
    } catch (exception $e) {
        // if it failed at this point, it's corruption or something unrecoverable like that
        $failedFile = $dirConfig->getQueueFailed().'/'.basename($inFile);
        rename($inFile, $failedFile);
    }
}
