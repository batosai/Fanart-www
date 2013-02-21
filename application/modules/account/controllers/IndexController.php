<?php

class Account_IndexController extends Zend_Controller_Action
{
  public function init()
  {
    $this->view->breadcrumb = array(
      array(
        'name' => 'Accueil',
        'url' => $this->view->url(array(), 'default', true)
      ),
      array(
        'name' => 'Mon compte',
        'active' => true
      )
    );
  }

  public function indexAction()
  {

  }
}
