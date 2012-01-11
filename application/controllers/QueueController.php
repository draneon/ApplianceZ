<?php

class QueueController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $this->view->queues = Application_Model_Queue::getAllQueues();
    }

}
