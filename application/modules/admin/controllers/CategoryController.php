<?php

class Admin_CategoryController extends Zend_Controller_Action
{
  private $_table;

  public function init()
  {
    $this->_table = new Model_DbTable_Categories();

    $this->view->breadcrumb = array(
      array(
        'name' => 'Accueil',
        'url' => $this->view->url(array('module' => 'admin'), 'default', true)
      ),
      array(
        'name' => 'Catégories',
        'active' => true
      )
    );
  }

  public function indexAction()
  {
    $select = $this->_table->fetchAll();

    $paginator = Zend_Paginator::factory($select);
    $paginator->setCurrentPageNumber($this->_getParam('page'));
    $paginator->setItemCountPerPage(20);

    $this->view->paginator = $paginator;
  }
}
