<?php

class IndexController extends Zend_Controller_Action
{
    private $_table;

    public function init()
    {
        $this->view->breadcrumb = array(
          array(
            'name' => 'Accueil',
            'active' => true
          )
        );
        $this->_table = new Model_DbTable_Files();
    }

    public function indexAction()
    {
        $select = $this->_table->select()->setIntegrityCheck(false)
                                         ->from($this->_table)
                                         ->join('drawings', 'drawings.id=files.fk_id', array('drawing_id' => 'id', 'drawing_name' => 'name'))
                                         ->where('files.fk_name LIKE ?', '%drawings.id')
                                         ->where('drawings.visible = ?', 1)
                                         ->order('drawings.created_at DESC');

        $paginator = Zend_Paginator::factory($select);
        $paginator->setCurrentPageNumber($this->_getParam('page'));
        $paginator->setItemCountPerPage(8);

        $this->view->paginator = $paginator;
    }
}
