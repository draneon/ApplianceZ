<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    public function __construct($application) {
        parent::__construct($application);

        set_error_handler(function($errno, $errstr, $errfile, $errline) {
            throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
        });
    }
}

