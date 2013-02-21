<?php

class Account_DrawingController extends Zend_Controller_Action
{
  private $_table;
  private $_drawing;

  public function init()
  {
    $this->_table = new Model_DbTable_Drawings();

    if ($id = $this->_getParam('id'))
    {
      $this->_drawing = $this->_table->find($id)->current();

      $this->view->breadcrumb = array(
        array(
          'name' => 'Accueil',
          'url' => $this->view->url(array(), 'default', true)
        ),
        array(
          'name' => 'Mes dessins',
          'url' => $this->view->url(array('module' => 'account', 'controller' => 'drawing'), 'default', true)
        ),
        array(
          'name' => $this->_drawing->name,
          'active' => true
        )
      );
    }
  }

  public function indexAction()
  {
    $this->view->breadcrumb = array(
      array(
        'name' => 'Accueil',
        'url' => $this->view->url(array(), 'default', true)
      ),
      array(
        'name' => 'Mes dessins',
        'active' => true
      )
    );

    $select = $this->_table->select()->setIntegrityCheck(false)
                                     ->from($this->_table)
                                     ->joinLeft('subcategories','subcategories.id=drawings.sub_category_id',array('subcategories.name AS sub_category_name'))
                                     ->join('users','users.id=drawings.user_id',array('users.login AS login', 'users.email AS email'))
                                     ->where('users.id = ?', $this->view->identity->id)
                                     ->order('drawings.created_at DESC');

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
        'url' => $this->view->url(array(), 'default', true)
      ),
      array(
        'name' => 'Mes dessins',
        'url' => $this->view->url(array('module' => 'account', 'controller' => 'drawing'), 'default', true)
      ),
      array(
        'name' => 'Créer',
        'active' => true
      )
    );

    $form = new Account_Form_Drawing();
    $this->view->form = $form;

    return $this->render('form');
  }

  public function createAction()
  {
    $this->view->breadcrumb = array(
      array(
        'name' => 'Accueil',
        'url' => $this->view->url(array(), 'default', true)
      ),
      array(
        'name' => 'Mes dessins',
        'url' => $this->view->url(array('module' => 'account', 'controller' => 'drawing'), 'default', true)
      ),
      array(
        'name' => 'Créer',
        'active' => true
      )
    );

    $form = new Account_Form_Drawing();
    $form->image->setDestination(APPLICATION_PATH . "/../data/uploads/users/{$this->view->identity->id}/drawings/");

    $data = $this->_request->getPost();

    if (!$form->isValid($data))
    {
      $this->_helper->flashMessenger($form);
      $this->view->form = $form;
      return $this->render('form');
    }

    $row = $this->_table->createRow($form->getValues() + array('user_id' => $this->view->identity->id));
    $row->save();

    $table = new Model_DbTable_Files();
    $file = $table->createRow(array('fk_name' => "users/{$this->view->identity->id}/drawings.id", 'fk_id' => $row->id));
    $file->setFile($form->image->getFileName());
    $file->save();

    $mailer = new Mailer_Contact();
    $mailer->create(array(
      'email' => $this->view->identity->email,
      'object' => 'Nouveau fanart',
      'message' => $form->comment->getValue()
    ));

    $this->_helper->redirector('index', 'drawing', 'account');
  }

  public function editAction()
  {
    $this->view->breadcrumb = array(
      array(
        'name' => 'Accueil',
        'url' => $this->view->url(array(), 'default', true)
      ),
      array(
        'name' => 'Mes dessins',
        'url' => $this->view->url(array('module' => 'account', 'controller' => 'drawing'), 'default', true)
      ),
      array(
        'name' => 'Editer',
        'active' => true
      )
    );

    $drawing = $this->_table->find($this->_getParam('id'))->current();

    if ($drawing->user_id != $this->view->identity->id) {
        $this->_helper->redirector('index', 'drawing', 'account');
    }

    $form = new Account_Form_Drawing();
    $form->setAction( $this->view->url(array('module' => 'account', 'controller' => 'drawing', 'action' => 'update', 'id' => $drawing->id), 'default', true));
    $form->removeElement('image');
    $form->removeElement('sub_category_text');
    $form->setDefaults($drawing->toArray());

    $this->view->form = $form;

    return $this->render('form');
  }

  public function updateAction()
  {
    $this->view->breadcrumb = array(
      array(
        'name' => 'Accueil',
        'url' => $this->view->url(array(), 'default', true)
      ),
      array(
        'name' => 'Mes dessins',
        'url' => $this->view->url(array('module' => 'account', 'controller' => 'drawing'), 'default', true)
      ),
      array(
        'name' => 'Editer',
        'active' => true
      )
    );

    $drawing = $this->_table->find($this->_getParam('id'))->current();

    if ($drawing->user_id != $this->view->identity->id) {
        $this->_helper->redirector('index', 'drawing', 'account');
    }

    $form = new Account_Form_Drawing();
    $form->setAction( $this->view->url(array('module' => 'account', 'controller' => 'drawing', 'action' => 'update', 'id' => $drawing->id), 'default', true));
    $form->removeElement('image');
    $form->removeElement('sub_category_text');

    $data = $this->_request->getPost();

    if (!$form->isValid($data))
    {
      $this->_helper->flashMessenger($form);
      $this->view->form = $form;
      return $this->render('form');
    }

    $drawing->setFromArray($form->getValues());
    $drawing->save();

    $this->_helper->redirector('index', 'drawing', 'account');
  }

  public function deleteAction()
  {
    $drawing = $this->_table->find($this->_getParam('id'))->current();

    if ($drawing->user_id != $this->view->identity->id) {
        $this->_helper->redirector('index', 'drawing', 'account');
    }

    $file = $drawing->findFile();
    $file->delete();
    $drawing->delete();

    $this->_helper->redirector('index', 'drawing', 'account');
  }
}
