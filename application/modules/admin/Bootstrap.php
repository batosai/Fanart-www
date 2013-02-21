<?php

class Admin_Bootstrap extends Zend_Application_Module_Bootstrap
{
  protected function _initPlugins()
  {
    $this->bootstrap('frontController');

    $frontController = $this->getResource('frontController');
    $frontController->registerPlugin(new Admin_Plugin_Error());
    $frontController->registerPlugin(new Admin_Plugin_Auth());
  }
}
