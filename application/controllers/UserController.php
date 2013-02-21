<?php

class UserController extends Zend_Controller_Action
{
    private $_table;
    private $_filesTable;
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

      $this->_table = new Model_DbTable_Users();
      $this->_filesTable = new Model_DbTable_Files();
    }

    public function indexAction()
    {
      $this->view->breadcrumb = array(
        array(
          'name' => 'Accueil',
          'url' => $this->view->url(array(), 'default', true)
        ),
        array(
          'name' => 'Utilisateur',
          'active' => true
        )
      );

      $select = $this->_table->select()->where('validate = 1')->order('login ASC');

      $paginator = Zend_Paginator::factory($select);
      $paginator->setCurrentPageNumber($this->_getParam('page'));
      $paginator->setItemCountPerPage(20);

      $this->view->paginator = $paginator;
    }

    public function showAction()
    {
        $this->_session->previous = 'user';
        $select = $this->_table->select()->where('login_url = ?', $this->_getParam('slug'));
        $user = $this->_table->fetchRow($select);

        $this->view->breadcrumb = array(
          array(
            'name' => 'Accueil',
            'url' => $this->view->url(array(), 'default', true)
          ),
          array(
            'name' => 'Utilisateur',
            'url' => $this->view->usersUrl()
          ),
          array(
            'name' => $user->login,
            'active' => true
          )
        );

        $select = $this->_filesTable->select()->setIntegrityCheck(false)
                                              ->from($this->_filesTable)
                                              ->join('drawings', 'drawings.id=files.fk_id', array('drawing_id' => 'id', 'drawing_name' => 'name'))
                                              ->where('files.fk_name LIKE ?', '%drawings.id')
                                              ->where('drawings.visible = ?', 1)
                                              ->where('drawings.user_id = ?', $user->id)
                                              ->order('drawings.created_at DESC');

        $paginator = Zend_Paginator::factory($select);
        $paginator->setCurrentPageNumber($this->_getParam('page'));
        $paginator->setItemCountPerPage(16);

        $this->view->paginator = $paginator;
		$this->view->user = $user;
    }
}
