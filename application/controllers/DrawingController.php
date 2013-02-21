<?php

class DrawingController extends Zend_Controller_Action
{
    private $_table;
    private $_fileTable;
    private $_form;
    private $_session;

    public function init()
    {
      $this->_session = new Zend_Session_Namespace('drawing');
      $this->view->breadcrumb = array(
        array(
          'name' => 'Accueil',
          'active' => true
        )
      );
      $this->_fileTable = new Model_DbTable_Files();
      $this->_table = new Model_DbTable_Drawings();

      $this->_form = new Form_Comment();
    }

    public function showAction()
    {
      $this->_form->drawing_id->setValue($this->_getParam('id'));

      $select = $this->_table->select()->where('drawings.id = ?', $this->_getParam('id'))
                                       ->where('visible = 1')
                                       ->limit(1);

      $drawing = $this->_table->fetchRow($select);
	    $drawing->view += 1;
	    $drawing->save();

	    $subcategory = $drawing->findSubCategory();

      $this->view->drawing = $drawing;
      $this->view->file = $drawing->findFile();
      $this->view->comments = $drawing->findComments();
      $this->view->user = $drawing->findUser();
      $this->view->form = $this->_form;
	    $this->view->subcategory = $subcategory;

      if ($this->_session->previous == 'user')
      {
        $user = $drawing->findUser();
        $this->view->breadcrumb = array(
          array(
            'name' => 'Accueil',
            'url' => $this->view->url(array(), 'default', true)
          ),
          array(
            'name' => 'Utilisateurs',
            'url' => $this->view->usersUrl()
          ),
          array(
            'name' => $user->login,
            'url' => $this->view->userUrl($user)
          ),
          array(
            'name' => $drawing->name,
            'active' => true
          )
        );
      }
      elseif ($this->_session->previous == 'search')
      {
        $this->view->breadcrumb = array(
          array(
            'name' => 'Accueil',
            'url' => $this->view->url(array(), 'default', true)
          ),
          array(
            'name' => 'Recherche => ' . $this->_session->q,
            'url' => $this->view->baseUrl('/search/index/page/' . $this->_session->page . '?q=' . $this->_session->q)
          )
        );
      }
      else
      {
        $this->view->breadcrumb = array(
          array(
            'name' => 'Accueil',
            'url' => $this->view->url(array(), 'default', true)
          ),
          array(
            'name' => 'Catégories',
            'url' => $this->view->categoryUrl()
          ),
          array(
            'name' => $subcategory->name,
            'url' => $this->view->subcategoryUrl($subcategory)
          ),
          array(
            'name' => $drawing->name,
            'active' => true
          )
        );
      }
    }

    public function createAction()
    {
      $select = $this->_table->select()->where('drawings.id = ?', $this->_request->getPost('drawing_id'))
                                       ->where('visible = 1')
                                       ->limit(1);

      $drawing = $this->_table->fetchRow($select);

      if (!$this->_form->isValid($this->_request->getPost()))
      {
        $this->view->drawing = $drawing;
        $this->view->file = $drawing->findFile();
        $this->view->comments = $drawing->findComments();
        $this->view->user = $drawing->findUser();
        $this->view->form = $this->_form;

        $this->_helper->flashMessenger($this->_form);
        return $this->render('show');
      }

      $table = new Model_DbTable_Comments();
      $comment = $table->createRow($this->_form->getValues());
      $comment->user_id = $this->view->identity->id;
      $comment->save();

      $user = $drawing->finduser();

      if ($user->notification_comment && $user->id != $comment->user_id)
      {
        $mailer = new Mailer_Comment();
        $mailer->create($drawing, $comment);
      }

      $filter = new Opsone_Filter_Slug();

      $this->_helper->redirector->gotoRoute(array('id' => $drawing->id, 'slug' => $filter->filter($drawing->name)), 'drawing', true);
    }
}
