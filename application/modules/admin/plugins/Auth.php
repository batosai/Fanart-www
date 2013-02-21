<?php

class Admin_Plugin_Auth extends Zend_Controller_Plugin_Abstract
{
  public function preDispatch(Zend_Controller_Request_Abstract $request)
  {
    if ($request->getModuleName() == 'admin')
    {
      $auth = Zend_Auth::getInstance();
      $auth->setStorage(new Zend_Auth_Storage_Session('Zend_Auth_Admin'));

      $controllers = array('error', 'session', 'upload');

      if (!in_array($request->getControllerName(), $controllers) && !$auth->hasIdentity())
      {
        $redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
        $redirector->gotoSimple('new', 'session');
      }

      $table = new Admin_Model_DbTable_Users();
      $identity = $table->find($auth->getIdentity())->current();

      $request->setParam('identity', $identity);

      $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
      $viewRenderer->view->hasIdentity = $auth->hasIdentity();
      $viewRenderer->view->identity = $identity;
    }
  }
}