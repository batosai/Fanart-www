<?php

class Plugin_Routes extends Zend_Controller_Plugin_Abstract
{/*
  public function preDispatch(Zend_Controller_Request_Abstract $request)//postDispatch
  {
    $response = $this->getResponse();

    if ($response->isException())
    {
      $exceptions    = $response->getException();
      $exception     = $exceptions[0];
      $exceptionType = get_class($exception);

      switch ($exceptionType)
      {
          case 'Zend_Controller_Router_Exception':
              if (404 == $exception->getCode()) {
                  $error = Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE;
              }
              break;
          case 'Zend_Controller_Dispatcher_Exception':
              $error = Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER;
              break;
          case 'Zend_Controller_Action_Exception':
              if (404 == $exception->getCode()) {
                  $error = Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION;
              }
              break;
      }

      switch ($error)
      {
        case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
        case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
        case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
        // TRAITEMENT DE FALLBACK DE ROUTES

        $filter = new Opsone_Filter_Slug();
        $usersTable = new Model_DbTable_Users();
        $subcategoriesTable = new Model_DbTable_Subcategories();

        $slug = $filter->filter($request->getParam('controller'));

        $select = $subcategoriesTable->select()->where('name_url = ?', $slug)->limit(1);
        if ($subcategory = $subcategoriesTable->fetchRow($select))
        {
          $request->setParam('controller', 'category')
                  ->setParam('action', 'show')
                  ->setParam('slug', $subcategory->name_url)
                  ->setControllerName('Category')
                  ->setActionName('show')
                  ->setDispatched(true);
        }
        else
        {
          $select = $usersTable->select()->where('login_url = ?', $slug)->limit(1);
          if ($user = $usersTable->fetchRow($select))
          {
            $request->setParam('controller', 'user')
                    ->setParam('action', 'show')
                    ->setParam('slug', $user->login_url)
                    ->setControllerName('User')
                    ->setActionName('show')
                    ->setDispatched(true);
          }
        }
          break;
      }
    }
  }*/
}