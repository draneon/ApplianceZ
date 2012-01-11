<?php

class Application_Model_Queue
{

    /** @var array */
    protected $files;

    public function __construct($files) {
        $this->setFiles($files);
    }

    public function setFiles($files) {
        $this->files = $files;
    }

    public function getFiles() {
        return $this->files;
    }

    public function getFileCount() {
        return count($this->files);
    }

    public function getTotalFileSize() {
        return array_sum(
                array_map(function($f) { return $f->getSize(); }, $this->files));
    }

    public static function getAllQueues() {
        $dirConfig = Application_Model_DirectoryConfigMapper::load();
        $qs = array();
        foreach (Application_Model_QueueFile::$Textual_Status as $status => $text) {
            $qs[$text] = new Application_Model_Queue(Application_Model_QueueFileMapper::findByStatus($status, $dirConfig));
        }

        return $qs;
    }

    public function __toString() {
        $str = '';
        $total = 0;
        foreach ($this->files as $file) {
            $total += $file->getSize();
            $str .= $file->getFileName().' ('.Misc_Util::getHumanReadableBytes($file->getSize()).")\n";
        }
        $str .= "Total size: ".Misc_Util::getHumanReadableBytes($total);
        return $str;
    }

}
