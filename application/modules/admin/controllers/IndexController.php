<?php

class Admin_IndexController extends Zend_Controller_Action
{
  public function init()
  {
    $this->view->breadcrumb = array(
      array(
        'name' => 'Accueil',
        'url' => $this->view->url(array(), 'default', true)
      )
    );
  }

  public function indexAction()
  {

  }
}
