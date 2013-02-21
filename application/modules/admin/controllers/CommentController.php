<?php

class Admin_CommentController extends Zend_Controller_Action
{
  private $_table;

  public function init()
  {
    $this->_table = new Model_DbTable_Comments();

    $this->view->breadcrumb = array(
      array(
        'name' => 'Accueil',
        'url' => $this->view->url(array('module' => 'admin'), 'default', true)
      ),
      array(
        'name' => 'Commentaires',
        'active' => true
      )
    );
  }

  public function indexAction()
  {
    $select = $this->_table->select()->setIntegrityCheck(false)
                                     ->from($this->_table)
                                     ->join('users','users.id=comments.user_id',array('users.login AS login'))
                                     ->join('drawings','drawings.id=comments.drawing_id',array('drawings.name AS name'))
                                     ->order('created_at DESC');

    $paginator = Zend_Paginator::factory($select);
    $paginator->setCurrentPageNumber($this->_getParam('page'));
    $paginator->setItemCountPerPage(20);

    $this->view->paginator = $paginator;
  }

  public function deleteAction()
  {
    $comment = $this->_table->find($this->_getParam('id'))->current();
    $comment->delete();
    $this->_helper->redirector('index');
  }
}
