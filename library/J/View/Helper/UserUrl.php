<?php

class Zend_View_Helper_UserUrl extends Zend_View_Helper_Abstract
{
  public function userUrl(Model_User $user)
  {
    $filter = new Opsone_Filter_Slug();

    return $this->view->url(array('slug' => $filter->filter($user->login)), 'user', true);

    return $this->view->url(array('controller' => 'user', 'action' => 'show', 'id' => $user->id), 'default', true);
  }
}