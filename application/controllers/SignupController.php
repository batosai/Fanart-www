<?php

class SignupController extends Zend_Controller_Action
{
    public function init()
    {
        $this->view->breadcrumb = array(
          array(
            'name' => 'Accueil',
            'url' => $this->view->url(array(), 'default', true)
          ),
          array(
            'name' => 'Inscription',
            'active' => true
          )
        );
    }

    public function indexAction()
    {
        $form = new Form_Signup();
        $this->view->form = $form;
    }

    public function createAction()
    {
        $form = new Form_Signup();

        if (!$form->isValid($this->_request->getPost()))
        {
          $this->_helper->flashMessenger($form);
          $this->view->form = $form;
          return $this->render('index');
        }

        $table = new Model_DbTable_Users();
        $user = $table->createRow($form->getValues());
        $user->save();

      	mkdir(UPLOADS_PATH . '/users/' . $user->id);
      	mkdir(UPLOADS_PATH . '/users/' . $user->id . '/drawings/');

        $mailer = new Mailer_User();
        $mailer->create($user, $form->password->getValue());

        $auth = Zend_Auth::getInstance();
        $auth->setStorage(new Zend_Auth_Storage_Session('Zend_Auth_Account'));
        $auth->getStorage()->write($user->id);

        $this->_helper->redirector('index', 'index', 'account');
    }
}
