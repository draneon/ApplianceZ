<?php

class ErrorLogController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function clearAction()
    {
        $dirConfig = Application_Model_DirectoryConfigMapper::load();
        file_put_contents($dirConfig->getErrorLog(), '');
        $this->_helper->redirector('index');
    }

    public function indexAction()
    {
        $dirConfig = Application_Model_DirectoryConfigMapper::load();
        if (file_exists($dirConfig->getErrorLog())) {
            $this->view->errorLog = Misc_Util::readErrorLogFile($dirConfig->getErrorLog());
        } else {
            $this->view->errorLog = '';
        }
    }

}

