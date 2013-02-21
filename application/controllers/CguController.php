<?php

class CguController extends Zend_Controller_Action
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
            'name' => 'Condition général d\'utilisation',
            'active' => true
          )
        );
        $this->_table = new Model_DbTable_Files();
    }

    public function indexAction()
    {
      
    }
}
