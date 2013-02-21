<?php

class Admin_SubcategoryController extends Zend_Controller_Action
{
  private $_table;

  public function init()
  {
    $this->_table = new Model_DbTable_Subcategories();

    $this->view->breadcrumb = array(
      array(
        'name' => 'Accueil',
        'url' => $this->view->url(array('module' => 'admin'), 'default', true)
      ),
      array(
        'name' => 'Sous-Catégories',
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

  public function newAction()
  {
    $this->view->breadcrumb = array(
      array(
        'name' => 'Accueil',
        'url' => $this->view->url(array('module' => 'admin'), 'default', true)
      ),
      array(
        'name' => 'Sous-Catégories',
        'url' => $this->view->url(array('module' => 'admin', 'controller' => 'subcategory'), 'default', true)
      ),
      array(
        'name' => 'Ajouter une Sous-Catégories',
        'active' => true
      )
    );

    $this->view->form = new Admin_Form_Subcategory();
  }

  public function createAction()
  {
    $form = new Admin_Form_Subcategory();
    $data = $this->_request->getPost();

    if (!$form->isValid($data))
    {
      $this->_helper->flashMessenger($form);
      $this->view->form = $form;
      return $this->render('new');
    }

    $row = $this->_table->createRow($form->getValues());
    $row->name_url = $this->view->slug($row->name);
    $row->save();

    $this->_helper->redirector('index', 'subcategory', 'admin');
  }
}
