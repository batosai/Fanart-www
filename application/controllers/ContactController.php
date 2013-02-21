<?php

class ContactController extends Zend_Controller_Action
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
    }

    public function indexAction()
    {
      $form = new Form_Contact();

      if ($this->view->hasIdentity)
      {
        $form->setDefaults($this->view->identity->toArray());
      }

      $this->view->form = $form;
    }

    public function sendAction()
    {
        $form = new Form_Contact();

        if (!$form->isValid($this->_request->getPost()))
        {
          $this->_helper->flashMessenger($form);
          $this->view->form = $form;
          return $this->render('index');
        }

        $this->_helper->flashMessenger($form->getSuccess(), 'SUCCESS');
        $mailer = new Mailer_Contact();
        $mailer->create($this->_request->getPost());

        $this->_helper->redirector('index', 'contact');
    }
}
