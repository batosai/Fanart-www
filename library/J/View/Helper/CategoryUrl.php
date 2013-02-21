<?php

class Zend_View_Helper_CategoryUrl extends Zend_View_Helper_Abstract
{
  public function categoryUrl()
  {
    return $this->view->url(array(), 'categories', true);

    return $this->view->url(array('controller' => 'category'), 'default', true);
  }
}