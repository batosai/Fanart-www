<?php

class Account_Plugin_Auth extends Zend_Controller_Plugin_Abstract
{
  public function preDispatch(Zend_Controller_Request_Abstract $request)
  {
    $auth = Zend_Auth::getInstance();
    $auth->setStorage(new Zend_Auth_Storage_Session('Zend_Auth_Account'));

    if ($request->getModuleName() == 'account')
    {
      $controllers = array('error', 'session');

      if (!in_array($request->getControllerName(), $controllers) && !$auth->hasIdentity())
      {
        $redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
        $redirector->gotoSimple('new', 'session');
      }
    }

    if(in_array($request->getModuleName(), array('account', 'default') ))
    {
      $table = new Model_DbTable_Users();
      $identity = $table->find($auth->getIdentity())->current();

      $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
      $viewRenderer->view->hasIdentity = $auth->hasIdentity();
      $viewRenderer->view->identity = $identity;

      $request->setParam('identity', $identity);
    }
  }
}