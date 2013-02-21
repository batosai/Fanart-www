<?php

class Zend_View_Helper_UsersUrl extends Zend_View_Helper_Abstract
{
  public function usersUrl()
  {
    return $this->view->url(array(), 'users', true);

    return $this->view->url(array('controller' => 'user'), 'default', true);
  }
}