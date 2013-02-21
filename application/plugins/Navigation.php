<?php

class Plugin_Navigation extends Zend_Controller_Plugin_Abstract
{
  public function preDispatch(Zend_Controller_Request_Abstract $request)
  {
    if ($request->getControllerName() == 'sitemap')
    {
      $frontController = Zend_Controller_Front::getInstance();

      $bootstrap = $frontController->getParam('bootstrap');

      $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
      $viewRenderer->view->navigation($bootstrap->getResource('navigation'));
    }
  }
}
