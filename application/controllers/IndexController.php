<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $dirConfig = Application_Model_DirectoryConfigMapper::load();
        $this->view->queue = new Application_Model_Queue(
            Application_Model_QueueFileMapper::findByStatus(Application_Model_QueueFile::Status_Compressed, $dirConfig)
        );
        $this->view->failed = new Application_Model_Queue(
                Application_Model_QueueFileMapper::findByStatus(Application_Model_QueueFile::Status_Failed, $dirConfig)
        );
    }


}

