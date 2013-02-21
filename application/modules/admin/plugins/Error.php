<?php

class Admin_Plugin_Error extends Zend_Controller_Plugin_Abstract
{
  public function routeShutdown(Zend_Controller_Request_Abstract $request)
  {
    $module = $request->getModuleName();

    if ($module == 'admin')
    {
      $frontController = Zend_Controller_Front::getInstance();

      $errorHandler = $frontController->getPlugin('Zend_Controller_Plugin_ErrorHandler');
      $errorHandler->setErrorHandlerModule($module);
    }
  }
}
