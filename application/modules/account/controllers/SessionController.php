<?php

class Account_SessionController extends Zend_Controller_Action
{
  private $_auth;
  private $_table;

  public function init()
  {
    $this->_auth = Zend_Auth::getInstance();
    $this->_table = new Model_DbTable_Users();

    $this->view->breadcrumb = array(
      array(
        'name' => 'Accueil',
        'url' => $this->view->url(array(), 'default', true)
      )
    );
  }

  public function newAction()
  {
    $this->view->form = new Account_Form_Signin();

    $this->view->breadcrumb[] = array(
                                  'name' => 'Connexion',
                                  'active' => true
                                );
  }

  public function createAction()
  {
    $login = $this->_request->getPost('login');
    $password = $this->_request->getPost('password');

    $user = $this->_table->fetchOneByCredentials($login, $password);

    if ($user)
    {
      $this->_auth->getStorage()->write($user->id);

      $this->_helper->redirector('index', 'index', 'account');

      return;
    }
    else {
       $result = new Zend_Auth_Result(Zend_Auth_Result::FAILURE, $login);
    }

    if ($result->isValid()) {
      $this->_helper->redirector('index', 'index');
    }

    $this->_helper->flashMessenger(array('Identification impossible', 'ERRORS'));

    $this->view->form = new Account_Form_Signin();
    $this->view->form->login->setValue($login);

    $this->render('new');
  }

  public function destroyAction()
  {
    $this->_auth->clearIdentity();

    $this->_helper->redirector('new');
  }

  public function passwordAction()
  {
    $this->view->form = new Account_Form_Password();

    $this->view->breadcrumb[] = array(
                                  'name' => 'Mot de passe oublié',
                                  'active' => true
                                );
  }

  public function updatePasswordAction()
  {
    $email = $this->_request->getPost('email');

    if ($user = $this->_table->fetchByEmail($email))
    {
      $password = Opsone_Token::get(6);

      $user->password = $password;
      $user->save();

      $mailer = new Mailer_User();
      $mailer->password($user, $password);

      $this->_helper->flashMessenger('Vous allez recevoir un e-mail avec votre nouveau mot de passe.');

      $this->_helper->redirector('new', 'session');
    }

    $this->_helper->flashMessenger(array('Email inconnu', 'ERRORS'));
    $this->_helper->redirector('password', 'session');
  }
}
