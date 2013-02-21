<?php

class Account_ProfileController extends Zend_Controller_Action
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
        'name' => 'Profil',
        'active' => true
      )
    );

    $this->_table = new Model_DbTable_Users();
  }

  public function indexAction()
  {
    $form = new Account_Form_Profile();
    $form->setDefaults($this->view->identity->toArray());
    $this->view->form = $form;
  }

  public function updateAction()
  {
    $form = new Account_Form_Profile();
    $form->avatar->setDestination(APPLICATION_PATH . "/../data/uploads/users/{$this->view->identity->id}/");

    $data = $this->_request->getPost();
    if (empty($data['password']))
    {
      unset($data['password']);
      unset($data['password_confirm']);
    }

    if (!$form->isValidPartial($data) || !$form->isValidPartial(array('avatar' => $_FILES['avatar']['tmp_name'])))
    {
      $this->_helper->flashMessenger($form);
      $this->view->form = $form;
      return $this->render('index');
    }
    elseif($_FILES['avatar']['error'] == UPLOAD_ERR_OK)
    {
      $form->getValues();//déclanche l'upload de l'image
      $table = new Model_DbTable_Files();
      $select = $table->select()->where("fk_name = 'users/{$this->view->identity->id}.id' AND fk_id = ?", $this->view->identity->id);

      if ($row = $table->fetchRow($select))
      {
        $row->setFile($form->avatar->getFileName());
        $row->save();
      }
      else
      {
        $file = $table->createRow(array('fk_name' => "users/{$this->view->identity->id}.id", 'fk_id' => $this->view->identity->id));
        $file->setFile($form->avatar->getFileName());
        $file->save();
      }
    }
    else
    {
      $form->removeElement('avatar');
    }

    if (!isset($data['password']))
    {
      $form->removeElement('password');
      $form->removeElement('password_confirm');
    }

    $user = $this->_table->find($this->view->identity->id)->current();
    $user->setFromArray($form->getValues());
    $user->save();

    $this->_helper->flashMessenger('Enregistrement réussit.');
    $this->_helper->redirector('index', 'profile', 'account');
  }
}
