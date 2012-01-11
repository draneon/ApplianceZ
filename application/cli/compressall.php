#!/usr/bin/php
<?php

#start-stop-daemon requires signal handlers to be set, and declaring signal handlers requires ticks to be set.
declare(ticks=1);

$pidnum = pcntl_fork();

if ($pidnum == -1) {
        #Well, we can't daemonize for some reason.
        die("Problem - Could not fork child!\n");
} else if ($pidnum) {
        #The child process pid - returned by pcntl_fork() - has to be written to the file defined as pidfile for start-stop-daemon
        file_put_contents('/home/novarad/bin/compressall-daemon.pid',$pidnum);
        #We'll only be running a child process, so, this process need not continue.
        die("Detaching from terminal.\n");
} else {
        #We're the child. Continuing on below as the child.
}

if (posix_setsid() == -1) {
        #If we can't detach, we're not daemonized. No reason to go on.
        die("Problem - I could not detach!\n");
}

#Set up signal handlers -- Linked to the functions below.
pcntl_signal(SIGHUP, "SIGHUP");
pcntl_signal(SIGTERM, "SIGTERM");

function SIGHUP() {
        #Do what you want it to do upon receiving a SIGHUP
}

function SIGTERM() {
#Do what you want it to do upon receiving a SIGTERM. In this case, die.
        #die("Received SIGTERM\n");
}


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

