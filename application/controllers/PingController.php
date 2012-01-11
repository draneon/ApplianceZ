<?php

class PingController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $sendConfig = Application_Model_SendConfigMapper::load();
        $statusCode = -1;
        $output = array();
        $error = false;
        try {
            DICOM_Util::pingSCU($sendConfig->getStoreHost(), $sendConfig->getStorePort());
        } catch (Exception $e) {
            $error = true;
        }
        $this->view->pingSuccessful = !$error;
        $this->_helper->layout->disableLayout();
        $this->getResponse()->setHeader('Content-Type', 'application/json');
    }

}

