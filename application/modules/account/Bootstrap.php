<?php

class Account_Bootstrap extends Zend_Application_Module_Bootstrap
{
  protected function _initPlugins()
  {
    $this->bootstrap('frontController');

    $frontController = $this->getResource('frontController');
    $frontController->registerPlugin(new Account_Plugin_Auth());
  }
}
