<?php

class ErrorController extends Zend_Controller_Action
{
  public function errorAction()
  {
    $errors = $this->_getParam('error_handler');

    if (!$errors) {
      $this->view->message = 'You have reached the error page';
      return;
    }

    switch ($errors->type)
    {
      case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
      case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
      case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:

        //START
        $filter = new Opsone_Filter_Slug();
        $usersTable = new Model_DbTable_Users();
        $subcategoriesTable = new Model_DbTable_Subcategories();

        $slug = $filter->filter($this->_getParam('controller'));

        $select = $subcategoriesTable->select()->setIntegrityCheck(false)
	                                           ->from($subcategoriesTable)
	                                           ->join('drawings', 'drawings.sub_category_id=subcategories.id', array())
											   ->where('name_url = ?', $slug)
											   ->limit(1);
        if ($subcategory = $subcategoriesTable->fetchRow($select))
        {
          $this->_helper->redirector->setCode(301)
                                    ->gotoRoute(array('id' => $subcategory->id, 'slug' => $filter->filter($subcategory->name)), 'category', true);
        }
        else
        {
          $select = $usersTable->select()->where('login_url = ?', $slug)->where('validate = 1')->limit(1);
          if ($user = $usersTable->fetchRow($select))
          {
            $this->_helper->redirector->setCode(301)
                                      ->gotoRoute(array('slug' => $filter->filter($user->login)), 'user', true);
          }
        }
        //END

        // 404 error -- controller or action not found
        $this->getResponse()->setHttpResponseCode(404);
        $this->view->message = 'Page not found: ' . $errors->request->getRequestUri();
        break;
      default:
        // application error
        $this->getResponse()->setHttpResponseCode(500);
        $this->view->message = 'Application error: ' . $errors->exception->getMessage();
        break;
    }

    // Log exception, if logger available
    if ($log = $this->getLog()) {
      $log->crit($this->view->message, $errors->exception);
    }

    // conditionally display exceptions
    if ($this->getInvokeArg('displayExceptions') == true) {
      $this->view->exception = $errors->exception;
    }

    $this->view->request = $errors->request;
  }

  public function getLog()
  {
    $bootstrap = $this->getInvokeArg('bootstrap');
    if (!$bootstrap->hasResource('Log')) {
      return false;
    }
    return $bootstrap->getResource('Log');
  }
}
