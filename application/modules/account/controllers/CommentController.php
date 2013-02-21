<?php

class Account_CommentController extends Zend_Controller_Action
{
  private $_table;

  public function init()
  {
    $this->view->breadcrumb = array(
      array(
        'name' => 'Accueil',
        'url' => $this->view->url(array(), 'default', true)
      ),
      array(
        'name' => 'Commentaires',
        'active' => true
      )
    );

    $this->_table = new Model_DbTable_Comments();
  }

  public function indexAction()
  {
    $select = $this->_table->select()->setIntegrityCheck(false)
                                     ->from($this->_table)
                                     ->join('drawings', 'drawings.id=comments.drawing_id', array('drawing_name' => 'name', 'drawing_id' => 'id'))
                                     ->join('users', 'users.id=comments.user_id', array('login'))
                                     ->where('drawings.user_id = ?', $this->view->identity->id)
                                     ->order('comments.created_at DESC');

    $paginator = Zend_Paginator::factory($select);
    $paginator->setCurrentPageNumber($this->_getParam('page'));
    $paginator->setItemCountPerPage(20);

    $this->view->paginator = $paginator;
  }
}
